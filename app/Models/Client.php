<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['broker_id', 'development_id', 'lot_id', 'name', 'cpf', 'phone', 'whatsapp', 'email', 'birth_date', 'zip_code', 'address', 'address_number', 'complement', 'neighborhood', 'city', 'state', 'lead_source', 'status', 'notes'];

    protected function casts(): array { return ['birth_date' => 'date']; }

    public function broker() { return $this->belongsTo(Broker::class); }
    public function development() { return $this->belongsTo(Development::class); }
    public function lot() { return $this->belongsTo(Lot::class); }
    public function leads() { return $this->hasMany(Lead::class); }
    public function activities() { return $this->hasMany(Activity::class); }
}
