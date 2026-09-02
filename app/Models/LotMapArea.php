<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LotMapArea extends Model
{
    protected $fillable = ['lot_map_id', 'lot_id', 'identifier', 'coordinates', 'svg_path', 'polygon', 'metadata'];
    protected function casts(): array { return ['polygon' => 'array', 'metadata' => 'array']; }
    public function map() { return $this->belongsTo(LotMap::class, 'lot_map_id'); }
    public function lot() { return $this->belongsTo(Lot::class); }
}
