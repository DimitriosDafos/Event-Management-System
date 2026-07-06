<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model {
    protected $fillable = ['title','body','active','sort_order','created_by'];
    protected $casts = ['active' => 'boolean'];

    public function createdBy() { return $this->belongsTo(User::class, 'created_by'); }

    public function scopeActive($q) { return $q->where('active', true)->orderBy('sort_order')->orderByDesc('created_at'); }
}
