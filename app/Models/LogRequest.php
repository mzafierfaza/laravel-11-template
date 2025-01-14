<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $table = 'dashin_log_requests';

    protected $fillable = [
        'uri',
        'query_string',
        'method',
        'request_data',
        'ip',
        'user_agent',
        'user_id',
        'roles',
        'browser',
        'platform',
        'device',
        'is_ajax',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'roles'        => 'array',
        'request_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
