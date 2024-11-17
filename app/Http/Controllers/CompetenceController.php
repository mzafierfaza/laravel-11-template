<?php

namespace App\Http\Controllers;

use App\Exports\CompetenceExport;
use App\Http\Requests\CompetenceRequest;
use App\Imports\CompetenceImport;
use App\Models\Competence;
use App\Repositories\CompetenceRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CompetenceController extends Controller
{
    /**
     * competenceRepository
     *
     * @var CompetenceRepository
     */
    private CompetenceRepository $competenceRepository;

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
        $this->competenceRepository      = new CompetenceRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Competences');
        $this->middleware('can:Competences Tambah')->only(['create', 'store']);
        $this->middleware('can:Competences Ubah')->only(['edit', 'update']);
        $this->middleware('can:Competences Hapus')->only(['destroy']);
        $this->middleware('can:Competences Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Competences Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
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
            'title'            => __('Competences'),
            'routeCreate'      => route('competences.create'),
            'routePdf'         => route('competences.pdf'),
            'routePrint'       => route('competences.print'),
            'routeExcel'       => route('competences.excel'),
            'routeCsv'         => route('competences.csv'),
            'routeJson'        => route('competences.json'),
            'routeImportExcel' => route('competences.import-excel'),
            'excelExampleLink' => route('competences.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.competences.form', [
            'title'         => __('Competences'),
            'fullTitle'     => __('Tambah Competences'),
            'routeIndex'    => route('competences.index'),
            'action'        => route('competences.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param CompetenceRequest $request
     * @return Response
     */
    public function store(CompetenceRequest $request)
    {
        $data = $request->only([
			'title',
			'level',
			'certificate',
			'certificate_can_download',
			'image',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->competenceRepository->create($data);

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

        logCreate("Competences", $result);

        $successMessage = successMessageCreate("Competences");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Competence $competence
     * @return Response
     */
    public function edit(Competence $competence)
    {
        return view('stisla.competences.form', [
            'd'             => $competence,
            'title'         => __('Competences'),
            'fullTitle'     => __('Ubah Competences'),
            'routeIndex'    => route('competences.index'),
            'action'        => route('competences.update', [$competence->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param CompetenceRequest $request
     * @param Competence $competence
     * @return Response
     */
    public function update(CompetenceRequest $request, Competence $competence)
    {
        $data = $request->only([
			'title',
			'level',
			'certificate',
			'certificate_can_download',
			'image',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

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

    /**
     * delete user from db
     *
     * @param Competence $competence
     * @return Response
     */
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
