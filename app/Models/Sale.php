<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $fillable = ['client_id', 'broker_id', 'lot_id', 'development_id', 'reservation_id', 'value', 'sold_at', 'status', 'notes'];
    protected function casts(): array { return ['value' => 'decimal:2', 'sold_at' => 'date']; }
    public function client() { return $this->belongsTo(Client::class); }
    public function broker() { return $this->belongsTo(Broker::class); }
    public function lot() { return $this->belongsTo(Lot::class); }
    public function development() { return $this->belongsTo(Development::class); }
    public function reservation() { return $this->belongsTo(Reservation::class); }
}
