<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestionOptionRequest;
use App\Models\QuestionOption;
use App\Repositories\QuestionOptionRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class QuestionOptionController extends Controller
{
    /**
     * questionOptionRepository
     *
     * @var QuestionOptionRepository
     */
    private QuestionOptionRepository $questionOptionRepository;

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
        $this->questionOptionRepository      = new QuestionOptionRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Question Options');
        $this->middleware('can:Question Options Tambah')->only(['create', 'store']);
        $this->middleware('can:Question Options Ubah')->only(['edit', 'update']);
        $this->middleware('can:Question Options Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->questionOptionRepository->getPaginate();
        $successMessage = successMessageLoadData("Question Options");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param QuestionOption $questionOption
     * @return JsonResponse
     */
    public function show(QuestionOption $questionOption)
    {
        $successMessage = successMessageLoadData("Question Options");
        return response200($questionOption, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param QuestionOptionRequest $request
     * @return JsonResponse
     */
    public function store(QuestionOptionRequest $request)
    {
        $data = $request->only([
			'question_id',
			'option_text',
			'is_correct',
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

        $result = $this->questionOptionRepository->create($data);
        logCreate('Question Options', $result);

        $successMessage = successMessageCreate("Question Options");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param QuestionOptionRequest $request
     * @param QuestionOption $questionOption
     * @return JsonResponse
     */
    public function update(QuestionOptionRequest $request, QuestionOption $questionOption)
    {
        $data = $request->only([
			'question_id',
			'option_text',
			'is_correct',
			'deleted_at',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->questionOptionRepository->update($data, $questionOption->id);

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

        logUpdate('Question Options', $questionOption, $result);

        $successMessage = successMessageUpdate("Question Options");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param QuestionOption $questionOption
     * @return JsonResponse
     */
    public function destroy(QuestionOption $questionOption)
    {
        $deletedRow = $this->questionOptionRepository->delete($questionOption->id);

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

        logDelete('Question Options', $questionOption);

        $successMessage = successMessageDelete("Question Options");
        return response200($deletedRow, $successMessage);
    }
}
