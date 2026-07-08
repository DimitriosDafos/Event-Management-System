<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class NewsletterSend extends Model {
    public $timestamps = false;
    protected $fillable = ['newsletter_id','email','name','success','error','sent_at'];
    protected $casts = ['success' => 'boolean', 'sent_at' => 'datetime'];
}
