<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Helpers\Helper;
use App\Helpers\StringHelper;
use App\Http\Requests\UsersRequest;
use App\Imports\UsersImport;
use App\Models\Users;
use App\Repositories\CoreRoleRepository;
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


    private UsersRepository $usersRepository;
    private CoreRoleRepository $coreRoleRepository;
    private NotificationRepository $NotificationRepository;
    private UserRepository $UserRepository;
    private RegisterLogRepository $registerLogRepository;
    private FileService $fileService;
    private EmailService $emailService;

    private bool $exportable = true;


    private bool $importable = false;

    public function __construct()
    {
        $this->usersRepository      = new UsersRepository;
        $this->coreRoleRepository = new CoreRoleRepository;
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
    public function index()
    {
        // dd($this->exportable);
        $user = auth()->user();
        // dd($user->canApprove());
        return view('stisla.users.index', [
            'data'             => $this->usersRepository->getLatest(),
            'canApprove'       => $user->canApprove(),
            'canBypass'       => $user->canBypass(),
            'canCreate'        => $user->can('Users Tambah'),
            'canUpdate'        => $user->can('Users Ubah'),
            'canDelete'        => $user->can('Users Hapus'),
            'canImportExcel'   => $user->can('Users Impor Excel') && $this->importable,
            'canExport'        => $user->can('Users Ekspor') && $this->exportable,
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
    public function create()
    {
        $roles = $this->coreRoleRepository->query()->with('group')->get()->map(function ($role) {
            // dd($role);
            $groupName = $role->group ? $role->group->name : '-';

            return [
                'id' => $role->id,
                'name' => $role->name . ' (' . $groupName . ')'
            ];
        })
            ->pluck('name', 'id')->toArray();

        // $roles = $this->coreRoleRepository->with('coreGroup')->all()->pluck('name', 'id')->toArray();
        // $filePath = public_path('assets/region.json');
        // $jsonContent = file_get_contents($filePath);
        // $data = json_decode($jsonContent, true); // true means array
        // $regions = collect($data)->pluck('alt_name', 'name')->toArray();
        $regions = $this->getDropdownOptions('region.json');


        return view('stisla.users.form', [
            'roles' => $roles,
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
        //
        // dd($request->region);
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'gender',
            'role_id',
            'ktp',
            'nik',
            'npwp',
            'date_of_birth',
            'region',
            'phone',
            'religion',
            'created_by',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }
        $data["created_by"] = auth()->user()->id;
        $data["approved_status"] = 0;

        $users = $this->usersRepository->create($data);

        // use this if you want to create notification data
        // $title = 'Notify Title';
        // $content = 'lorem ipsum dolor sit amet';
        // $userId = 2;
        // $notificationType = 'transaksi masuk';
        // $icon = 'bell'; // font awesome
        // $bgColor = 'primary'; // primary, danger, success, warning
        // $this->NotificationRepository->createNotif($title,  $content, $userId,  $notificationType, $icon, $bgColor);

        // $request->merge(['new' => true]);
        // $this->sendActivation($users, $request);
        logCreate("Users", $users);


        $successMessage = successMessageCreate("Peserta, \n Segera Approve user terlebih dahulu");
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
        $sent = $this->emailService->sendConfirmPassword($users->email, $url);
        // dd($sent);
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
        $roles = $this->coreRoleRepository->query()->with('group')->get()->map(function ($role) {
            // dd($role);
            $groupName = $role->group ? $role->group->name : '-';
            return [
                'id' => $role->id,
                'name' => $role->name . ' (' . $groupName . ')'
            ];
        })->pluck('name', 'id')->toArray();

        $filePath = public_path('assets/dropdown/region.json');
        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true); // true means array
        $regions = collect($data)->pluck('alt_name', 'name')->toArray();
        $notyet = $users->verification_password_at == null ? true : false;

        return view('stisla.users.form', [
            'd'             => $users,
            'roles'         => $roles,
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

    public function approve(Users $users, Request $request)
    {
        $old = $users;
        $users->approved_at = now();
        $users->approved_status = $request->get('approved_status');
        $users->approved_desc = $request->get('approved_desc');
        $users->approved_by = auth()->user()->id;
        $users->save();
        $successMessage = "Berhasil reject peserta";

        if ($request->get('approved_status') == 1) {
            $successMessage = "Berhasil approve user ";
        }

        if ($request->get('approved_status') == 1 && $users->verification_password_at == null) {
            $request->merge(['new' => true]);
            $this->sendActivation($users, $request);
            $successMessage = "Berhasil approve user \n Email ke peserta telah dikirim";
        }

        logUpdate("Approve Users", $old, $users);
        return redirect()->back()->with('successMessage', $successMessage);
    }
    // public function reject(Users $users)
    // {
    //     $users->approved_at = now();
    //     $users->approved_status = 2;
    //     $users->save();
    // }

    public function update(UsersRequest $request, Users $users)
    {
        $data = $request->only([
            'first_name',
            'last_name',
            'email',
            'gender',
            'ktp',
            'nik',
            'npwp',
            'date_of_birth',
            'role_id',
            'region',
            'phone',
            'religion',
            'created_by',
        ]);

        // gunakan jika ada file
        // if ($request->hasFile('file')) {
        //     $data['file'] = $this->fileService->methodName($request->file('file'));
        // }
        $data["approved_status"] = 0;

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

        logUpdate("Update Perserta", $users, $newData);

        $successMessage = successMessageUpdate("Peserta");
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
