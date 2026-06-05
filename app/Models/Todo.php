<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model {
    protected $fillable = ['party_id','created_by','due_date','due_time','what','costs','costs_entered_by','done','done_at'];
    protected $casts = ['done'=>'boolean','done_at'=>'datetime','due_date'=>'date'];

    public function party()     { return $this->belongsTo(Party::class); }
    public function users()     { return $this->belongsToMany(User::class, 'todo_users'); }
    public function createdBy() { return $this->belongsTo(User::class,'created_by'); }
    public function costsEnteredBy() { return $this->belongsTo(User::class,'costs_entered_by'); }

    public function isAssigned(int $userId): bool
    {
        return $this->users->contains($userId);
    }
}
