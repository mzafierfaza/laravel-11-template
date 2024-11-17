<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CompetenceRequest;
use App\Models\Competence;
use App\Repositories\CompetenceRepository;
use App\Repositories\NotificationRepository;
use Illuminate\Http\JsonResponse;
use App\Services\EmailService;
use App\Services\FileService;

class CompetenceController extends Controller
{
    /**
     * competenceRepository
     *
     * @var CompetenceRepository
     */
    private CompetenceRepository $competenceRepository;

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
        $this->competenceRepository      = new CompetenceRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;

        $this->middleware('can:Competences');
        $this->middleware('can:Competences Tambah')->only(['create', 'store']);
        $this->middleware('can:Competences Ubah')->only(['edit', 'update']);
        $this->middleware('can:Competences Hapus')->only(['destroy']);
    }

    /**
     * get data as pagination
     *
     * @return JsonResponse
     */
    public function index()
    {
        $data = $this->competenceRepository->getPaginate();
        $successMessage = successMessageLoadData("Competences");
        return response200($data, $successMessage);
    }

    /**
     * get detail data
     *
     * @param Competence $competence
     * @return JsonResponse
     */
    public function show(Competence $competence)
    {
        $successMessage = successMessageLoadData("Competences");
        return response200($competence, $successMessage);
    }

    /**
     * save new data to db
     *
     * @param CompetenceRequest $request
     * @return JsonResponse
     */
    public function store(CompetenceRequest $request)
    {
        $data = $request->only([
			'title',
			'level',
			'certificate',
			'certificate_can_download',
			'image',
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

        $result = $this->competenceRepository->create($data);
        logCreate('Competences', $result);

        $successMessage = successMessageCreate("Competences");
        return response200($result, $successMessage);
    }

    /**
     * update data to db
     *
     * @param CompetenceRequest $request
     * @param Competence $competence
     * @return JsonResponse
     */
    public function update(CompetenceRequest $request, Competence $competence)
    {
        $data = $request->only([
			'title',
			'level',
			'certificate',
			'certificate_can_download',
			'image',
        ]);

        // bisa digunakan jika ada upload file dan ganti methodnya
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->uploadCrudExampleFile($request->file('file'));
        // }

        $result = $this->competenceRepository->update($data, $competence->id);

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

        logUpdate('Competences', $competence, $result);

        $successMessage = successMessageUpdate("Competences");
        return response200($result, $successMessage);
    }

    /**
     * delete data from db
     *
     * @param Competence $competence
     * @return JsonResponse
     */
    public function destroy(Competence $competence)
    {
        $deletedRow = $this->competenceRepository->delete($competence->id);

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

        logDelete('Competences', $competence);

        $successMessage = successMessageDelete("Competences");
        return response200($deletedRow, $successMessage);
    }
}
