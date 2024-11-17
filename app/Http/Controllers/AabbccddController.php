<?php

namespace App\Http\Controllers;

use App\Exports\AabbccddExport;
use App\Http\Requests\AabbccddRequest;
use App\Imports\AabbccddImport;
use App\Models\Aabbccdd;
use App\Repositories\AabbccddRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class AabbccddController extends Controller
{
    /**
     * aabbccddRepository
     *
     * @var AabbccddRepository
     */
    private AabbccddRepository $aabbccddRepository;

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
        $this->aabbccddRepository      = new AabbccddRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:AABBCCDD');
        $this->middleware('can:AABBCCDD Tambah')->only(['create', 'store']);
        $this->middleware('can:AABBCCDD Ubah')->only(['edit', 'update']);
        $this->middleware('can:AABBCCDD Hapus')->only(['destroy']);
        $this->middleware('can:AABBCCDD Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:AABBCCDD Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.aabbccdds.index', [
            'data'             => $this->aabbccddRepository->getLatest(),
            'canCreate'        => $user->can('AABBCCDD Tambah'),
            'canUpdate'        => $user->can('AABBCCDD Ubah'),
            'canDelete'        => $user->can('AABBCCDD Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('AABBCCDD'),
            'routeCreate'      => route('aabbccdds.create'),
            'routePdf'         => route('aabbccdds.pdf'),
            'routePrint'       => route('aabbccdds.print'),
            'routeExcel'       => route('aabbccdds.excel'),
            'routeCsv'         => route('aabbccdds.csv'),
            'routeJson'        => route('aabbccdds.json'),
            'routeImportExcel' => route('aabbccdds.import-excel'),
            'excelExampleLink' => route('aabbccdds.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.aabbccdds.form', [
            'title'         => __('AABBCCDD'),
            'fullTitle'     => __('Tambah AABBCCDD'),
            'routeIndex'    => route('aabbccdds.index'),
            'action'        => route('aabbccdds.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param AabbccddRequest $request
     * @return Response
     */
    public function store(AabbccddRequest $request)
    {
        $data = $request->only([
			'name',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->aabbccddRepository->create($data);

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

        logCreate("AABBCCDD", $result);

        $successMessage = successMessageCreate("AABBCCDD");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Aabbccdd $aabbccdd
     * @return Response
     */
    public function edit(Aabbccdd $aabbccdd)
    {
        return view('stisla.aabbccdds.form', [
            'd'             => $aabbccdd,
            'title'         => __('AABBCCDD'),
            'fullTitle'     => __('Ubah AABBCCDD'),
            'routeIndex'    => route('aabbccdds.index'),
            'action'        => route('aabbccdds.update', [$aabbccdd->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param AabbccddRequest $request
     * @param Aabbccdd $aabbccdd
     * @return Response
     */
    public function update(AabbccddRequest $request, Aabbccdd $aabbccdd)
    {
        $data = $request->only([
			'name',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->aabbccddRepository->update($data, $aabbccdd->id);

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

        logUpdate("AABBCCDD", $aabbccdd, $newData);

        $successMessage = successMessageUpdate("AABBCCDD");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Aabbccdd $aabbccdd
     * @return Response
     */
    public function destroy(Aabbccdd $aabbccdd)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($aabbccdd);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($aabbccdd);

        $this->aabbccddRepository->delete($aabbccdd->id);
        logDelete("AABBCCDD", $aabbccdd);

        $successMessage = successMessageDelete("AABBCCDD");
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

        $data = $this->aabbccddRepository->getLatest();
        return Excel::download(new AabbccddExport($data), 'aabbccdds.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new AabbccddImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("AABBCCDD");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->aabbccddRepository->getLatest();
        return $this->fileService->downloadJson($data, 'aabbccdds.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->aabbccddRepository->getLatest();
        return (new AabbccddExport($data))->download('aabbccdds.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->aabbccddRepository->getLatest();
        return (new AabbccddExport($data))->download('aabbccdds.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->aabbccddRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.aabbccdds.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('aabbccdds.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->aabbccddRepository->getLatest();
        return view('stisla.aabbccdds.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
