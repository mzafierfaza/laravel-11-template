<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'courses';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'title',
    'description',
    'procedurs',
    'topic',
    'format',
    'is_random_material',
    'is_premium',
    'price',
    'created_by',
    'is_active',
    'start_date',
    'end_date',
    'start_time',
    'end_time',
    'address',
    'is_repeat_enrollment',
    'max_repeat_enrollment',
    'max_enrollment',
    'is_class_test',
    'is_class_finish',
    'status',
    'approved_status',
    'approved_at',
    'approved_by',
    'teacher_id',
    'teacher_about',
    'image',
    'certificate',
    'certificate_can_download',
  ];


  protected $casts = [];


  public $timestamps = true;
  const TYPES = [];
  protected $with = [];

  public function modules()
  {
    return $this->hasMany(Module::class);
  }
}
