<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['client_id', 'broker_id', 'lot_id', 'development_id', 'created_by', 'approved_by', 'reserved_at', 'expires_at', 'approved_at', 'cancelled_at', 'status', 'notes'];

    protected function casts(): array { return ['reserved_at' => 'datetime', 'expires_at' => 'datetime', 'approved_at' => 'datetime', 'cancelled_at' => 'datetime']; }
    public function client() { return $this->belongsTo(Client::class); }
    public function broker() { return $this->belongsTo(Broker::class); }
    public function lot() { return $this->belongsTo(Lot::class); }
    public function development() { return $this->belongsTo(Development::class); }
    public function sale() { return $this->hasOne(Sale::class); }
}
