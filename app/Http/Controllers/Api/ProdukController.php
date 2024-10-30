<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ProdukRequest;
use App\Models\Produk;
use App\Repositories\ProdukRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

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

        $this->middleware('can:Produk');
        $this->middleware('can:Produk Tambah')->only(['create', 'store']);
        $this->middleware('can:Produk Ubah')->only(['edit', 'update']);
        $this->middleware('can:Produk Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->produkRepository->getPaginate();
        $successMessage = successMessageLoadData("Produk");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Produk $produk
     * @return JsonResponse
     */
    public function show(Produk $produk)
    {
        $successMessage = successMessageLoadData("Produk");
        return response200($produk, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param ProdukRequest $request
     * @return JsonResponse
     */
    public function store(ProdukRequest $request)
    {
        $data = $request->only([
			'name',
			'umur',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // bisa digunakan jika ingim kirim email dan ganti methodnya
        // $this->emailService->methodName($params);

        $result = $this->produkRepository->create($data);
        logCreate('Produk', $result);

        $successMessage = successMessageCreate("Produk");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param ProdukRequest $request
     * @param Produk $produk
     * @return JsonResponse
     */
    public function update(ProdukRequest $request, Produk $produk)
    {
        $data = $request->only([
			'name',
			'umur',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->produkRepository->update($data, $produk->id);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // bisa digunakan jika ingim kirim email dan ganti methodnya
        // $this->emailService->methodName($params);

        logUpdate('Produk', $produk, $result);

        $successMessage = successMessageUpdate("Produk");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Produk $produk
     * @return JsonResponse
     */
    public function destroy(Produk $produk)
    {
        $deletedRow = $this->produkRepository->delete($produk->id);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // bisa digunakan jika ingim kirim email dan ganti methodnya
        // $this->emailService->methodName($params);

        logDelete('Produk', $produk);

        $successMessage = successMessageDelete("Produk");
        return response200($deletedRow, $successMessage);
    }
}
