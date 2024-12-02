<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
  use HasFactory;

  protected $table = 'enrollments';

  protected $fillable = [
    'user_id',
    'competence_id',
    'enrolled_date',
    'enrollment_status', // enrolled, active, droped, completed
    'deleted_at',
    'graduate_date',
    'certificate',
  ];
  protected $casts = [];
  public $timestamps = true;

  const TYPES = [];

  protected $with = [];

  public function user()
  {
    return $this->belongsTo(Users::class);
  }
}
