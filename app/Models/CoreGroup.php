<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreGroup extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'core_groups';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name',
    'jenis_badan_usaha',
    'bidang_usaha',
    'owner_name',
    'owner_ktp',
    'owner_npwp',
    'address',
    'pic_name',
    'pic_phone',
    'pic_email',
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
