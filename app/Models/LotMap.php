<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotMap extends Model
{
    protected $fillable = ['development_id', 'name', 'file_path', 'file_type', 'metadata', 'is_active'];
    protected function casts(): array { return ['metadata' => 'array', 'is_active' => 'boolean']; }
    public function development() { return $this->belongsTo(Development::class); }
    public function areas() { return $this->hasMany(LotMapArea::class); }
}
