<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DjLineup extends Model {
    protected $table = 'dj_lineup';
    protected $fillable = ['party_id','dj_name','from','till','style','website','sort_order'];
    public function party() { return $this->belongsTo(Party::class); }
}
