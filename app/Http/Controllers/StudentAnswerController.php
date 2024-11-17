<?php

namespace App\Http\Controllers;

use App\Exports\StudentAnswerExport;
use App\Http\Requests\StudentAnswerRequest;
use App\Imports\StudentAnswerImport;
use App\Models\StudentAnswer;
use App\Repositories\StudentAnswerRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class StudentAnswerController extends Controller
{
    /**
     * studentAnswerRepository
     *
     * @var StudentAnswerRepository
     */
    private StudentAnswerRepository $studentAnswerRepository;

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
        $this->studentAnswerRepository      = new StudentAnswerRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Student Answers');
        $this->middleware('can:Student Answers Tambah')->only(['create', 'store']);
        $this->middleware('can:Student Answers Ubah')->only(['edit', 'update']);
        $this->middleware('can:Student Answers Hapus')->only(['destroy']);
        $this->middleware('can:Student Answers Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Student Answers Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.student-answers.index', [
            'data'             => $this->studentAnswerRepository->getLatest(),
            'canCreate'        => $user->can('Student Answers Tambah'),
            'canUpdate'        => $user->can('Student Answers Ubah'),
            'canDelete'        => $user->can('Student Answers Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Student Answers'),
            'routeCreate'      => route('student-answers.create'),
            'routePdf'         => route('student-answers.pdf'),
            'routePrint'       => route('student-answers.print'),
            'routeExcel'       => route('student-answers.excel'),
            'routeCsv'         => route('student-answers.csv'),
            'routeJson'        => route('student-answers.json'),
            'routeImportExcel' => route('student-answers.import-excel'),
            'excelExampleLink' => route('student-answers.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.student-answers.form', [
            'title'         => __('Student Answers'),
            'fullTitle'     => __('Tambah Student Answers'),
            'routeIndex'    => route('student-answers.index'),
            'action'        => route('student-answers.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param StudentAnswerRequest $request
     * @return Response
     */
    public function store(StudentAnswerRequest $request)
    {
        $data = $request->only([
			'quiz_attempt_id',
			'question_id',
			'selected_option_id',
			'essay_answer',
			'score',
			'teacher_comment',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->studentAnswerRepository->create($data);

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

        logCreate("Student Answers", $result);

        $successMessage = successMessageCreate("Student Answers");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param StudentAnswer $studentAnswer
     * @return Response
     */
    public function edit(StudentAnswer $studentAnswer)
    {
        return view('stisla.student-answers.form', [
            'd'             => $studentAnswer,
            'title'         => __('Student Answers'),
            'fullTitle'     => __('Ubah Student Answers'),
            'routeIndex'    => route('student-answers.index'),
            'action'        => route('student-answers.update', [$studentAnswer->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param StudentAnswerRequest $request
     * @param StudentAnswer $studentAnswer
     * @return Response
     */
    public function update(StudentAnswerRequest $request, StudentAnswer $studentAnswer)
    {
        $data = $request->only([
			'quiz_attempt_id',
			'question_id',
			'selected_option_id',
			'essay_answer',
			'score',
			'teacher_comment',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->studentAnswerRepository->update($data, $studentAnswer->id);

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

        logUpdate("Student Answers", $studentAnswer, $newData);

        $successMessage = successMessageUpdate("Student Answers");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param StudentAnswer $studentAnswer
     * @return Response
     */
    public function destroy(StudentAnswer $studentAnswer)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($studentAnswer);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($studentAnswer);

        $this->studentAnswerRepository->delete($studentAnswer->id);
        logDelete("Student Answers", $studentAnswer);

        $successMessage = successMessageDelete("Student Answers");
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

        $data = $this->studentAnswerRepository->getLatest();
        return Excel::download(new StudentAnswerExport($data), 'student-answers.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new StudentAnswerImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Student Answers");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->studentAnswerRepository->getLatest();
        return $this->fileService->downloadJson($data, 'student-answers.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->studentAnswerRepository->getLatest();
        return (new StudentAnswerExport($data))->download('student-answers.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->studentAnswerRepository->getLatest();
        return (new StudentAnswerExport($data))->download('student-answers.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->studentAnswerRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.student-answers.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('student-answers.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->studentAnswerRepository->getLatest();
        return view('stisla.student-answers.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
