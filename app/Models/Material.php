<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Material extends Model
{
    use SoftDeletes;

    protected $fillable = ['development_id', 'created_by', 'name', 'type', 'file_path', 'mime_type', 'size'];
    public function development() { return $this->belongsTo(Development::class); }
}
