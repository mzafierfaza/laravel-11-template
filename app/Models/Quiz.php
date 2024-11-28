<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'quizzes';

  protected $fillable = [
    'module_id',
    'title',
    'type',
    'description',
    'order',
    'duration_minutes',
    'passing_score',
    'file_path',
    'start_time',
    'end_time',
    'is_randomize',
    'deleted_at',
  ];

  public function questions()
  {
    return $this->hasMany(Question::class);
  }

  protected $casts = [];

  public $timestamps = true;

  const TYPES = [];

  protected $with = [];
}
