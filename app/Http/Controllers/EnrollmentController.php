<?php

namespace App\Http\Controllers;

use App\Exports\EnrollmentExport;
use App\Http\Requests\EnrollmentRequest;
use App\Imports\EnrollmentImport;
use App\Models\Enrollment;
use App\Repositories\EnrollmentRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class EnrollmentController extends Controller
{
    /**
     * enrollmentRepository
     *
     * @var EnrollmentRepository
     */
    private EnrollmentRepository $enrollmentRepository;

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
        $this->enrollmentRepository      = new EnrollmentRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Enrollments');
        $this->middleware('can:Enrollments Tambah')->only(['create', 'store']);
        $this->middleware('can:Enrollments Ubah')->only(['edit', 'update']);
        $this->middleware('can:Enrollments Hapus')->only(['destroy']);
        $this->middleware('can:Enrollments Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Enrollments Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.enrollments.index', [
            'data'             => $this->enrollmentRepository->getLatest(),
            'canCreate'        => $user->can('Enrollments Tambah'),
            'canUpdate'        => $user->can('Enrollments Ubah'),
            'canDelete'        => $user->can('Enrollments Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Enrollments'),
            'routeCreate'      => route('enrollments.create'),
            'routePdf'         => route('enrollments.pdf'),
            'routePrint'       => route('enrollments.print'),
            'routeExcel'       => route('enrollments.excel'),
            'routeCsv'         => route('enrollments.csv'),
            'routeJson'        => route('enrollments.json'),
            'routeImportExcel' => route('enrollments.import-excel'),
            'excelExampleLink' => route('enrollments.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.enrollments.form', [
            'title'         => __('Enrollments'),
            'fullTitle'     => __('Tambah Enrollments'),
            'routeIndex'    => route('enrollments.index'),
            'action'        => route('enrollments.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param EnrollmentRequest $request
     * @return Response
     */
    public function store(EnrollmentRequest $request)
    {
        $data = $request->only([
			'user_id',
			'competence_id',
			'enrolled_date',
			'status',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->enrollmentRepository->create($data);

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

        logCreate("Enrollments", $result);

        $successMessage = successMessageCreate("Enrollments");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Enrollment $enrollment
     * @return Response
     */
    public function edit(Enrollment $enrollment)
    {
        return view('stisla.enrollments.form', [
            'd'             => $enrollment,
            'title'         => __('Enrollments'),
            'fullTitle'     => __('Ubah Enrollments'),
            'routeIndex'    => route('enrollments.index'),
            'action'        => route('enrollments.update', [$enrollment->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param EnrollmentRequest $request
     * @param Enrollment $enrollment
     * @return Response
     */
    public function update(EnrollmentRequest $request, Enrollment $enrollment)
    {
        $data = $request->only([
			'user_id',
			'competence_id',
			'enrolled_date',
			'status',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->enrollmentRepository->update($data, $enrollment->id);

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

        logUpdate("Enrollments", $enrollment, $newData);

        $successMessage = successMessageUpdate("Enrollments");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Enrollment $enrollment
     * @return Response
     */
    public function destroy(Enrollment $enrollment)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($enrollment);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($enrollment);

        $this->enrollmentRepository->delete($enrollment->id);
        logDelete("Enrollments", $enrollment);

        $successMessage = successMessageDelete("Enrollments");
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

        $data = $this->enrollmentRepository->getLatest();
        return Excel::download(new EnrollmentExport($data), 'enrollments.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new EnrollmentImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Enrollments");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->enrollmentRepository->getLatest();
        return $this->fileService->downloadJson($data, 'enrollments.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->enrollmentRepository->getLatest();
        return (new EnrollmentExport($data))->download('enrollments.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->enrollmentRepository->getLatest();
        return (new EnrollmentExport($data))->download('enrollments.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->enrollmentRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.enrollments.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('enrollments.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->enrollmentRepository->getLatest();
        return view('stisla.enrollments.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
