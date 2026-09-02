<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Broker extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'name', 'cpf', 'creci', 'phone', 'whatsapp', 'email', 'company', 'city', 'state', 'status'];

    public function user() { return $this->belongsTo(User::class); }
    public function clients() { return $this->hasMany(Client::class); }
    public function leads() { return $this->hasMany(Lead::class); }
    public function activities() { return $this->hasMany(Activity::class); }
}
