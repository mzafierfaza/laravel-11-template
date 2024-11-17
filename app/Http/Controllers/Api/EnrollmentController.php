<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EnrollmentRequest;
use App\Models\Enrollment;
use App\Repositories\EnrollmentRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class EnrollmentController extends Controller
{
    /**
     * enrollmentRepository
     *
     * @var EnrollmentRepository
     */
    private EnrollmentRepository $enrollmentRepository;

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
        $this->enrollmentRepository      = new EnrollmentRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Enrollments');
        $this->middleware('can:Enrollments Tambah')->only(['create', 'store']);
        $this->middleware('can:Enrollments Ubah')->only(['edit', 'update']);
        $this->middleware('can:Enrollments Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->enrollmentRepository->getPaginate();
        $successMessage = successMessageLoadData("Enrollments");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Enrollment $enrollment
     * @return JsonResponse
     */
    public function show(Enrollment $enrollment)
    {
        $successMessage = successMessageLoadData("Enrollments");
        return response200($enrollment, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param EnrollmentRequest $request
     * @return JsonResponse
     */
    public function store(EnrollmentRequest $request)
    {
        $data = $request->only([
			'user_id',
			'competence_id',
			'enrolled_date',
			'status',
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

        $result = $this->enrollmentRepository->create($data);
        logCreate('Enrollments', $result);

        $successMessage = successMessageCreate("Enrollments");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param EnrollmentRequest $request
     * @param Enrollment $enrollment
     * @return JsonResponse
     */
    public function update(EnrollmentRequest $request, Enrollment $enrollment)
    {
        $data = $request->only([
			'user_id',
			'competence_id',
			'enrolled_date',
			'status',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->enrollmentRepository->update($data, $enrollment->id);

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

        logUpdate('Enrollments', $enrollment, $result);

        $successMessage = successMessageUpdate("Enrollments");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Enrollment $enrollment
     * @return JsonResponse
     */
    public function destroy(Enrollment $enrollment)
    {
        $deletedRow = $this->enrollmentRepository->delete($enrollment->id);

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

        logDelete('Enrollments', $enrollment);

        $successMessage = successMessageDelete("Enrollments");
        return response200($deletedRow, $successMessage);
    }
}
