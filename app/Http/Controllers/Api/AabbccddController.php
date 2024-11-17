<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AabbccddRequest;
use App\Models\Aabbccdd;
use App\Repositories\AabbccddRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

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
        $this->aabbccddRepository      = new AabbccddRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:AABBCCDD');
        $this->middleware('can:AABBCCDD Tambah')->only(['create', 'store']);
        $this->middleware('can:AABBCCDD Ubah')->only(['edit', 'update']);
        $this->middleware('can:AABBCCDD Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->aabbccddRepository->getPaginate();
        $successMessage = successMessageLoadData("AABBCCDD");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Aabbccdd $aabbccdd
     * @return JsonResponse
     */
    public function show(Aabbccdd $aabbccdd)
    {
        $successMessage = successMessageLoadData("AABBCCDD");
        return response200($aabbccdd, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param AabbccddRequest $request
     * @return JsonResponse
     */
    public function store(AabbccddRequest $request)
    {
        $data = $request->only([
			'name',
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

        $result = $this->aabbccddRepository->create($data);
        logCreate('AABBCCDD', $result);

        $successMessage = successMessageCreate("AABBCCDD");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param AabbccddRequest $request
     * @param Aabbccdd $aabbccdd
     * @return JsonResponse
     */
    public function update(AabbccddRequest $request, Aabbccdd $aabbccdd)
    {
        $data = $request->only([
			'name',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->aabbccddRepository->update($data, $aabbccdd->id);

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

        logUpdate('AABBCCDD', $aabbccdd, $result);

        $successMessage = successMessageUpdate("AABBCCDD");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Aabbccdd $aabbccdd
     * @return JsonResponse
     */
    public function destroy(Aabbccdd $aabbccdd)
    {
        $deletedRow = $this->aabbccddRepository->delete($aabbccdd->id);

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

        logDelete('AABBCCDD', $aabbccdd);

        $successMessage = successMessageDelete("AABBCCDD");
        return response200($deletedRow, $successMessage);
    }
}
