<?php

namespace App\Http\Controllers;

use App\Exports\ProdukExport;
use App\Http\Requests\ProdukRequest;
use App\Imports\ProdukImport;
use App\Models\Produk;
use App\Repositories\ProdukRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class ProdukController extends Controller
{
    /**
     * produkRepository
     *
     * @var ProdukRepository
     */
    private ProdukRepository $produkRepository;

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
        $this->produkRepository      = new ProdukRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Produk');
        $this->middleware('can:Produk Tambah')->only(['create', 'store']);
        $this->middleware('can:Produk Ubah')->only(['edit', 'update']);
        $this->middleware('can:Produk Hapus')->only(['destroy']);
        $this->middleware('can:Produk Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Produk Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.produks.index', [
            'data'             => $this->produkRepository->getLatest(),
            'canCreate'        => $user->can('Produk Tambah'),
            'canUpdate'        => $user->can('Produk Ubah'),
            'canDelete'        => $user->can('Produk Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Produk'),
            'routeCreate'      => route('produks.create'),
            'routePdf'         => route('produks.pdf'),
            'routePrint'       => route('produks.print'),
            'routeExcel'       => route('produks.excel'),
            'routeCsv'         => route('produks.csv'),
            'routeJson'        => route('produks.json'),
            'routeImportExcel' => route('produks.import-excel'),
            'excelExampleLink' => route('produks.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.produks.form', [
            'title'         => __('Produk'),
            'fullTitle'     => __('Tambah Produk'),
            'routeIndex'    => route('produks.index'),
            'action'        => route('produks.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param ProdukRequest $request
     * @return Response
     */
    public function store(ProdukRequest $request)
    {
        $data = $request->only([
            'name',
            'umur',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $result = $this->produkRepository->create($data);

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

        logCreate("Produk", $result);

        $successMessage = successMessageCreate("Produk");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Produk $produk
     * @return Response
     */
    public function edit(Produk $produk)
    {
        return view('stisla.produks.form', [
            'd'             => $produk,
            'title'         => __('Produk'),
            'fullTitle'     => __('Ubah Produk'),
            'routeIndex'    => route('produks.index'),
            'action'        => route('produks.update', [$produk->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param ProdukRequest $request
     * @param Produk $produk
     * @return Response
     */
    public function update(ProdukRequest $request, Produk $produk)
    {
        $data = $request->only([
            'name',
            'umur',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->produkRepository->update($data, $produk->id);

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

        logUpdate("Produk", $produk, $newData);

        $successMessage = successMessageUpdate("Produk");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Produk $produk
     * @return Response
     */
    public function destroy(Produk $produk)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($produk);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($produk);

        $this->produkRepository->delete($produk->id);
        logDelete("Produk", $produk);

        $successMessage = successMessageDelete("Produk");
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

        $data = $this->produkRepository->getLatest();
        return Excel::download(new ProdukExport($data), 'produks.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new ProdukImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Produk");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->produkRepository->getLatest();
        return $this->fileService->downloadJson($data, 'produks.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->produkRepository->getLatest();
        return (new ProdukExport($data))->download('produks.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->produkRepository->getLatest();
        return (new ProdukExport($data))->download('produks.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->produkRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.produks.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('produks.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->produkRepository->getLatest();
        return view('stisla.produks.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
