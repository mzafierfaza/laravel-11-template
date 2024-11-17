<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  use HasFactory;

  protected $table = 'core_users';

  protected $fillable = [
    'id',
    'first_name',
    'last_name',
    'email',
    'gender',
    'role_id',
    'ktp',
    'npwp',
    'picture',
    'date_of_birth',
    'region',
    'religion',
    'nik',
    'phone',
    'verification_password_at',
    'approved_at',
    'approved_by',
    'created_by',
    'approved_status',
    'approved_desc',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [];

  /**
   * Indicates if the model should be timestamped.
   *
   * @var bool
   */
  public $timestamps = true;

  /**
   * some columns model type
   *
   * @var array
   */
  const TYPES = [];

  /**
   * Default with relationship
   *
   * @var array
   */
  protected $with = [];
}
