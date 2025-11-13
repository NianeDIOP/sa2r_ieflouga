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
        Schema::create('rapport_dictionnaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_id')->constrained('rapports')->onDelete('cascade');
            
            // Dictionnaire Français
            $table->integer('dictionnaire_francais_eleves')->nullable();
            $table->integer('dictionnaire_francais_enseignants')->nullable();
            $table->enum('dictionnaire_francais_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            
            // Dictionnaire National
            $table->integer('dictionnaire_national_eleves')->nullable();
            $table->integer('dictionnaire_national_enseignants')->nullable();
            $table->enum('dictionnaire_national_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            
            // Dictionnaire Anglais
            $table->integer('dictionnaire_anglais_eleves')->nullable();
            $table->integer('dictionnaire_anglais_enseignants')->nullable();
            $table->enum('dictionnaire_anglais_etat', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            
            // Besoins et observations
            $table->text('besoins_dictionnaires')->nullable();
            $table->integer('budget_estime_dictionnaires')->nullable();
            $table->text('observations_dictionnaires')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_dictionnaires');
    }
};
