<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    protected $fillable = ['title','description','date','start_time','end_time','location','address','flyer_path','status','is_special'];
    protected $casts = ['date'=>'date','is_special'=>'boolean'];

    public function barShifts() { return $this->hasMany(BarShift::class)->orderBy('from'); }
    public function doorAssignments() { return $this->hasMany(DoorAssignment::class)->orderBy('from'); }
    public function djLineup() { return $this->hasMany(DjLineup::class)->orderBy('from'); }
    public function todos() { return $this->hasMany(Todo::class)->orderBy('due_date')->orderBy('due_time'); }
    public function income() { return $this->hasMany(Income::class); }

    public function totalExpenses(): float
    {
        return $this->todos()->sum('costs');
    }

    public function totalIncome(): float
    {
        return $this->income()->sum('amount');
    }

    public function balance(): float
    {
        return $this->totalIncome() - $this->totalExpenses();
    }

    public function isPublished(): bool { return $this->status === 'published'; }
    public function isDraft(): bool { return $this->status === 'draft'; }
    public function isPast(): bool { return $this->status === 'past'; }

    public function scopePublished($q) { return $q->where('status','published'); }
    public function scopeUpcoming($q) { return $q->whereIn('status',['published','draft'])->orderBy('date'); }
    public function scopeNextPublic($q) { return $q->where('status','published')->where('date','>=',now()->toDateString())->orderBy('date')->limit(1); }
}
