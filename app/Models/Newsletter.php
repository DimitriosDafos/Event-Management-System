<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model {
    protected $fillable = ['subject','body','sent_by','sent_at','recipient_count','failed_count'];
    protected $casts = ['sent_at' => 'datetime'];

    public function sentBy()  { return $this->belongsTo(User::class, 'sent_by'); }
    public function sends()   { return $this->hasMany(NewsletterSend::class); }
}
