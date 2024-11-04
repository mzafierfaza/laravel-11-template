<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UsersRequest;
use App\Models\Users;
use App\Repositories\UsersRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class UsersController extends Controller
{
    /**
     * usersRepository
     *
     * @var UsersRepository
     */
    private UsersRepository $usersRepository;

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
        $this->usersRepository      = new UsersRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Users');
        $this->middleware('can:Users Tambah')->only(['create', 'store']);
        $this->middleware('can:Users Ubah')->only(['edit', 'update']);
        $this->middleware('can:Users Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->usersRepository->getPaginate();
        $successMessage = successMessageLoadData("Users");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Users $users
     * @return JsonResponse
     */
    public function show(Users $users)
    {
        $successMessage = successMessageLoadData("Users");
        return response200($users, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param UsersRequest $request
     * @return JsonResponse
     */
    public function store(UsersRequest $request)
    {
        $data = $request->only([
			'firstname',
			'lastname',
			'email',
			'gender',
			'ktp',
			'npwp',
			'picture',
			'date_of_birth',
			'region',
			'phone',
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

        $result = $this->usersRepository->create($data);
        logCreate('Users', $result);

        $successMessage = successMessageCreate("Users");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param UsersRequest $request
     * @param Users $users
     * @return JsonResponse
     */
    public function update(UsersRequest $request, Users $users)
    {
        $data = $request->only([
			'firstname',
			'lastname',
			'email',
			'gender',
			'ktp',
			'npwp',
			'picture',
			'date_of_birth',
			'region',
			'phone',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->usersRepository->update($data, $users->id);

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

        logUpdate('Users', $users, $result);

        $successMessage = successMessageUpdate("Users");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Users $users
     * @return JsonResponse
     */
    public function destroy(Users $users)
    {
        $deletedRow = $this->usersRepository->delete($users->id);

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

        logDelete('Users', $users);

        $successMessage = successMessageDelete("Users");
        return response200($deletedRow, $successMessage);
    }
}
