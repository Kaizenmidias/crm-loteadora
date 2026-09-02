<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use SoftDeletes;

    protected $fillable = ['development_id', 'name', 'code', 'description', 'sort_order', 'status'];

    public function development() { return $this->belongsTo(Development::class); }
    public function lots() { return $this->hasMany(Lot::class); }
}
