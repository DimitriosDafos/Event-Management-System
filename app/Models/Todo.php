<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Todo extends Model {
    protected $fillable = ['party_id','assigned_to','created_by','due_date','due_time','what','costs','costs_entered_by','done','done_at'];
    protected $casts = ['done'=>'boolean','done_at'=>'datetime','due_date'=>'date'];
    public function party() { return $this->belongsTo(Party::class); }
    public function assignedTo() { return $this->belongsTo(User::class,'assigned_to'); }
    public function createdBy() { return $this->belongsTo(User::class,'created_by'); }
    public function costsEnteredBy() { return $this->belongsTo(User::class,'costs_entered_by'); }
}
