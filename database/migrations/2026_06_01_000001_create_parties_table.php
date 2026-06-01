<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('date');
            $table->time('start_time');
            $table->time('end_time')->nullable();
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->string('flyer_path')->nullable();
            $table->enum('status', ['draft', 'published', 'past'])->default('draft');
            $table->boolean('is_special')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('parties'); }
};
