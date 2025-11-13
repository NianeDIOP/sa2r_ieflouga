<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_manuels_maitre', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');
            
            $table->enum('niveau', ['CI', 'CP', 'CE1', 'CE2', 'CM1', 'CM2']);

            // GUIDES PÉDAGOGIQUES
            $table->integer('guide_lc_francais')->default(0);
            $table->integer('guide_mathematiques')->default(0);
            $table->integer('guide_edd')->default(0);
            $table->integer('guide_dm')->default(0);
            $table->integer('guide_pedagogique')->default(0);
            $table->integer('guide_arabe_religieux')->default(0);
            $table->integer('guide_langue_nationale')->default(0);
            $table->integer('cahier_recits')->default(0);
            $table->integer('autres_manuels_maitre')->default(0);

            $table->timestamps();
            
            // 6 enregistrements par rapport (un par niveau)
            $table->unique(['rapport_id', 'niveau']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_manuels_maitre');
    }
};
