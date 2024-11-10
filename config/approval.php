<?php

return [
    'enabled' => env('APPROVAL_ENABLED', true),

    'model' => [
        'reject_by' => \App\Models\User::class,
        'approve_by' => \App\Models\User::class,
        'approval' => \Afiqiqmal\Approval\Models\Approval::class
    ],

    'tables' => [
        'name' => 'approvals',
        'reject_by' => 'dashin_users',
        'approve_by' => 'dashin_users',
    ],

    'when' => [ // need to approve
        'create' => true,
        'update' => true,
        'delete' => true
    ],

    'events' => [
        'requested' => \Afiqiqmal\Approval\Events\ApprovalRequested::class,
        'approved' => \Afiqiqmal\Approval\Events\Approved::class,
        'rejected' => \Afiqiqmal\Approval\Events\Rejected::class,
    ],
];
