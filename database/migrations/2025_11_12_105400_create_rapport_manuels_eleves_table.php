<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_manuels_eleves', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');
            
            $table->enum('niveau', ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2']);

            // MANUELS PAR MATIÈRE
            $table->integer('lc_francais')->default(0)->comment('Lecture/Communication Français');
            $table->integer('mathematiques')->default(0);
            $table->integer('edd')->default(0)->comment('Éveil au Développement Durable');
            $table->integer('dm')->default(0)->comment('Dessin/Musique');
            $table->integer('manuel_classe')->default(0);
            $table->integer('livret_maison')->default(0);
            $table->integer('livret_devoir_gradue')->default(0);
            $table->integer('planche_alphabetique')->default(0);
            $table->integer('manuel_arabe')->default(0);
            $table->integer('manuel_religion')->default(0);
            $table->integer('autre_religion')->default(0);
            $table->integer('autres_manuels')->default(0);

            $table->timestamps();
            
            // 6 enregistrements par rapport (un par niveau)
            $table->unique(['rapport_id', 'niveau']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_manuels_eleves');
    }
};
