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
        Schema::create('hostings', function (Blueprint $table) {
            $table->id();

            // User reference
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id', 'fk_hostings_user')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Domain & Plan Info
            $table->string('domain')->unique()->index(); // Primary domain name
            $table->string('package')->index(); // CyberPanel package name
            $table->string('plan')->index();    // SaaS plan name (Free, Pro, etc.)

            // Status and Feedback
            $table->string('cyberpanel_status', 20)
                  ->default('pending')
                  ->index(); // flexible for any status (pending, success, failed, active, suspended, etc.)
            $table->string('cyberpanel_message')->nullable(); // Optional: error/success from API

            // SSL + Expiry
            $table->boolean('ssl')->default(false); // SSL provisioned or not
            $table->timestamp('expiry_date')->nullable()->index(); // Optional expiry for plan

            // Timestamps
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hostings');
    }
};
