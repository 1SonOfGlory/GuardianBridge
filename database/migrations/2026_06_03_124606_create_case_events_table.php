<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('case_events', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('child_case_id')->constrained();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('from_state')->nullable();
            $table->string('to_state');
            $table->string('event_type');
            $table->json('payload')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('case_events');
    }
};
