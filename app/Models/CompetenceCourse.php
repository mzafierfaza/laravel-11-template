<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompetenceCourse extends Model
{
  use HasFactory;

  protected $table = 'competence_courses';

  protected $fillable = [
    'competence_id',
    'course_id',
    'order',
  ];

  protected $casts = [];
  public $timestamps = true;

  const TYPES = [];
  protected $with = [];

  public function course()
  {
    return $this->belongsTo(Course::class);
  }
}
