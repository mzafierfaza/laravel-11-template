<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuizAttemptRequest;
use App\Models\QuizAttempt;
use App\Repositories\QuizAttemptRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class QuizAttemptController extends Controller
{
    /**
     * quizAttemptRepository
     *
     * @var QuizAttemptRepository
     */
    private QuizAttemptRepository $quizAttemptRepository;

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
        $this->quizAttemptRepository      = new QuizAttemptRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Quiz Attempts');
        $this->middleware('can:Quiz Attempts Tambah')->only(['create', 'store']);
        $this->middleware('can:Quiz Attempts Ubah')->only(['edit', 'update']);
        $this->middleware('can:Quiz Attempts Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->quizAttemptRepository->getPaginate();
        $successMessage = successMessageLoadData("Quiz Attempts");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param QuizAttempt $quizAttempt
     * @return JsonResponse
     */
    public function show(QuizAttempt $quizAttempt)
    {
        $successMessage = successMessageLoadData("Quiz Attempts");
        return response200($quizAttempt, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param QuizAttemptRequest $request
     * @return JsonResponse
     */
    public function store(QuizAttemptRequest $request)
    {
        $data = $request->only([
			'enrollment_id',
			'quiz_id',
			'start_time',
			'submit_time',
			'score',
			'is_passed',
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

        $result = $this->quizAttemptRepository->create($data);
        logCreate('Quiz Attempts', $result);

        $successMessage = successMessageCreate("Quiz Attempts");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param QuizAttemptRequest $request
     * @param QuizAttempt $quizAttempt
     * @return JsonResponse
     */
    public function update(QuizAttemptRequest $request, QuizAttempt $quizAttempt)
    {
        $data = $request->only([
			'enrollment_id',
			'quiz_id',
			'start_time',
			'submit_time',
			'score',
			'is_passed',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->quizAttemptRepository->update($data, $quizAttempt->id);

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

        logUpdate('Quiz Attempts', $quizAttempt, $result);

        $successMessage = successMessageUpdate("Quiz Attempts");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param QuizAttempt $quizAttempt
     * @return JsonResponse
     */
    public function destroy(QuizAttempt $quizAttempt)
    {
        $deletedRow = $this->quizAttemptRepository->delete($quizAttempt->id);

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

        logDelete('Quiz Attempts', $quizAttempt);

        $successMessage = successMessageDelete("Quiz Attempts");
        return response200($deletedRow, $successMessage);
    }
}
