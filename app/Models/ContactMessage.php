<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model {
    protected $fillable = ['name', 'email', 'subject', 'message', 'read', 'ip'];
    protected $casts = ['read' => 'boolean'];

    public function scopeUnread($q) { return $q->where('read', false); }
}
