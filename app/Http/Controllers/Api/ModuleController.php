<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ModuleRequest;
use App\Models\Module;
use App\Repositories\ModuleRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class ModuleController extends Controller
{
    /**
     * moduleRepository
     *
     * @var ModuleRepository
     */
    private ModuleRepository $moduleRepository;

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
        $this->moduleRepository      = new ModuleRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Modules');
        $this->middleware('can:Modules Tambah')->only(['create', 'store']);
        $this->middleware('can:Modules Ubah')->only(['edit', 'update']);
        $this->middleware('can:Modules Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->moduleRepository->getPaginate();
        $successMessage = successMessageLoadData("Modules");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Module $module
     * @return JsonResponse
     */
    public function show(Module $module)
    {
        $successMessage = successMessageLoadData("Modules");
        return response200($module, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param ModuleRequest $request
     * @return JsonResponse
     */
    public function store(ModuleRequest $request)
    {
        $data = $request->only([
			'course_id',
			'title',
			'description',
			'order',
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

        $result = $this->moduleRepository->create($data);
        logCreate('Modules', $result);

        $successMessage = successMessageCreate("Modules");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param ModuleRequest $request
     * @param Module $module
     * @return JsonResponse
     */
    public function update(ModuleRequest $request, Module $module)
    {
        $data = $request->only([
			'course_id',
			'title',
			'description',
			'order',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->moduleRepository->update($data, $module->id);

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

        logUpdate('Modules', $module, $result);

        $successMessage = successMessageUpdate("Modules");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Module $module
     * @return JsonResponse
     */
    public function destroy(Module $module)
    {
        $deletedRow = $this->moduleRepository->delete($module->id);

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

        logDelete('Modules', $module);

        $successMessage = successMessageDelete("Modules");
        return response200($deletedRow, $successMessage);
    }
}
