<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CoreGroupRequest;
use App\Models\CoreGroup;
use App\Repositories\CoreGroupRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

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
        $this->coreGroupRepository      = new CoreGroupRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Groups');
        $this->middleware('can:Groups Tambah')->only(['create', 'store']);
        $this->middleware('can:Groups Ubah')->only(['edit', 'update']);
        $this->middleware('can:Groups Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->coreGroupRepository->getPaginate();
        $successMessage = successMessageLoadData("Groups");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param CoreGroup $coreGroup
     * @return JsonResponse
     */
    public function show(CoreGroup $coreGroup)
    {
        $successMessage = successMessageLoadData("Groups");
        return response200($coreGroup, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param CoreGroupRequest $request
     * @return JsonResponse
     */
    public function store(CoreGroupRequest $request)
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

        $result = $this->coreGroupRepository->create($data);
        logCreate('Groups', $result);

        $successMessage = successMessageCreate("Groups");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param CoreGroupRequest $request
     * @param CoreGroup $coreGroup
     * @return JsonResponse
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

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->coreGroupRepository->update($data, $coreGroup->id);

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

        logUpdate('Groups', $coreGroup, $result);

        $successMessage = successMessageUpdate("Groups");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param CoreGroup $coreGroup
     * @return JsonResponse
     */
    public function destroy(CoreGroup $coreGroup)
    {
        $deletedRow = $this->coreGroupRepository->delete($coreGroup->id);

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

        logDelete('Groups', $coreGroup);

        $successMessage = successMessageDelete("Groups");
        return response200($deletedRow, $successMessage);
    }
}
