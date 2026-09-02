<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $fillable = ['broker_id', 'client_id', 'development_id', 'name', 'phone', 'email', 'source', 'source_url', 'utm_source', 'utm_medium', 'utm_campaign', 'utm_content', 'utm_term', 'stage', 'notes'];

    public function broker() { return $this->belongsTo(Broker::class); }
    public function client() { return $this->belongsTo(Client::class); }
    public function development() { return $this->belongsTo(Development::class); }
    public function activities() { return $this->hasMany(Activity::class); }
}
