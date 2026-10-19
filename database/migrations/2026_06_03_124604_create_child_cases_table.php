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
        Schema::create('child_cases', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('district_id')->constrained();
            $table->text('minor_name'); // Encrypted
            $table->string('minor_name_hash')->index(); // Blind Index
            $table->text('phone_number')->nullable(); // Encrypted
            $table->string('phone_number_hash')->nullable()->index(); // Blind Index
            $table->text('family_details')->nullable(); // Encrypted
            $table->string('state')->default('Received')->index();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('child_cases');
    }
};
