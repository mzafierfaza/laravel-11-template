<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'core_users';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'gender',
    'ktp',
    'npwp',
    'picture',
    'date_of_birth',
    'region',
    'religion',
    'nik',
    'phone',
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
