<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\StudentAnswerRequest;
use App\Models\StudentAnswer;
use App\Repositories\StudentAnswerRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class StudentAnswerController extends Controller
{
    /**
     * studentAnswerRepository
     *
     * @var StudentAnswerRepository
     */
    private StudentAnswerRepository $studentAnswerRepository;

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
        $this->studentAnswerRepository      = new StudentAnswerRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Student Answers');
        $this->middleware('can:Student Answers Tambah')->only(['create', 'store']);
        $this->middleware('can:Student Answers Ubah')->only(['edit', 'update']);
        $this->middleware('can:Student Answers Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->studentAnswerRepository->getPaginate();
        $successMessage = successMessageLoadData("Student Answers");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param StudentAnswer $studentAnswer
     * @return JsonResponse
     */
    public function show(StudentAnswer $studentAnswer)
    {
        $successMessage = successMessageLoadData("Student Answers");
        return response200($studentAnswer, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param StudentAnswerRequest $request
     * @return JsonResponse
     */
    public function store(StudentAnswerRequest $request)
    {
        $data = $request->only([
			'quiz_attempt_id',
			'question_id',
			'selected_option_id',
			'essay_answer',
			'score',
			'teacher_comment',
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

        $result = $this->studentAnswerRepository->create($data);
        logCreate('Student Answers', $result);

        $successMessage = successMessageCreate("Student Answers");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param StudentAnswerRequest $request
     * @param StudentAnswer $studentAnswer
     * @return JsonResponse
     */
    public function update(StudentAnswerRequest $request, StudentAnswer $studentAnswer)
    {
        $data = $request->only([
			'quiz_attempt_id',
			'question_id',
			'selected_option_id',
			'essay_answer',
			'score',
			'teacher_comment',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->studentAnswerRepository->update($data, $studentAnswer->id);

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

        logUpdate('Student Answers', $studentAnswer, $result);

        $successMessage = successMessageUpdate("Student Answers");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param StudentAnswer $studentAnswer
     * @return JsonResponse
     */
    public function destroy(StudentAnswer $studentAnswer)
    {
        $deletedRow = $this->studentAnswerRepository->delete($studentAnswer->id);

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

        logDelete('Student Answers', $studentAnswer);

        $successMessage = successMessageDelete("Student Answers");
        return response200($deletedRow, $successMessage);
    }
}
