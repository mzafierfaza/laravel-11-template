<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\MaterialRequest;
use App\Models\Material;
use App\Repositories\MaterialRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class MaterialController extends Controller
{
    /**
     * materialRepository
     *
     * @var MaterialRepository
     */
    private MaterialRepository $materialRepository;

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
        $this->materialRepository      = new MaterialRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Materials');
        $this->middleware('can:Materials Tambah')->only(['create', 'store']);
        $this->middleware('can:Materials Ubah')->only(['edit', 'update']);
        $this->middleware('can:Materials Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->materialRepository->getPaginate();
        $successMessage = successMessageLoadData("Materials");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Material $material
     * @return JsonResponse
     */
    public function show(Material $material)
    {
        $successMessage = successMessageLoadData("Materials");
        return response200($material, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param MaterialRequest $request
     * @return JsonResponse
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

        $result = $this->materialRepository->create($data);
        logCreate('Materials', $result);

        $successMessage = successMessageCreate("Materials");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param MaterialRequest $request
     * @param Material $material
     * @return JsonResponse
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

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->materialRepository->update($data, $material->id);

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

        logUpdate('Materials', $material, $result);

        $successMessage = successMessageUpdate("Materials");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Material $material
     * @return JsonResponse
     */
    public function destroy(Material $material)
    {
        $deletedRow = $this->materialRepository->delete($material->id);

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

        logDelete('Materials', $material);

        $successMessage = successMessageDelete("Materials");
        return response200($deletedRow, $successMessage);
    }
}
