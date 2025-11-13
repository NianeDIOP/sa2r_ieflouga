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
        Schema::create('rapports', function (Blueprint $table) {
            $table->id();
            
            // Relation avec établissement
            $table->unsignedBigInteger('etablissement_id');
            $table->foreign('etablissement_id')
                  ->references('id')
                  ->on('etablissements')
                  ->onDelete('cascade');
            
            // Informations du rapport
            $table->string('annee_scolaire'); // Ex: "2024-2025"
            $table->enum('trimestre', ['1', '2', '3', 'annuel'])->default('annuel');
            $table->date('date_rapport');
            
            // Workflow et statuts
            $table->enum('statut', ['brouillon', 'soumis', 'validé', 'rejeté'])->default('brouillon');
            
            // Soumission
            $table->unsignedBigInteger('submitted_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreign('submitted_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Validation
            $table->unsignedBigInteger('validated_by')->nullable();
            $table->timestamp('validated_at')->nullable();
            $table->foreign('validated_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
            
            // Commentaire de rejet/validation
            $table->text('commentaire_validation')->nullable();
            
            $table->timestamps();
            
            // Index pour performance
            $table->index(['etablissement_id', 'annee_scolaire', 'trimestre']);
            $table->index('statut');

            // Unicité : un seul rapport par établissement et par année scolaire
            $table->unique(['etablissement_id', 'annee_scolaire']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapports');
    }
};
