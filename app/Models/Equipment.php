<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Equipment extends Model
{
    protected $fillable = ['party_id','artikel','menge','kabeltyp','kabellaenge','sort_order'];

    public function party(): BelongsTo { return $this->belongsTo(Party::class); }
}
