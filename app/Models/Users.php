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

  public function role()
  {
    return $this->belongsTo(CoreRole::class, 'role_id', 'id');
  }

  public function getName()
  {
    return $this->first_name . ' ' . $this->last_name;
  }

  protected $casts = [];

  public $timestamps = true;
  const TYPES = [];

  protected $with = [];
}
