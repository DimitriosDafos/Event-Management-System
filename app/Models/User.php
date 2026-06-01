<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name','username','password','role','active'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['active' => 'boolean'];

    public function isAdmin(): bool { return $this->role === 'admin'; }
    public function isMarketing(): bool { return in_array($this->role, ['admin','marketing']); }
    public function canManageParty(): bool { return in_array($this->role, ['admin','marketing']); }

    public function barShifts() { return $this->hasMany(BarShift::class); }
    public function doorAssignments() { return $this->hasMany(DoorAssignment::class); }
    public function todos() { return $this->hasMany(Todo::class, 'assigned_to'); }
}
