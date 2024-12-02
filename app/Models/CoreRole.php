<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoreRole extends Model
{
  use HasFactory;

  protected $table = 'core_roles';

  protected $fillable = [
    'name',
    'group_id',
  ];

  public function group()
  {
    return $this->belongsTo(CoreGroup::class, 'group_id', 'id');
  }

  protected $casts = [];

  public $timestamps = true;

  const TYPES = [];
  protected $with = [];
}
