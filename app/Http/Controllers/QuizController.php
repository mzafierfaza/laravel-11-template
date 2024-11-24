<?php

namespace App\Http\Controllers;

use App\Exports\QuizExport;
use App\Http\Requests\QuizRequest;
use App\Imports\QuizImport;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class QuizController extends Controller
{
    /**
     * quizRepository
     *
     * @var QuizRepository
     */
    private QuizRepository $quizRepository;

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
        $this->quizRepository      = new QuizRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Quizzes');
        $this->middleware('can:Quizzes Tambah')->only(['create', 'store']);
        $this->middleware('can:Quizzes Ubah')->only(['edit', 'update']);
        $this->middleware('can:Quizzes Hapus')->only(['destroy']);
        $this->middleware('can:Quizzes Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Quizzes Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.quizzes.index', [
            'data'             => $this->quizRepository->getLatest(),
            'canCreate'        => $user->can('Quizzes Tambah'),
            'canUpdate'        => $user->can('Quizzes Ubah'),
            'canDelete'        => $user->can('Quizzes Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Quizzes'),
            'routeCreate'      => route('quizzes.create'),
            'routePdf'         => route('quizzes.pdf'),
            'routePrint'       => route('quizzes.print'),
            'routeExcel'       => route('quizzes.excel'),
            'routeCsv'         => route('quizzes.csv'),
            'routeJson'        => route('quizzes.json'),
            'routeImportExcel' => route('quizzes.import-excel'),
            'excelExampleLink' => route('quizzes.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.quizzes.form', [
            'title'         => __('Quizzes'),
            'fullTitle'     => __('Tambah Quizzes'),
            'routeIndex'    => route('quizzes.index'),
            'action'        => route('quizzes.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param QuizRequest $request
     * @return Response
     */
    public function store(QuizRequest $request)
    {
        $data = $request->only([
            'module_id',
            'title',
            'description',
            'duration_minutes',
            'passing_score',
            'start_time',
            'end_time',
            'is_randomize',
            'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->quizRepository->create($data);

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

        logCreate("Quizzes", $result);

        $successMessage = successMessageCreate("Quizzes");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Quiz $quiz
     * @return Response
     */
    public function edit(Quiz $quiz)
    {
        return view('stisla.quizzes.form', [
            'd'             => $quiz,
            'title'         => __('Quizzes'),
            'fullTitle'     => __('Ubah Quizzes'),
            'routeIndex'    => route('quizzes.index'),
            'action'        => route('quizzes.update', [$quiz->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return Response
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        $data = $request->only([
            'module_id',
            'title',
            'description',
            'duration_minutes',
            'passing_score',
            'start_time',
            'end_time',
            'is_randomize',
            'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->quizRepository->update($data, $quiz->id);

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

        logUpdate("Quizzes", $quiz, $newData);

        $successMessage = successMessageUpdate("Quizzes");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Quiz $quiz
     * @return Response
     */
    public function destroy(Quiz $quiz)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($quiz);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($quiz);

        $this->quizRepository->delete($quiz->id);
        logDelete("Quizzes", $quiz);

        $successMessage = successMessageDelete("Quizzes");
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

        $data = $this->quizRepository->getLatest();
        return Excel::download(new QuizExport($data), 'quizzes.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new QuizImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Quizzes");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->quizRepository->getLatest();
        return $this->fileService->downloadJson($data, 'quizzes.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->quizRepository->getLatest();
        return (new QuizExport($data))->download('quizzes.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->quizRepository->getLatest();
        return (new QuizExport($data))->download('quizzes.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->quizRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.quizzes.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('quizzes.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->quizRepository->getLatest();
        return view('stisla.quizzes.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
