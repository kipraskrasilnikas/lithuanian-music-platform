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
        Schema::create('artist_moods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('mood')->nullable(); // Define a string column for mood

            $table->timestamps();
        });

        DB::statement("ALTER TABLE artist_moods CHANGE COLUMN mood mood ENUM(
             'Ekscentriška',
                'Elegantiška',
                'Euforiška',
                'Žavi/kerinti',
                'Laiminga',
                'Viltinga',
                'Romantiška',
                'Seksuali',
                'Tamsi',
                'Baiminga',
                'Sunki/priverčianti susimąstyti',
                'Nerimąstinga',
                'Liūdna',
                'Sentimentali',
                'Pasakiška',
                'Klajojanti',
                'Atsipūtusi',
                'Mistiška',
                'Taikinga/rami',
                'Atpalaiduojanti',
                'Sklandi',
                'Pikta',
                'Perkrauta ir pašėlusi',
                'Besikeičiančio tempo',
                'Epinė ir didinga',
                'Išdaigi',
                'Bėgiojimo',
                'Įtampa',
                'Keista'
        )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('artist_moods');
    }
};
