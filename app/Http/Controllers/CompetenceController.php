<?php

namespace App\Http\Controllers;

use App\Exports\CompetenceExport;
use App\Http\Requests\CompetenceRequest;
use App\Imports\CompetenceImport;
use App\Models\Competence;
use App\Repositories\CompetenceCourseRepository;
use App\Repositories\CompetenceRepository;
use App\Repositories\CourseRepository;
use App\Repositories\EnrollmentRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Repositories\UsersRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CompetenceController extends Controller
{
    private CompetenceRepository $competenceRepository;
    private CompetenceCourseRepository $competenceCourseRepository;
    private EnrollmentRepository $enrollmentRepository;
    private UsersRepository $usersRepository;
    private CourseRepository $courseRepository;
    private NotificationRepository $NotificationRepository;
    private UserRepository $UserRepository;
    private FileService $fileService;
    private EmailService $emailService;

    private bool $exportable = false;

    private bool $importable = false;

    public function __construct()
    {
        $this->competenceRepository      = new CompetenceRepository;
        $this->usersRepository      = new UsersRepository;
        $this->courseRepository      = new CourseRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;
        $this->enrollmentRepository = new EnrollmentRepository;
        $this->competenceCourseRepository = new CompetenceCourseRepository;

        $this->middleware('can:Competences');
        $this->middleware('can:Competences Tambah')->only(['create', 'store']);
        $this->middleware('can:Competences Ubah')->only(['edit', 'update']);
        $this->middleware('can:Competences Hapus')->only(['destroy']);
        $this->middleware('can:Competences Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Competences Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    public function index()
    {
        $user = auth()->user();
        return view('stisla.competences.index', [
            'data'             => $this->competenceRepository->getLatest(),
            'canCreate'        => $user->can('Competences Tambah'),
            'canUpdate'        => $user->can('Competences Ubah'),
            'canDelete'        => $user->can('Competences Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Kompetensi'),
            'routeCreate'      => route('competences.create'),
            'routePdf'         => route('competences.pdf'),
            'routePrint'       => route('competences.print'),
            'routeExcel'       => route('competences.excel'),
            'routeCsv'         => route('competences.csv'),
            'routeJson'        => route('competences.json'),
            'routeImportExcel' => route('competences.import-excel'),
            'excelExampleLink' => route('competences.import-excel-example'),
            'canApprove'       => $user->canApprove(),
            'canBypass'       => $user->canBypass(),
        ]);
    }

    public function approve(Competence $competence, Request $request)
    {
        $old = $competence;
        $competence->approved_at = now();
        $competence->approved_status = $request->get('approved_status');
        $competence->approved_by = auth()->user()->id;
        $competence->save();
        $successMessage = "Berhasil reject peserta";

        if ($request->get('approved_status') == 1) {
            $successMessage = "Berhasil approve user ";
        }

        logUpdate("Approve Users", $old, $competence);
        return redirect()->back()->with('successMessage', $successMessage);
    }

    public function create()
    {
        $trainings = $this->courseRepository->getAll();
        $persons = $this->usersRepository->getAll();

        // dd('trainings', $trainings, $persons);

        return view('stisla.competences.form', [
            'trainings' => $trainings,
            'persons' => $persons,
            'title'         => __('Competences'),
            'fullTitle'     => __('Tambah Competences'),
            'routeIndex'    => route('competences.index'),
            'action'        => route('competences.store')
        ]);
    }

    public function store(CompetenceRequest $request)
    {
        $data = $request->only([
            'title',
            'level',
            'start_date',
            'end_date',
            'description',
            'benefit',
            'level',
            'image',
            'certificate',
        ]);

        // gunakan jika ada file
        // dd($request->hasFile('image'));
        if ($request->hasFile('image')) {
            $file = $request->file(key: 'image');
            $upload = $this->fileService->uploadMinio($file, 'competences/images/');
            if ($upload) {
                $res = $upload->getData();
                $data['image'] = $res->url;
            }
        }
        // dd($data);

        if ($request->hasFile('certificate')) {
            $file = $request->file(key: 'certificate');
            $upload = $this->fileService->uploadMinio($file, 'competences/certificates/');
            if ($upload) {
                $res = $upload->getData();
                $data['certificate'] = $res->url;
            }
        }

        $data["created_by"] = auth()->user()->id;
        $data["approved_status"] = 0;

        $result = $this->competenceRepository->create($data);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        logCreate("Competences", $result);

        $courses = $request->courses;
        $persons = $request->persons;

        foreach ($courses as $course) {
            // dd($courses);
            $data = [
                "course_id" => $course,
                "competence_id" => $result->id
            ];
            $this->competenceCourseRepository->create($data);
        }

        foreach ($persons as $person) {
            $data = [
                "user_id" => $person,
                "competence_id" => $result->id,
                "enrolled_date" => date("Y-m-d H:i:s"),
                "enrollment_status" => 'Enrolled',
            ];
            $this->enrollmentRepository->create($data);
        }

        $successMessage = successMessageCreate("Competences");
        return redirect()->back()->with('successMessage', $successMessage);
    }


    public function show(Competence $competence)
    {
        $user = auth()->user();
        $enrollments = $competence->enrollments()->get();
        $competence_courses = $competence->courses()->orderBy('order')->get();

        return view('stisla.competences.show', [
            'competence' => $competence,
            'competence_courses' => $competence_courses,
            'enrollmentsCounts' => $enrollments->count(),
            'coursesCounts' => $competence_courses->count(),
            'isAjaxYajra' => true,
            'routeCreateCourses' => route(name: 'competence-courses.create', parameters: ['competence_id' => $competence->id]),
            'routeIndex'    => route(name: 'competences.index'),
            'fullTitle'     => $competence->title,
            'title' => 'Kompetensi'
        ]);
    }

    public function edit(Competence $competence)
    {
        $trainings = $this->courseRepository->getAll();

        return view('stisla.competences.form', [
            'd'             => $competence,
            'trainings' => $trainings,
            'title'         => __('Competences'),
            'fullTitle'     => __('Ubah Competences'),
            'routeIndex'    => route('competences.index'),
            'action'        => route('competences.update', [$competence->id])
        ]);
    }

    public function update(CompetenceRequest $request, Competence $competence)
    {
        $data = $request->only([
            'title',
            'level',
            'start_date',
            'end_date',
            'description',
            'benefit',
            'image',
            'certificate',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }
        $data["approved_status"] = 0;

        // gunakan jika ada file
        // dd($request->hasFile('image'));
        if ($request->hasFile('image')) {
            $file = $request->file(key: 'image');
            $upload = $this->fileService->uploadMinio($file, 'competences/images/');
            if ($upload) {
                $res = $upload->getData();
                $data['image'] = $res->url;
            }
        }
        // dd($data);

        if ($request->hasFile('certificate')) {
            $file = $request->file(key: 'certificate');
            $upload = $this->fileService->uploadMinio($file, 'competences/certificates/');
            if ($upload) {
                $res = $upload->getData();
                $data['certificate'] = $res->url;
            }
        }


        $newData = $this->competenceRepository->update($data, $competence->id);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($newData);

        logUpdate("Competences", $competence, $newData);

        $successMessage = successMessageUpdate("Competences");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    public function destroy(Competence $competence)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($competence);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($competence);

        $this->competenceRepository->delete($competence->id);
        logDelete("Competences", $competence);

        $successMessage = successMessageDelete("Competences");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        // bisa gunakan file excel langsung sebagai contoh
        // $filepath = public_path('example.xlsx');
        // return response()->download($filepath);

        $data = $this->competenceRepository->getLatest();
        return Excel::download(new CompetenceExport($data), 'competences.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new CompetenceImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Competences");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->competenceRepository->getLatest();
        return $this->fileService->downloadJson($data, 'competences.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->competenceRepository->getLatest();
        return (new CompetenceExport($data))->download('competences.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->competenceRepository->getLatest();
        return (new CompetenceExport($data))->download('competences.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->competenceRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.competences.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('competences.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->competenceRepository->getLatest();
        return view('stisla.competences.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
