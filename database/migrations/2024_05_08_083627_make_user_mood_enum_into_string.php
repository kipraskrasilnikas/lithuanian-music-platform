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
        // Add new string column
        Schema::table('user_moods', function (Blueprint $table) {
            $table->string('temp_mood')->nullable();
        });

        // Migrate data from enum column to string column
        DB::table('user_moods')->update(['temp_mood' => DB::raw('mood')]);

        // Drop the enum column
        Schema::table('user_moods', function (Blueprint $table) {
            $table->dropColumn('mood');
        });

        // Rename the new string column to match the original column name
        Schema::table('user_moods', function (Blueprint $table) {
            $table->renameColumn('temp_mood', 'mood');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
