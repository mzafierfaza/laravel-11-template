<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Helpers\Helper;
use App\Helpers\StringHelper;
use App\Http\Requests\UsersRequest;
use App\Imports\UsersImport;
use App\Models\Users;
use App\Repositories\UsersRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\RegisterLogRepository;
use App\Repositories\UserRepository;
use App\Services\EmailService;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;

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
     * UserRepository
     *
     * @var UserRepository
     */
    private UserRepository $UserRepository;

    private RegisterLogRepository $registerLogRepository;

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
     * exportable
     *
     * @var bool
     */
    private bool $exportable = false;

    /**
     * importable
     *
     * @var bool
     */
    private bool $importable = false;

    /**
     * constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->usersRepository      = new UsersRepository;
        $this->registerLogRepository = new RegisterLogRepository;
        $this->fileService            = new FileService;
        $this->emailService           = new EmailService;
        $this->NotificationRepository = new NotificationRepository;
        $this->UserRepository         = new UserRepository;

        $this->middleware('can:Users');
        $this->middleware('can:Users Tambah')->only(['create', 'store']);
        $this->middleware('can:Users Ubah')->only(['edit', 'update']);
        $this->middleware('can:Users Hapus')->only(['destroy']);
        $this->middleware('can:Users Ekspor')->only(['json', 'excel', 'csv', 'pdf']);
        $this->middleware('can:Users Impor Excel')->only(['importExcel', 'importExcelExample']);
    }

    /**
     * showing data page
     *
     * @return Response
     */
    public function index()
    {
        $user = auth()->user();
        return view('stisla.users.index', [
            'data'             => $this->usersRepository->getLatest(),
            'canCreate'        => $user->can('Users Tambah'),
            'canUpdate'        => $user->can('Users Ubah'),
            'canDelete'        => $user->can('Users Hapus'),
            'canImportExcel'   => $user->can('Order Impor Excel') && $this->importable,
            'canExport'        => $user->can('Order Ekspor') && $this->exportable,
            'title'            => __('Peserta'),
            'routeCreate'      => route('users.create'),
            'routePdf'         => route('users.pdf'),
            'routePrint'       => route('users.print'),
            'routeExcel'       => route('users.excel'),
            'routeCsv'         => route('users.csv'),
            'routeJson'        => route('users.json'),
            'routeImportExcel' => route('users.import-excel'),
            'excelExampleLink' => route('users.import-excel-example'),
        ]);
    }

    /**
     * showing add new data form page
     *
     * @return Response
     */
    public function create()
    {
        // dd('create');
        // $d = new Users;
        // dd($d);
        $filePath = public_path('assets/region.json');
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true); // true means array
        $regions = collect($data)->pluck('alt_name', 'name')->toArray();

        return view('stisla.users.form', [
            // 'd' => $d,
            'title'         => __('Peserta'),
            'fullTitle'     => __('Tambah Peserta'),
            'routeIndex'    => route('users.index'),
            'action'        => route('users.store'),
            'regions'       => $regions
        ]);
    }



    /**
     * save new data to db
     *
     * @param UsersRequest $request
     * @return Response
     */
    public function store(UsersRequest $request)
    {
        // dd($request->all());

        // dd($request->region);
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'gender',
            'ktp',
            'npwp',
            'date_of_birth',
            'region',
            'phone',
            'religion',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $users = $this->usersRepository->create($data);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // $now = Carbon::now();
        // $session_id = StringHelper::generateRandomString(length: 16);
        // $url = config('app.url_lms') . '/confirm-password?session_id=' . $session_id;

        // $registerLog = [
        //     'user_id' => $users->id,
        //     'email' => $users->email,
        //     'session_id' => $session_id,
        //     'session_expired_at' => $now->addMinutes(value: 30),
        //     'session_url' => $url,
        // ];
        // $usersSession = $this->registerLogRepository->create($registerLog);
        // $this->emailService->sendConfirmPassword($users->email, $url);
        $request->merge(['new' => true]);
        $this->sendActivation($users, $request);


        logCreate("Users", $users);


        $successMessage = successMessageCreate("Peserta, \n Mohon Cek Email Peserta Untuk Mengaktifkan Akun");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    public function sendActivation(Users $users, Request $request)
    {

        $new = $request->query('new', true);  // mengambil parameter new dari query string

        $now = Carbon::now();
        $session_id = StringHelper::generateRandomString(length: 16);
        $url = config('app.url_lms') . '/confirm-password?session_id=' . $session_id;

        $registerLog = [
            'user_id' => $users->id,
            'email' => $users->email,
            'session_id' => $session_id,
            'session_expired_at' => $now->addMinutes(value: 30),
            'session_url' => $url,
        ];

        $resultSession = $this->registerLogRepository->create($registerLog);
        // dd($resultSession);
        $this->emailService->sendConfirmPassword($users->email, $url);

        // dd($new);
        if ($new) {
            logCreate("Create Activation", $users);
            $successMessage = successMessageCreate("Email telah dikirim");
        } else {
            logCreate("Resend Activation", $users);
            $successMessage = successMessageUpdate("Email telah dikirim");
            return redirect()->back()->with('successMessage', $successMessage);
        }
    }

    /**
     * showing edit page
     *
     * @param Users $users
     * @return Response
     */
    public function edit(Users $users)
    {

        $filePath = public_path('assets/region.json');
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true); // true means array
        $regions = collect($data)->pluck('alt_name', 'name')->toArray();
        $notyet = $users->verification_password_at == null ? true : false;

        return view('stisla.users.form', [
            'd'             => $users,
            'notyet'        => $notyet,
            'regions' => $regions,
            'title'         => __('Users'),
            'fullTitle'     => __('Ubah Users'),
            'routeIndex'    => route('users.index'),
            'action'        => route('users.update', [$users->id])
        ]);
    }

    /**
     * update data to db
     *
     * @param UsersRequest $request
     * @param Users $users
     * @return Response
     */
    public function update(UsersRequest $request, Users $users)
    {
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'gender',
            'ktp',
            'npwp',
            'picture',
            'date_of_birth',
            'region',
            'phone',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }

        $newData = $this->usersRepository->update($data, $users->id);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($newData);

        logUpdate("Users", $users, $newData);

        $successMessage = successMessageUpdate("Users");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * delete user from db
     *
     * @param Users $users
     * @return Response
     */
    public function destroy(Users $users)
    {
        // delete file from storage if exists
        // $this->fileService->methodName($users);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // gunakan jika mau kirim email
        // $this->emailService->methodName($users);

        $this->usersRepository->delete($users->id);
        logDelete("Users", $users);

        $successMessage = successMessageDelete("Users");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download import example
     *
     * @return BinaryFileResponse
     */
    public function importExcelExample(): BinaryFileResponse
    {
        // bisa gunakan file excel langsung sebagai contoh
        // $filepath = public_path('example.xlsx');
        // return response()->download($filepath);

        $data = $this->usersRepository->getLatest();
        return Excel::download(new UsersExport($data), 'users.xlsx');
    }

    /**
     * import excel file to db
     *
     * @param \App\Http\Requests\ImportExcelRequest $request
     * @return Response
     */
    public function importExcel(\App\Http\Requests\ImportExcelRequest $request)
    {
        Excel::import(new UsersImport, $request->file('import_file'));
        $successMessage = successMessageImportExcel("Users");
        return redirect()->back()->with('successMessage', $successMessage);
    }

    /**
     * download export data as json
     *
     * @return Response
     */
    public function json()
    {
        $data = $this->usersRepository->getLatest();
        return $this->fileService->downloadJson($data, 'users.json');
    }

    /**
     * download export data as xlsx
     *
     * @return Response
     */
    public function excel()
    {
        $data = $this->usersRepository->getLatest();
        return (new UsersExport($data))->download('users.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

    /**
     * download export data as csv
     *
     * @return Response
     */
    public function csv()
    {
        $data = $this->usersRepository->getLatest();
        return (new UsersExport($data))->download('users.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    /**
     * download export data as pdf
     *
     * @return Response
     */
    public function pdf()
    {
        $data = $this->usersRepository->getLatest();
        return PDF::setPaper('Letter', 'landscape')
            ->loadView('stisla.users.export-pdf', [
                'data'    => $data,
                'isPrint' => false
            ])
            ->download('users.pdf');
    }

    /**
     * export data to print html
     *
     * @return Response
     */
    public function exportPrint()
    {
        $data = $this->usersRepository->getLatest();
        return view('stisla.users.export-pdf', [
            'data'    => $data,
            'isPrint' => true
        ]);
    }
}
