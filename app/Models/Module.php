<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
  use HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'modules';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'course_id',
    'title',
    'description',
    'order',
    'deleted_at',
  ];


  protected $casts = [];


  public $timestamps = true;

  const TYPES = [];
  protected $with = [];

  public function materials()
  {
    return $this->hasMany(Material::class);
  }

  // public function countVideo(int $id)
  // {
  //   return Material::where('module_id', $id)->where('type', 'video')->count();
  // }

  // public function countTest(int $id)
  // {
  //   return Material::where('module_id', $id)->where('type', 'test')->count();
  // }

  public function countVideo()
  {
    return Material::where('module_id', $this->id)->where('type', 'video')->count();
  }
  public function countText()
  {
    return Material::where('module_id', $this->id)->where('type', 'text')->count();
  }
  public function countTest()
  {
    return Material::where('module_id', $this->id)->where('type', 'test')->count();
  }
}
