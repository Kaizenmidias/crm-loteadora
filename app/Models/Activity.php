<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = ['client_id', 'lead_id', 'broker_id', 'user_id', 'type', 'title', 'description', 'scheduled_at', 'status'];

    protected function casts(): array { return ['scheduled_at' => 'datetime']; }

    public function client() { return $this->belongsTo(Client::class); }
    public function lead() { return $this->belongsTo(Lead::class); }
    public function broker() { return $this->belongsTo(Broker::class); }
    public function user() { return $this->belongsTo(User::class); }
}
