<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
    protected $fillable = ['name','username','password','role','active'];
    protected $hidden = ['password','remember_token'];
    protected $casts = ['active' => 'boolean', 'role' => 'array'];

    public function hasRole(string $r): bool
    {
        return in_array($r, (array)$this->role);
    }

    public function isAdmin(): bool    { return $this->hasRole('admin'); }
    public function isMarketing(): bool { return $this->hasRole('admin') || $this->hasRole('marketing'); }
    public function isDj(): bool        { return $this->hasRole('admin') || $this->hasRole('marketing') || $this->hasRole('dj'); }
    public function canManageParty(): bool { return $this->isMarketing(); }

    // Höchste Rolle für Anzeige
    public function primaryRole(): string
    {
        if ($this->hasRole('admin'))     return 'admin';
        if ($this->hasRole('marketing')) return 'marketing';
        if ($this->hasRole('dj'))        return 'dj';
        return 'member';
    }

    public function barShifts()     { return $this->hasMany(BarShift::class); }
    public function doorAssignments() { return $this->hasMany(DoorAssignment::class); }
    public function todos()         { return $this->hasMany(Todo::class, 'assigned_to'); }
}
