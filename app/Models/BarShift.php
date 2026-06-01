<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BarShift extends Model {
    protected $fillable = ['party_id','user_id','area','from','till'];
    public function party() { return $this->belongsTo(Party::class); }
    public function user() { return $this->belongsTo(User::class); }
}
