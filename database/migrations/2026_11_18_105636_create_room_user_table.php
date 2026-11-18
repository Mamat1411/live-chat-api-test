<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('room_user', function (Blueprint $table) {
            $table->uuid("id")->primary()->index();
            $table->foreignUuid('room_id')->constrained("rooms")->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained("users")->cascadeOnDelete();
            $table->timestamp('last_read_at')->nullable();
            $table->timestamps();
            $table->unique(['room_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('room_user');
    }
};
