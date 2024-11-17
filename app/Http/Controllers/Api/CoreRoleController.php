<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CoreRoleRequest;
use App\Models\CoreRole;
use App\Repositories\CoreRoleRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

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
        $this->coreRoleRepository      = new CoreRoleRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Roles');
        $this->middleware('can:Roles Tambah')->only(['create', 'store']);
        $this->middleware('can:Roles Ubah')->only(['edit', 'update']);
        $this->middleware('can:Roles Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->coreRoleRepository->getPaginate();
        $successMessage = successMessageLoadData("Roles");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param CoreRole $coreRole
     * @return JsonResponse
     */
    public function show(CoreRole $coreRole)
    {
        $successMessage = successMessageLoadData("Roles");
        return response200($coreRole, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param CoreRoleRequest $request
     * @return JsonResponse
     */
    public function store(CoreRoleRequest $request)
    {
        $data = $request->only([
			'name',
			'group_id',
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

        $result = $this->coreRoleRepository->create($data);
        logCreate('Roles', $result);

        $successMessage = successMessageCreate("Roles");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param CoreRoleRequest $request
     * @param CoreRole $coreRole
     * @return JsonResponse
     */
    public function update(CoreRoleRequest $request, CoreRole $coreRole)
    {
        $data = $request->only([
			'name',
			'group_id',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->coreRoleRepository->update($data, $coreRole->id);

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

        logUpdate('Roles', $coreRole, $result);

        $successMessage = successMessageUpdate("Roles");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param CoreRole $coreRole
     * @return JsonResponse
     */
    public function destroy(CoreRole $coreRole)
    {
        $deletedRow = $this->coreRoleRepository->delete($coreRole->id);

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

        logDelete('Roles', $coreRole);

        $successMessage = successMessageDelete("Roles");
        return response200($deletedRow, $successMessage);
    }
}
