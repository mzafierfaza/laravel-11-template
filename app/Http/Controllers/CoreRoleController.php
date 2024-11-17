<?php

namespace App\Http\Controllers;

use App\Exports\CoreRoleExport;
use App\Http\Requests\CoreRoleRequest;
use App\Imports\CoreRoleImport;
use App\Models\CoreRole;
use App\Repositories\CoreRoleRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CoreRoleController extends Controller
{
    /**
     * coreRoleRepository
     *
     * @var CoreRoleRepository
     */
    private CoreRoleRepository $coreRoleRepository;

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
        $this->coreRoleRepository      = new CoreRoleRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Roles');
        $this->middleware('can:Roles Tambah')->only(['create', 'store']);
        $this->middleware('can:Roles Ubah')->only(['edit', 'update']);
        $this->middleware('can:Roles Hapus')->only(['destroy']);
        $this->middleware('can:Roles Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Roles Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.core-roles.index', [
            'data'             => $this->coreRoleRepository->getLatest(),
            'canCreate'        => $user->can('Roles Tambah'),
            'canUpdate'        => $user->can('Roles Ubah'),
            'canDelete'        => $user->can('Roles Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Roles'),
            'routeCreate'      => route('core-roles.create'),
            'routePdf'         => route('core-roles.pdf'),
            'routePrint'       => route('core-roles.print'),
            'routeExcel'       => route('core-roles.excel'),
            'routeCsv'         => route('core-roles.csv'),
            'routeJson'        => route('core-roles.json'),
            'routeImportExcel' => route('core-roles.import-excel'),
            'excelExampleLink' => route('core-roles.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.core-roles.form', [
            'title'         => __('Roles'),
            'fullTitle'     => __('Tambah Roles'),
            'routeIndex'    => route('core-roles.index'),
            'action'        => route('core-roles.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param CoreRoleRequest $request
     * @return Response
     */
    public function store(CoreRoleRequest $request)
    {
        $data = $request->only([
			'name',
			'group_id',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->coreRoleRepository->create($data);

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

        logCreate("Roles", $result);

        $successMessage = successMessageCreate("Roles");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param CoreRole $coreRole
     * @return Response
     */
    public function edit(CoreRole $coreRole)
    {
        return view('stisla.core-roles.form', [
            'd'             => $coreRole,
            'title'         => __('Roles'),
            'fullTitle'     => __('Ubah Roles'),
            'routeIndex'    => route('core-roles.index'),
            'action'        => route('core-roles.update', [$coreRole->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param CoreRoleRequest $request
     * @param CoreRole $coreRole
     * @return Response
     */
    public function update(CoreRoleRequest $request, CoreRole $coreRole)
    {
        $data = $request->only([
			'name',
			'group_id',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->coreRoleRepository->update($data, $coreRole->id);

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

        logUpdate("Roles", $coreRole, $newData);

        $successMessage = successMessageUpdate("Roles");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param CoreRole $coreRole
     * @return Response
     */
    public function destroy(CoreRole $coreRole)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($coreRole);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($coreRole);

        $this->coreRoleRepository->delete($coreRole->id);
        logDelete("Roles", $coreRole);

        $successMessage = successMessageDelete("Roles");
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

        $data = $this->coreRoleRepository->getLatest();
        return Excel::download(new CoreRoleExport($data), 'core-roles.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new CoreRoleImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Roles");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->coreRoleRepository->getLatest();
        return $this->fileService->downloadJson($data, 'core-roles.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->coreRoleRepository->getLatest();
        return (new CoreRoleExport($data))->download('core-roles.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->coreRoleRepository->getLatest();
        return (new CoreRoleExport($data))->download('core-roles.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->coreRoleRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.core-roles.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('core-roles.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->coreRoleRepository->getLatest();
        return view('stisla.core-roles.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
