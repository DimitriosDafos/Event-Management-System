<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Income extends Model {
    protected $table = 'income';
    protected $fillable = ['party_id','created_by','description','amount','date'];
    protected $casts = ['date'=>'date'];
    public function party() { return $this->belongsTo(Party::class); }
    public function createdBy() { return $this->belongsTo(User::class,'created_by'); }
}
