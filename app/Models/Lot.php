<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lot extends Model
{
    use SoftDeletes;

    protected $fillable = ['development_id', 'block_id', 'number', 'internal_code', 'area', 'front', 'back', 'left_side', 'right_side', 'price', 'promotional_price', 'status', 'map_identifier', 'notes'];

    protected function casts(): array
    {
        return ['area' => 'decimal:2', 'front' => 'decimal:2', 'back' => 'decimal:2', 'left_side' => 'decimal:2', 'right_side' => 'decimal:2', 'price' => 'decimal:2', 'promotional_price' => 'decimal:2'];
    }

    public function development() { return $this->belongsTo(Development::class); }
    public function block() { return $this->belongsTo(Block::class); }
    public function reservations() { return $this->hasMany(Reservation::class); }
    public function sales() { return $this->hasMany(Sale::class); }
}
