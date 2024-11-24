<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuizRequest;
use App\Models\Quiz;
use App\Repositories\QuizRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class QuizController extends Controller
{
    /**
     * quizRepository
     *
     * @var QuizRepository
     */
    private QuizRepository $quizRepository;

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
        $this->quizRepository      = new QuizRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Quizzes');
        $this->middleware('can:Quizzes Tambah')->only(['create', 'store']);
        $this->middleware('can:Quizzes Ubah')->only(['edit', 'update']);
        $this->middleware('can:Quizzes Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->quizRepository->getPaginate();
        $successMessage = successMessageLoadData("Quizzes");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function show(Quiz $quiz)
    {
        $successMessage = successMessageLoadData("Quizzes");
        return response200($quiz, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param QuizRequest $request
     * @return JsonResponse
     */
    public function store(QuizRequest $request)
    {
        $data = $request->only([
			'module_id',
			'title',
			'description',
			'duration_minutes',
			'passing_score',
			'start_time',
			'end_time',
			'is_randomize',
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

        $result = $this->quizRepository->create($data);
        logCreate('Quizzes', $result);

        $successMessage = successMessageCreate("Quizzes");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param QuizRequest $request
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function update(QuizRequest $request, Quiz $quiz)
    {
        $data = $request->only([
			'module_id',
			'title',
			'description',
			'duration_minutes',
			'passing_score',
			'start_time',
			'end_time',
			'is_randomize',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->quizRepository->update($data, $quiz->id);

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

        logUpdate('Quizzes', $quiz, $result);

        $successMessage = successMessageUpdate("Quizzes");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Quiz $quiz
     * @return JsonResponse
     */
    public function destroy(Quiz $quiz)
    {
        $deletedRow = $this->quizRepository->delete($quiz->id);

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

        logDelete('Quizzes', $quiz);

        $successMessage = successMessageDelete("Quizzes");
        return response200($deletedRow, $successMessage);
    }
}
