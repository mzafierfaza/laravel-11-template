<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CourseRequest;
use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class CourseController extends Controller
{
    /**
     * courseRepository
     *
     * @var CourseRepository
     */
    private CourseRepository $courseRepository;

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
        $this->courseRepository      = new CourseRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Courses');
        $this->middleware('can:Courses Tambah')->only(['create', 'store']);
        $this->middleware('can:Courses Ubah')->only(['edit', 'update']);
        $this->middleware('can:Courses Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->courseRepository->getPaginate();
        $successMessage = successMessageLoadData("Courses");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function show(Course $course)
    {
        $successMessage = successMessageLoadData("Courses");
        return response200($course, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param CourseRequest $request
     * @return JsonResponse
     */
    public function store(CourseRequest $request)
    {
        $data = $request->only([
			'title',
			'description',
			'procedurs',
			'topic',
			'format',
			'is_random_material',
			'is_premium',
			'price',
			'created_by',
			'is_active',
			'start_date',
			'end_date',
			'start_time',
			'end_time',
			'address',
			'is_repeat_enrollment',
			'max_repeat_enrollment',
			'max_enrollment',
			'is_class_test',
			'is_class_finish',
			'status',
			'approved_status',
			'approved_at',
			'approved_by',
			'teacher_id',
			'teacher_about',
			'image',
			'certificate',
			'certificate_can_download',
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

        $result = $this->courseRepository->create($data);
        logCreate('Courses', $result);

        $successMessage = successMessageCreate("Courses");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param CourseRequest $request
     * @param Course $course
     * @return JsonResponse
     */
    public function update(CourseRequest $request, Course $course)
    {
        $data = $request->only([
			'title',
			'description',
			'procedurs',
			'topic',
			'format',
			'is_random_material',
			'is_premium',
			'price',
			'created_by',
			'is_active',
			'start_date',
			'end_date',
			'start_time',
			'end_time',
			'address',
			'is_repeat_enrollment',
			'max_repeat_enrollment',
			'max_enrollment',
			'is_class_test',
			'is_class_finish',
			'status',
			'approved_status',
			'approved_at',
			'approved_by',
			'teacher_id',
			'teacher_about',
			'image',
			'certificate',
			'certificate_can_download',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->courseRepository->update($data, $course->id);

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

        logUpdate('Courses', $course, $result);

        $successMessage = successMessageUpdate("Courses");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Course $course
     * @return JsonResponse
     */
    public function destroy(Course $course)
    {
        $deletedRow = $this->courseRepository->delete($course->id);

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

        logDelete('Courses', $course);

        $successMessage = successMessageDelete("Courses");
        return response200($deletedRow, $successMessage);
    }
}
