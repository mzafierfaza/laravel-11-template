<?php

namespace App\Http\Controllers;

use App\Exports\QuestionExport;
use App\Http\Requests\QuestionRequest;
use App\Imports\QuestionImport;
use App\Models\Question;
use App\Repositories\QuestionRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class QuestionController extends Controller
{
    private QuestionRepository $questionRepository;

    private NotificationRepository $NotificationRepository;

    private UserRepository $UserRepository;

    private FileService $fileService;
    private EmailService $emailService;

    private bool $exportable = false;

    private bool $importable = false;

    public function __construct()
    {
        $this->questionRepository      = new QuestionRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Questions');
        $this->middleware('can:Questions Tambah')->only(['create', 'store']);
        $this->middleware('can:Questions Ubah')->only(['edit', 'update']);
        $this->middleware('can:Questions Hapus')->only(['destroy']);
        $this->middleware('can:Questions Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Questions Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.questions.index', [
            'data'             => $this->questionRepository->getLatest(),
            'canCreate'        => $user->can('Questions Tambah'),
            'canUpdate'        => $user->can('Questions Ubah'),
            'canDelete'        => $user->can('Questions Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Questions'),
            'routeCreate'      => route('questions.create'),
            'routePdf'         => route('questions.pdf'),
            'routePrint'       => route('questions.print'),
            'routeExcel'       => route('questions.excel'),
            'routeCsv'         => route('questions.csv'),
            'routeJson'        => route('questions.json'),
            'routeImportExcel' => route('questions.import-excel'),
            'excelExampleLink' => route('questions.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.questions.form', [
            'title'         => __('Questions'),
            'fullTitle'     => __('Tambah Questions'),
            'routeIndex'    => route('questions.index'),
            'action'        => route('questions.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param QuestionRequest $request
     * @return Response
     */
    public function store(QuestionRequest $request)
    {
        $data = $request->only([
            'quiz_id',
            'question_text',
            'type',
            'points',
            'correct_essay_answer',
            'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->questionRepository->create($data);

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

        logCreate("Questions", $result);

        $successMessage = successMessageCreate("Questions");
        return redirect()->back()->with('successMessage', $successMessage);
    }


    public function edit(Question $question)
    {
        return view('stisla.questions.form', [
            'd'             => $question,
            'title'         => __('Questions'),
            'fullTitle'     => __('Ubah Questions'),
            'routeIndex'    => route('questions.index'),
            'action'        => route('questions.update', [$question->id])
        ]);
    }
    public function update(QuestionRequest $request, Question $question)
    {
        $data = $request->only([
            'quiz_id',
            'question_text',
            'type',
            'points',
            'correct_essay_answer',
            'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->questionRepository->update($data, $question->id);

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

        logUpdate("Questions", $question, $newData);

        $successMessage = successMessageUpdate("Questions");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    public function destroy(Question $question)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($question);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($question);

        $this->questionRepository->delete($question->id);
        logDelete("Questions", $question);

        $successMessage = successMessageDelete("Questions");
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

        $data = $this->questionRepository->getLatest();
        return Excel::download(new QuestionExport($data), 'questions.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        // dd($request->all());
        // dd($request->get('quiz_id'));
        Excel::import(new QuestionImport($request->quiz_id, $request->is_essay), $request->file('import_file'));
        $successMessage = successMessageImportExcel("Questions");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->questionRepository->getLatest();
        return $this->fileService->downloadJson($data, 'questions.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->questionRepository->getLatest();
        return (new QuestionExport($data))->download('questions.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->questionRepository->getLatest();
        return (new QuestionExport($data))->download('questions.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->questionRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.questions.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('questions.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->questionRepository->getLatest();
        return view('stisla.questions.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
