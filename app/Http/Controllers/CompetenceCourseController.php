<?php

namespace App\Http\Controllers;

use App\Exports\CompetenceCourseExport;
use App\Http\Requests\CompetenceCourseRequest;
use App\Imports\CompetenceCourseImport;
use App\Models\CompetenceCourse;
use App\Repositories\CompetenceCourseRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CompetenceCourseController extends Controller
{
    /**
     * competenceCourseRepository
     *
     * @var CompetenceCourseRepository
     */
    private CompetenceCourseRepository $competenceCourseRepository;

    /**
     * NotificationRepository
     *
     * @var NotificationRepository
     */
    private NotificationRepository $NotificationRepository;

    /**
     * UserRepository
     *
     * @var UserRepository
     */
    private UserRepository $UserRepository;

    /**
     * file service
     *
     * @var FileService
     */
    private FileService $fileService;

    /**
     * email service
     *
     * @var FileService
     */
    private EmailService $emailService;
    
    /**
     * exportable
     *
     * @var bool
     */
    private bool $exportable = false;

    /**
     * importable
     *
     * @var bool
     */
    private bool $importable = false;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->competenceCourseRepository      = new CompetenceCourseRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Competence Courses');
        $this->middleware('can:Competence Courses Tambah')->only(['create', 'store']);
        $this->middleware('can:Competence Courses Ubah')->only(['edit', 'update']);
        $this->middleware('can:Competence Courses Hapus')->only(['destroy']);
        $this->middleware('can:Competence Courses Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Competence Courses Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.competence-courses.index', [
            'data'             => $this->competenceCourseRepository->getLatest(),
            'canCreate'        => $user->can('Competence Courses Tambah'),
            'canUpdate'        => $user->can('Competence Courses Ubah'),
            'canDelete'        => $user->can('Competence Courses Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Competence Courses'),
            'routeCreate'      => route('competence-courses.create'),
            'routePdf'         => route('competence-courses.pdf'),
            'routePrint'       => route('competence-courses.print'),
            'routeExcel'       => route('competence-courses.excel'),
            'routeCsv'         => route('competence-courses.csv'),
            'routeJson'        => route('competence-courses.json'),
            'routeImportExcel' => route('competence-courses.import-excel'),
            'excelExampleLink' => route('competence-courses.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.competence-courses.form', [
            'title'         => __('Competence Courses'),
            'fullTitle'     => __('Tambah Competence Courses'),
            'routeIndex'    => route('competence-courses.index'),
            'action'        => route('competence-courses.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param CompetenceCourseRequest $request
     * @return Response
     */
    public function store(CompetenceCourseRequest $request)
    {
        $data = $request->only([
			'competence_id',
			'course_id',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->competenceCourseRepository->create($data);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($result);

        logCreate("Competence Courses", $result);

        $successMessage = successMessageCreate("Competence Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param CompetenceCourse $competenceCourse
     * @return Response
     */
    public function edit(CompetenceCourse $competenceCourse)
    {
        return view('stisla.competence-courses.form', [
            'd'             => $competenceCourse,
            'title'         => __('Competence Courses'),
            'fullTitle'     => __('Ubah Competence Courses'),
            'routeIndex'    => route('competence-courses.index'),
            'action'        => route('competence-courses.update', [$competenceCourse->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param CompetenceCourseRequest $request
     * @param CompetenceCourse $competenceCourse
     * @return Response
     */
    public function update(CompetenceCourseRequest $request, CompetenceCourse $competenceCourse)
    {
        $data = $request->only([
			'competence_id',
			'course_id',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->competenceCourseRepository->update($data, $competenceCourse->id);

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

        logUpdate("Competence Courses", $competenceCourse, $newData);

        $successMessage = successMessageUpdate("Competence Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param CompetenceCourse $competenceCourse
     * @return Response
     */
    public function destroy(CompetenceCourse $competenceCourse)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($competenceCourse);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($competenceCourse);

        $this->competenceCourseRepository->delete($competenceCourse->id);
        logDelete("Competence Courses", $competenceCourse);

        $successMessage = successMessageDelete("Competence Courses");
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

        $data = $this->competenceCourseRepository->getLatest();
        return Excel::download(new CompetenceCourseExport($data), 'competence-courses.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new CompetenceCourseImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Competence Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->competenceCourseRepository->getLatest();
        return $this->fileService->downloadJson($data, 'competence-courses.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->competenceCourseRepository->getLatest();
        return (new CompetenceCourseExport($data))->download('competence-courses.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->competenceCourseRepository->getLatest();
        return (new CompetenceCourseExport($data))->download('competence-courses.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->competenceCourseRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.competence-courses.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('competence-courses.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->competenceCourseRepository->getLatest();
        return view('stisla.competence-courses.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
