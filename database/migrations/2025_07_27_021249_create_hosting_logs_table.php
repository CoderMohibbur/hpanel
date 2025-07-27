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
        Schema::create('hosting_logs', function (Blueprint $table) {
            $table->id();

            // 🔗 Related Hosting
            $table->foreignId('hosting_id')
                ->constrained()
                ->cascadeOnDelete()
                ->index();

            // 📝 Action performed (e.g., create, ssl_renew, wp_install)
            $table->string('action', 100)->index();

            // 🧾 Full response or error log
            $table->text('response')->nullable();

            // 📅 Timestamps
            $table->timestamps();
            $table->softDeletes(); // for safety

            // Optional: index for future filtering
            $table->index(['hosting_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hosting_logs');
    }
};
