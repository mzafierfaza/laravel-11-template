<?php

namespace App\Http\Controllers;

use App\Exports\CoreGroupExport;
use App\Http\Requests\CoreGroupRequest;
use App\Imports\CoreGroupImport;
use App\Models\CoreGroup;
use App\Repositories\CoreGroupRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class CoreGroupController extends Controller
{
    /**
     * coreGroupRepository
     *
     * @var CoreGroupRepository
     */
    private CoreGroupRepository $coreGroupRepository;

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
        $this->coreGroupRepository      = new CoreGroupRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Groups');
        $this->middleware('can:Groups Tambah')->only(['create', 'store']);
        $this->middleware('can:Groups Ubah')->only(['edit', 'update']);
        $this->middleware('can:Groups Hapus')->only(['destroy']);
        $this->middleware('can:Groups Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Groups Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.core-groups.index', [
            'data'             => $this->coreGroupRepository->getLatest(),
            'canCreate'        => $user->can('Groups Tambah'),
            'canUpdate'        => $user->can('Groups Ubah'),
            'canDelete'        => $user->can('Groups Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Groups'),
            'routeCreate'      => route('core-groups.create'),
            'routePdf'         => route('core-groups.pdf'),
            'routePrint'       => route('core-groups.print'),
            'routeExcel'       => route('core-groups.excel'),
            'routeCsv'         => route('core-groups.csv'),
            'routeJson'        => route('core-groups.json'),
            'routeImportExcel' => route('core-groups.import-excel'),
            'excelExampleLink' => route('core-groups.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        $bidang_usaha = $this->getDropdownOptions('bidang_usaha.json');

        return view('stisla.core-groups.form', [
            'bidang_usaha' => $bidang_usaha,
            'title'         => __('Groups'),
            'fullTitle'     => __('Tambah Groups'),
            'routeIndex'    => route('core-groups.index'),
            'action'        => route('core-groups.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param CoreGroupRequest $request
     * @return Response
     */
    public function store(CoreGroupRequest $request)
    {
        $data = $request->only([
            'name',
            'jenis_badan_usaha',
            'bidang_usaha',
            'owner_name',
            'owner_ktp',
            'owner_npwp',
            'address',
            'pic_name',
            'pic_phone',
            'pic_email',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->coreGroupRepository->create($data);

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

        logCreate("Groups", $result);

        $successMessage = successMessageCreate("Groups");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param CoreGroup $coreGroup
     * @return Response
     */
    public function edit(CoreGroup $coreGroup)
    {
        $filePath = public_path('assets/bidang_usaha.json');
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true); // true means array
        $bidang_usaha = collect($data)->pluck('label', 'value')->toArray();

        return view('stisla.core-groups.form', [
            'bidang_usaha' => $bidang_usaha,
            'd'             => $coreGroup,
            'title'         => __('Groups'),
            'fullTitle'     => __('Ubah Groups'),
            'routeIndex'    => route('core-groups.index'),
            'action'        => route('core-groups.update', [$coreGroup->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param CoreGroupRequest $request
     * @param CoreGroup $coreGroup
     * @return Response
     */
    public function update(CoreGroupRequest $request, CoreGroup $coreGroup)
    {
        $data = $request->only([
            'name',
            'jenis_badan_usaha',
            'badan_usaha',
            'owner_name',
            'owner_ktp',
            'owner_npwp',
            'address',
            'pic_name',
            'pic_phone',
            'pic_email',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->coreGroupRepository->update($data, $coreGroup->id);

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

        logUpdate("Groups", $coreGroup, $newData);

        $successMessage = successMessageUpdate("Groups");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param CoreGroup $coreGroup
     * @return Response
     */
    public function destroy(CoreGroup $coreGroup)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($coreGroup);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($coreGroup);

        $this->coreGroupRepository->delete($coreGroup->id);
        logDelete("Groups", $coreGroup);

        $successMessage = successMessageDelete("Groups");
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

        $data = $this->coreGroupRepository->getLatest();
        return Excel::download(new CoreGroupExport($data), 'core-groups.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new CoreGroupImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Groups");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->coreGroupRepository->getLatest();
        return $this->fileService->downloadJson($data, 'core-groups.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->coreGroupRepository->getLatest();
        return (new CoreGroupExport($data))->download('core-groups.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->coreGroupRepository->getLatest();
        return (new CoreGroupExport($data))->download('core-groups.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->coreGroupRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.core-groups.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('core-groups.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->coreGroupRepository->getLatest();
        return view('stisla.core-groups.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
