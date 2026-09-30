<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhatsappApproval extends Model
{
    protected $fillable = ['message_id','user_id','approved_at'];
    protected $casts = ['approved_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function message(): BelongsTo { return $this->belongsTo(WhatsappMessage::class, 'message_id'); }
}
