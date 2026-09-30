<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('text')->nullable();
            $table->string('link', 500)->nullable();
            $table->string('flyer_path')->nullable();
            $table->string('video_url', 500)->nullable();
            $table->string('group_type', 20)->default('test'); // test | community
            $table->string('group_id')->nullable();
            $table->string('status', 20)->default('pending'); // pending | approved | sent | failed
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();
        });

        Schema::create('whatsapp_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('whatsapp_messages')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamp('approved_at');
            $table->timestamps();
            $table->unique(['message_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_approvals');
        Schema::dropIfExists('whatsapp_messages');
    }
};
