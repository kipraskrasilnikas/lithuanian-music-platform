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
        Schema::table('songs', function (Blueprint $table) {
            // Add new column for embedded URL
            $table->string('embedded_url')->nullable()->after('song_url');
            $table->renameColumn('song_url', 'original_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('songs', function (Blueprint $table) {
            // Drop the added column for embedded URL
            $table->dropColumn('embedded_url');
            // Revert the name change of the URL column
            $table->renameColumn('original_url', 'song_url');
        });
    }
};
