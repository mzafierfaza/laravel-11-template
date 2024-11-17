<?php

namespace App\Http\Controllers;

use App\Exports\QuizAttemptExport;
use App\Http\Requests\QuizAttemptRequest;
use App\Imports\QuizAttemptImport;
use App\Models\QuizAttempt;
use App\Repositories\QuizAttemptRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class QuizAttemptController extends Controller
{
    /**
     * quizAttemptRepository
     *
     * @var QuizAttemptRepository
     */
    private QuizAttemptRepository $quizAttemptRepository;

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
        $this->quizAttemptRepository      = new QuizAttemptRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Quiz Attempts');
        $this->middleware('can:Quiz Attempts Tambah')->only(['create', 'store']);
        $this->middleware('can:Quiz Attempts Ubah')->only(['edit', 'update']);
        $this->middleware('can:Quiz Attempts Hapus')->only(['destroy']);
        $this->middleware('can:Quiz Attempts Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Quiz Attempts Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.quiz-attempts.index', [
            'data'             => $this->quizAttemptRepository->getLatest(),
            'canCreate'        => $user->can('Quiz Attempts Tambah'),
            'canUpdate'        => $user->can('Quiz Attempts Ubah'),
            'canDelete'        => $user->can('Quiz Attempts Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Quiz Attempts'),
            'routeCreate'      => route('quiz-attempts.create'),
            'routePdf'         => route('quiz-attempts.pdf'),
            'routePrint'       => route('quiz-attempts.print'),
            'routeExcel'       => route('quiz-attempts.excel'),
            'routeCsv'         => route('quiz-attempts.csv'),
            'routeJson'        => route('quiz-attempts.json'),
            'routeImportExcel' => route('quiz-attempts.import-excel'),
            'excelExampleLink' => route('quiz-attempts.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.quiz-attempts.form', [
            'title'         => __('Quiz Attempts'),
            'fullTitle'     => __('Tambah Quiz Attempts'),
            'routeIndex'    => route('quiz-attempts.index'),
            'action'        => route('quiz-attempts.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param QuizAttemptRequest $request
     * @return Response
     */
    public function store(QuizAttemptRequest $request)
    {
        $data = $request->only([
			'enrollment_id',
			'quiz_id',
			'start_time',
			'submit_time',
			'score',
			'is_passed',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->quizAttemptRepository->create($data);

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

        logCreate("Quiz Attempts", $result);

        $successMessage = successMessageCreate("Quiz Attempts");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param QuizAttempt $quizAttempt
     * @return Response
     */
    public function edit(QuizAttempt $quizAttempt)
    {
        return view('stisla.quiz-attempts.form', [
            'd'             => $quizAttempt,
            'title'         => __('Quiz Attempts'),
            'fullTitle'     => __('Ubah Quiz Attempts'),
            'routeIndex'    => route('quiz-attempts.index'),
            'action'        => route('quiz-attempts.update', [$quizAttempt->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param QuizAttemptRequest $request
     * @param QuizAttempt $quizAttempt
     * @return Response
     */
    public function update(QuizAttemptRequest $request, QuizAttempt $quizAttempt)
    {
        $data = $request->only([
			'enrollment_id',
			'quiz_id',
			'start_time',
			'submit_time',
			'score',
			'is_passed',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->quizAttemptRepository->update($data, $quizAttempt->id);

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

        logUpdate("Quiz Attempts", $quizAttempt, $newData);

        $successMessage = successMessageUpdate("Quiz Attempts");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param QuizAttempt $quizAttempt
     * @return Response
     */
    public function destroy(QuizAttempt $quizAttempt)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($quizAttempt);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($quizAttempt);

        $this->quizAttemptRepository->delete($quizAttempt->id);
        logDelete("Quiz Attempts", $quizAttempt);

        $successMessage = successMessageDelete("Quiz Attempts");
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

        $data = $this->quizAttemptRepository->getLatest();
        return Excel::download(new QuizAttemptExport($data), 'quiz-attempts.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new QuizAttemptImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Quiz Attempts");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->quizAttemptRepository->getLatest();
        return $this->fileService->downloadJson($data, 'quiz-attempts.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->quizAttemptRepository->getLatest();
        return (new QuizAttemptExport($data))->download('quiz-attempts.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->quizAttemptRepository->getLatest();
        return (new QuizAttemptExport($data))->download('quiz-attempts.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->quizAttemptRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.quiz-attempts.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('quiz-attempts.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->quizAttemptRepository->getLatest();
        return view('stisla.quiz-attempts.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
