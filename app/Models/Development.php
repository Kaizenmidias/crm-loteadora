<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Development extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'slug', 'type', 'description', 'address', 'city', 'state', 'zip_code', 'latitude', 'longitude', 'featured_image', 'status', 'launch_date', 'internal_notes'];

    protected function casts(): array
    {
        return ['launch_date' => 'date', 'latitude' => 'decimal:7', 'longitude' => 'decimal:7'];
    }

    public function blocks() { return $this->hasMany(Block::class); }
    public function lots() { return $this->hasMany(Lot::class); }
    public function maps() { return $this->hasMany(LotMap::class); }
}
