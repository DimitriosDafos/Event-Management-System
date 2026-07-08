<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('newsletters', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('body');
            $table->foreignId('sent_by')->constrained('users');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedInteger('recipient_count')->default(0);
            $table->unsignedInteger('failed_count')->default(0);
            $table->timestamps();
        });

        Schema::create('newsletter_sends', function (Blueprint $table) {
            $table->id();
            $table->foreignId('newsletter_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('name')->nullable();
            $table->boolean('success')->default(true);
            $table->text('error')->nullable();
            $table->timestamp('sent_at')->useCurrent();
        });
    }

    public function down(): void {
        Schema::dropIfExists('newsletter_sends');
        Schema::dropIfExists('newsletters');
    }
};
