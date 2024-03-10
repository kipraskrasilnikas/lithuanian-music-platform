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
        DB::statement("ALTER TABLE users MODIFY COLUMN genre VARCHAR(255) AFTER name");
        DB::statement("ALTER TABLE users MODIFY COLUMN specialty VARCHAR(255) AFTER genre");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN specialty VARCHAR(255) AFTER updated_at");
        DB::statement("ALTER TABLE users MODIFY COLUMN genre VARCHAR(255) AFTER specialty");
    }
};
