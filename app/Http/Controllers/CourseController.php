<?php

namespace App\Http\Controllers;

use App\Exports\CourseExport;
use App\Http\Requests\CourseRequest;
use App\Imports\CourseImport;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CourseController extends Controller
{
    /**
     * courseRepository
     *
     * @var CourseRepository
     */
    private CourseRepository $courseRepository;

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
        $this->courseRepository      = new CourseRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Courses');
        $this->middleware('can:Courses Tambah')->only(['create', 'store']);
        $this->middleware('can:Courses Ubah')->only(['edit', 'update']);
        $this->middleware('can:Courses Hapus')->only(['destroy']);
        $this->middleware('can:Courses Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Courses Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.courses.index', [
            'data'             => $this->courseRepository->getLatest(),
            'canCreate'        => $user->can('Courses Tambah'),
            'canUpdate'        => $user->can('Courses Ubah'),
            'canDelete'        => $user->can('Courses Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Courses'),
            'routeCreate'      => route('courses.create'),
            'routePdf'         => route('courses.pdf'),
            'routePrint'       => route('courses.print'),
            'routeExcel'       => route('courses.excel'),
            'routeCsv'         => route('courses.csv'),
            'routeJson'        => route('courses.json'),
            'routeImportExcel' => route('courses.import-excel'),
            'excelExampleLink' => route('courses.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.courses.form', [
            'title'         => __('Courses'),
            'fullTitle'     => __('Tambah Courses'),
            'routeIndex'    => route('courses.index'),
            'action'        => route('courses.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param CourseRequest $request
     * @return Response
     */
    public function store(CourseRequest $request)
    {
        $data = $request->only([
			'title',
			'description',
			'procedurs',
			'topic',
			'format',
			'is_random_material',
			'is_premium',
			'price',
			'created_by',
			'is_active',
			'start_date',
			'end_date',
			'start_time',
			'end_time',
			'address',
			'is_repeat_enrollment',
			'max_repeat_enrollment',
			'max_enrollment',
			'is_class_test',
			'is_class_finish',
			'status',
			'approved_status',
			'approved_at',
			'approved_by',
			'teacher_id',
			'teacher_about',
			'image',
			'certificate',
			'certificate_can_download',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->courseRepository->create($data);

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

        logCreate("Courses", $result);

        $successMessage = successMessageCreate("Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Course $course
     * @return Response
     */
    public function edit(Course $course)
    {
        return view('stisla.courses.form', [
            'd'             => $course,
            'title'         => __('Courses'),
            'fullTitle'     => __('Ubah Courses'),
            'routeIndex'    => route('courses.index'),
            'action'        => route('courses.update', [$course->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param CourseRequest $request
     * @param Course $course
     * @return Response
     */
    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->only([
			'title',
			'description',
			'procedurs',
			'topic',
			'format',
			'is_random_material',
			'is_premium',
			'price',
			'created_by',
			'is_active',
			'start_date',
			'end_date',
			'start_time',
			'end_time',
			'address',
			'is_repeat_enrollment',
			'max_repeat_enrollment',
			'max_enrollment',
			'is_class_test',
			'is_class_finish',
			'status',
			'approved_status',
			'approved_at',
			'approved_by',
			'teacher_id',
			'teacher_about',
			'image',
			'certificate',
			'certificate_can_download',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->courseRepository->update($data, $course->id);

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

        logUpdate("Courses", $course, $newData);

        $successMessage = successMessageUpdate("Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Course $course
     * @return Response
     */
    public function destroy(Course $course)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($course);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($course);

        $this->courseRepository->delete($course->id);
        logDelete("Courses", $course);

        $successMessage = successMessageDelete("Courses");
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

        $data = $this->courseRepository->getLatest();
        return Excel::download(new CourseExport($data), 'courses.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new CourseImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Courses");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->courseRepository->getLatest();
        return $this->fileService->downloadJson($data, 'courses.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->courseRepository->getLatest();
        return (new CourseExport($data))->download('courses.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->courseRepository->getLatest();
        return (new CourseExport($data))->download('courses.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->courseRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.courses.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('courses.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->courseRepository->getLatest();
        return view('stisla.courses.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
