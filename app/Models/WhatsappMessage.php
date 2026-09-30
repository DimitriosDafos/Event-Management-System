<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WhatsappMessage extends Model
{
    protected $fillable = [
        'created_by','text','link','flyer_path','video_url',
        'group_type','group_id','status','error','sent_at',
    ];
    protected $casts = ['sent_at' => 'datetime'];

    public function creator(): BelongsTo  { return $this->belongsTo(User::class, 'created_by'); }
    public function approvals(): HasMany  { return $this->hasMany(WhatsappApproval::class, 'message_id'); }

    public function approvalCount(): int  { return $this->approvals()->count(); }
    public function requiredApprovals(): int { return $this->group_type === 'community' ? 2 : 1; }
    public function isFullyApproved(): bool { return $this->approvalCount() >= $this->requiredApprovals(); }
    public function hasApprovedBy(int $userId): bool { return $this->approvals()->where('user_id', $userId)->exists(); }
}
