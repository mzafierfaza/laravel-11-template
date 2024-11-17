<?php

namespace App\Http\Controllers;

use App\Exports\QuestionOptionExport;
use App\Http\Requests\QuestionOptionRequest;
use App\Imports\QuestionOptionImport;
use App\Models\QuestionOption;
use App\Repositories\QuestionOptionRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class QuestionOptionController extends Controller
{
    /**
     * questionOptionRepository
     *
     * @var QuestionOptionRepository
     */
    private QuestionOptionRepository $questionOptionRepository;

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
        $this->questionOptionRepository      = new QuestionOptionRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Question Options');
        $this->middleware('can:Question Options Tambah')->only(['create', 'store']);
        $this->middleware('can:Question Options Ubah')->only(['edit', 'update']);
        $this->middleware('can:Question Options Hapus')->only(['destroy']);
        $this->middleware('can:Question Options Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Question Options Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.question-options.index', [
            'data'             => $this->questionOptionRepository->getLatest(),
            'canCreate'        => $user->can('Question Options Tambah'),
            'canUpdate'        => $user->can('Question Options Ubah'),
            'canDelete'        => $user->can('Question Options Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Question Options'),
            'routeCreate'      => route('question-options.create'),
            'routePdf'         => route('question-options.pdf'),
            'routePrint'       => route('question-options.print'),
            'routeExcel'       => route('question-options.excel'),
            'routeCsv'         => route('question-options.csv'),
            'routeJson'        => route('question-options.json'),
            'routeImportExcel' => route('question-options.import-excel'),
            'excelExampleLink' => route('question-options.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.question-options.form', [
            'title'         => __('Question Options'),
            'fullTitle'     => __('Tambah Question Options'),
            'routeIndex'    => route('question-options.index'),
            'action'        => route('question-options.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param QuestionOptionRequest $request
     * @return Response
     */
    public function store(QuestionOptionRequest $request)
    {
        $data = $request->only([
			'question_id',
			'option_text',
			'is_correct',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->questionOptionRepository->create($data);

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

        logCreate("Question Options", $result);

        $successMessage = successMessageCreate("Question Options");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param QuestionOption $questionOption
     * @return Response
     */
    public function edit(QuestionOption $questionOption)
    {
        return view('stisla.question-options.form', [
            'd'             => $questionOption,
            'title'         => __('Question Options'),
            'fullTitle'     => __('Ubah Question Options'),
            'routeIndex'    => route('question-options.index'),
            'action'        => route('question-options.update', [$questionOption->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param QuestionOptionRequest $request
     * @param QuestionOption $questionOption
     * @return Response
     */
    public function update(QuestionOptionRequest $request, QuestionOption $questionOption)
    {
        $data = $request->only([
			'question_id',
			'option_text',
			'is_correct',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->questionOptionRepository->update($data, $questionOption->id);

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

        logUpdate("Question Options", $questionOption, $newData);

        $successMessage = successMessageUpdate("Question Options");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param QuestionOption $questionOption
     * @return Response
     */
    public function destroy(QuestionOption $questionOption)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($questionOption);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($questionOption);

        $this->questionOptionRepository->delete($questionOption->id);
        logDelete("Question Options", $questionOption);

        $successMessage = successMessageDelete("Question Options");
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

        $data = $this->questionOptionRepository->getLatest();
        return Excel::download(new QuestionOptionExport($data), 'question-options.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new QuestionOptionImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Question Options");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->questionOptionRepository->getLatest();
        return $this->fileService->downloadJson($data, 'question-options.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->questionOptionRepository->getLatest();
        return (new QuestionOptionExport($data))->download('question-options.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->questionOptionRepository->getLatest();
        return (new QuestionOptionExport($data))->download('question-options.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->questionOptionRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.question-options.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('question-options.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->questionOptionRepository->getLatest();
        return view('stisla.question-options.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
