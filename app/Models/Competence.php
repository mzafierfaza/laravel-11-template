<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
  use HasFactory;

  protected $table = 'competences';

  protected $fillable = [
    'title',
    'level',
    'description',
    'benefits',
    'start_date',
    'end_date',
    'certificate',
    'certificate_can_download',
    'is_random_course',
    'image',
    'status',
    'is_forever',
    'approved_status',
    'approved_at',
    'approved_by',
  ];

  protected $casts = [];

  public $timestamps = true;

  const TYPES = [];

  protected $with = [];

  public function enrollments()
  {
    return $this->hasMany(Enrollment::class);
  }

  public function courses()
  {
    return $this->hasMany(CompetenceCourse::class);
  }

  public function countEnrollments()
  {
    return $this->enrollments()->count();
  }

  public function getImage()
  {
    $url = config('app.minio_url') . '/' . config('app.minio_bucket') . '/';

    return $url . $this->image;
  }
  public function getDocument()
  {
    $url = config('app.minio_url') . '/' . config('app.minio_bucket') . '/';

    return $url . $this->certificate;
  }

  public function periode()
  {
    if ($this->start_date == null || $this->end_date == null) {
      return "Belum di setting";
    }
    // make periode with $this->start_date and $this->end_date
    $start = date('d F Y', strtotime($this->start_date));
    $end = date('d F Y', strtotime($this->end_date));

    return $start . ' - ' . $end;
  }
}
