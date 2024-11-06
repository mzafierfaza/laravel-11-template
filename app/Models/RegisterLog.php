<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterLog extends Model
{
    use HasFactory;

    protected $table = 'register_logs';

    protected $fillable = [
        'user_id',
        'email',
        'session_expired_at',
        'session_id',
        'session_claim_at',
        'session_url',
        'session_email_at',
        'verification_at',
        'ip_client',
        'user_agent',
        'created_at',
        'updated_at',
    ];
}
