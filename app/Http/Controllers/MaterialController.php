<?php

namespace App\Http\Controllers;

use App\Exports\MaterialExport;
use App\Http\Requests\MaterialRequest;
use App\Imports\MaterialImport;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;

class MaterialController extends Controller
{
    private MaterialRepository $materialRepository;

    private NotificationRepository $NotificationRepository;

    private UserRepository $UserRepository;

    private FileService $fileService;


    private bool $exportable = false;

    private bool $importable = false;

    public function __construct()
    {
        $this->materialRepository      = new MaterialRepository;
        $this->fileService            = new FileService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Materials');
        $this->middleware('can:Materials Tambah')->only(['create', 'store']);
        $this->middleware('can:Materials Ubah')->only(['edit', 'update']);
        $this->middleware('can:Materials Hapus')->only(['destroy']);
        $this->middleware('can:Materials Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Materials Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.materials.index', [
            'data'             => $this->materialRepository->getLatest(),
            'canCreate'        => $user->can('Materials Tambah'),
            'canUpdate'        => $user->can('Materials Ubah'),
            'canDelete'        => $user->can('Materials Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Materials'),
            'routeCreate'      => route('materials.create'),
            'routePdf'         => route('materials.pdf'),
            'routePrint'       => route('materials.print'),
            'routeExcel'       => route('materials.excel'),
            'routeCsv'         => route('materials.csv'),
            'routeJson'        => route('materials.json'),
            'routeImportExcel' => route('materials.import-excel'),
            'excelExampleLink' => route('materials.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        return view('stisla.materials.form', [
            'title'         => __('Materials'),
            'fullTitle'     => __('Tambah Materials'),
            'routeIndex'    => route('materials.index'),
            'action'        => route('materials.store')
        ]);
    }

    /**
     * save new data to db
     *
     * @param MaterialRequest $request
     * @return Response
     */
    public function store(MaterialRequest $request)
    {
        $data = $request->only([
            'module_id',
            'title',
            'content',
            'file_path',
            'duration_minutes',
            'type',
            'order',
            'is_progress',
            'deleted_at',
        ]);

        // gunakan jika ada file
        if ($request->hasFile('file_path')) {
            $file = $request->file('file_path');
            $upload = $this->fileService->uploadMinio($file, 'materials/documents/');
            if ($upload) {
                $res = $upload->getData();
                $data['file_path'] = $res->url;
            }
        }

        $result = $this->materialRepository->create($data);

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

        logCreate("Materials", $result);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'success menambahkan Materi',
            ]);
        }

        $successMessage = successMessageCreate("Materials");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * showing edit page
     *
     * @param Material $material
     * @return Response
     */
    public function edit(Material $material)
    {
        return view('stisla.materials.form', [
            'd'             => $material,
            'title'         => __('Materials'),
            'fullTitle'     => __('Ubah Materials'),
            'routeIndex'    => route('materials.index'),
            'action'        => route('materials.update', [$material->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param MaterialRequest $request
     * @param Material $material
     * @return Response
     */
    public function update(MaterialRequest $request, Material $material)
    {
        $data = $request->only([
            'module_id',
            'title',
            'content',
            'file_path',
            'duration_minutes',
            'type',
            'order',
            'is_progress',
            'deleted_at',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->materialRepository->update($data, $material->id);

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

        logUpdate("Materials", $material, $newData);
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'success update Materi',
            ]);
        }

        $successMessage = successMessageUpdate("Materials");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Material $material
     * @return Response
     */
    public function destroy(Material $material)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($material);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($material);

        $this->materialRepository->delete($material->id);
        logDelete("Materials", $material);

        $successMessage = successMessageDelete("Materials");
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

        $data = $this->materialRepository->getLatest();
        return Excel::download(new MaterialExport($data), 'materials.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new MaterialImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Materials");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->materialRepository->getLatest();
        return $this->fileService->downloadJson($data, 'materials.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->materialRepository->getLatest();
        return (new MaterialExport($data))->download('materials.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->materialRepository->getLatest();
        return (new MaterialExport($data))->download('materials.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->materialRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.materials.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('materials.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->materialRepository->getLatest();
        return view('stisla.materials.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
