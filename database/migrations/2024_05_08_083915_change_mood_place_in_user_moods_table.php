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
        DB::statement("ALTER TABLE user_moods MODIFY COLUMN mood DATE AFTER user_id");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_moods', function (Blueprint $table) {
            //
        });
    }
};
