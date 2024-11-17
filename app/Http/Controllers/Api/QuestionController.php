<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionRequest;
use App\Models\Question;
use App\Repositories\QuestionRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class QuestionController extends Controller
{
    /**
     * questionRepository
     *
     * @var QuestionRepository
     */
    private QuestionRepository $questionRepository;

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
        $this->questionRepository      = new QuestionRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Questions');
        $this->middleware('can:Questions Tambah')->only(['create', 'store']);
        $this->middleware('can:Questions Ubah')->only(['edit', 'update']);
        $this->middleware('can:Questions Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->questionRepository->getPaginate();
        $successMessage = successMessageLoadData("Questions");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function show(Question $question)
    {
        $successMessage = successMessageLoadData("Questions");
        return response200($question, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param QuestionRequest $request
     * @return JsonResponse
     */
    public function store(QuestionRequest $request)
    {
        $data = $request->only([
			'quiz_id',
			'question_text',
			'type',
			'points',
			'correct_essay_answer',
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

        $result = $this->questionRepository->create($data);
        logCreate('Questions', $result);

        $successMessage = successMessageCreate("Questions");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param QuestionRequest $request
     * @param Question $question
     * @return JsonResponse
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $data = $request->only([
			'quiz_id',
			'question_text',
			'type',
			'points',
			'correct_essay_answer',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->questionRepository->update($data, $question->id);

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

        logUpdate('Questions', $question, $result);

        $successMessage = successMessageUpdate("Questions");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Question $question
     * @return JsonResponse
     */
    public function destroy(Question $question)
    {
        $deletedRow = $this->questionRepository->delete($question->id);

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

        logDelete('Questions', $question);

        $successMessage = successMessageDelete("Questions");
        return response200($deletedRow, $successMessage);
    }
}
