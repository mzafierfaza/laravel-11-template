<?php

namespace App\Http\Controllers;

use App\Exports\ModuleExport;
use App\Http\Requests\ModuleRequest;
use App\Imports\ModuleImport;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class ModuleController extends Controller
{
    /**
     * moduleRepository
     *
     * @var ModuleRepository
     */
    private ModuleRepository $moduleRepository;

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
        $this->moduleRepository      = new ModuleRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Modules');
        $this->middleware('can:Modules Tambah')->only(['create', 'store']);
        $this->middleware('can:Modules Ubah')->only(['edit', 'update']);
        $this->middleware('can:Modules Hapus')->only(['destroy']);
        $this->middleware('can:Modules Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Modules Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.modules.index', [
            'data'             => $this->moduleRepository->getLatest(),
            'canCreate'        => $user->can('Modules Tambah'),
            'canUpdate'        => $user->can('Modules Ubah'),
            'canDelete'        => $user->can('Modules Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Modules'),
            'routeCreate'      => route('modules.create'),
            'routePdf'         => route('modules.pdf'),
            'routePrint'       => route('modules.print'),
            'routeExcel'       => route('modules.excel'),
            'routeCsv'         => route('modules.csv'),
            'routeJson'        => route('modules.json'),
            'routeImportExcel' => route('modules.import-excel'),
            'excelExampleLink' => route('modules.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.modules.form', [
            'title'         => __('Modules'),
            'fullTitle'     => __('Tambah Modules'),
            'routeIndex'    => route('modules.index'),
            'action'        => route('modules.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param ModuleRequest $request
     * @return Response
     */
    public function store(ModuleRequest $request)
    {
        $data = $request->only([
			'course_id',
			'title',
			'description',
			'order',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->moduleRepository->create($data);

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

        logCreate("Modules", $result);

        $successMessage = successMessageCreate("Modules");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Module $module
     * @return Response
     */
    public function edit(Module $module)
    {
        return view('stisla.modules.form', [
            'd'             => $module,
            'title'         => __('Modules'),
            'fullTitle'     => __('Ubah Modules'),
            'routeIndex'    => route('modules.index'),
            'action'        => route('modules.update', [$module->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param ModuleRequest $request
     * @param Module $module
     * @return Response
     */
    public function update(ModuleRequest $request, Module $module)
    {
        $data = $request->only([
			'course_id',
			'title',
			'description',
			'order',
			'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->moduleRepository->update($data, $module->id);

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

        logUpdate("Modules", $module, $newData);

        $successMessage = successMessageUpdate("Modules");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Module $module
     * @return Response
     */
    public function destroy(Module $module)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($module);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($module);

        $this->moduleRepository->delete($module->id);
        logDelete("Modules", $module);

        $successMessage = successMessageDelete("Modules");
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

        $data = $this->moduleRepository->getLatest();
        return Excel::download(new ModuleExport($data), 'modules.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new ModuleImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Modules");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->moduleRepository->getLatest();
        return $this->fileService->downloadJson($data, 'modules.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->moduleRepository->getLatest();
        return (new ModuleExport($data))->download('modules.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->moduleRepository->getLatest();
        return (new ModuleExport($data))->download('modules.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->moduleRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.modules.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('modules.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->moduleRepository->getLatest();
        return view('stisla.modules.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
