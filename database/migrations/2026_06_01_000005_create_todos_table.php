<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->constrained()->cascadeOnDelete();
            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->date('due_date')->nullable();
            $table->time('due_time')->nullable();
            $table->string('what');
            $table->decimal('costs', 8, 2)->default(0);
            $table->foreignId('costs_entered_by')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('done')->default(false);
            $table->timestamp('done_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('todos'); }
};
