<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompetenceCourseRequest;
use App\Models\CompetenceCourse;
use App\Repositories\CompetenceCourseRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class CompetenceCourseController extends Controller
{
    /**
     * competenceCourseRepository
     *
     * @var CompetenceCourseRepository
     */
    private CompetenceCourseRepository $competenceCourseRepository;

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
        $this->competenceCourseRepository      = new CompetenceCourseRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Competence Courses');
        $this->middleware('can:Competence Courses Tambah')->only(['create', 'store']);
        $this->middleware('can:Competence Courses Ubah')->only(['edit', 'update']);
        $this->middleware('can:Competence Courses Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->competenceCourseRepository->getPaginate();
        $successMessage = successMessageLoadData("Competence Courses");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param CompetenceCourse $competenceCourse
     * @return JsonResponse
     */
    public function show(CompetenceCourse $competenceCourse)
    {
        $successMessage = successMessageLoadData("Competence Courses");
        return response200($competenceCourse, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param CompetenceCourseRequest $request
     * @return JsonResponse
     */
    public function store(CompetenceCourseRequest $request)
    {
        $data = $request->only([
			'competence_id',
			'course_id',
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

        $result = $this->competenceCourseRepository->create($data);
        logCreate('Competence Courses', $result);

        $successMessage = successMessageCreate("Competence Courses");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param CompetenceCourseRequest $request
     * @param CompetenceCourse $competenceCourse
     * @return JsonResponse
     */
    public function update(CompetenceCourseRequest $request, CompetenceCourse $competenceCourse)
    {
        $data = $request->only([
			'competence_id',
			'course_id',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->competenceCourseRepository->update($data, $competenceCourse->id);

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

        logUpdate('Competence Courses', $competenceCourse, $result);

        $successMessage = successMessageUpdate("Competence Courses");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param CompetenceCourse $competenceCourse
     * @return JsonResponse
     */
    public function destroy(CompetenceCourse $competenceCourse)
    {
        $deletedRow = $this->competenceCourseRepository->delete($competenceCourse->id);

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

        logDelete('Competence Courses', $competenceCourse);

        $successMessage = successMessageDelete("Competence Courses");
        return response200($deletedRow, $successMessage);
    }
}
