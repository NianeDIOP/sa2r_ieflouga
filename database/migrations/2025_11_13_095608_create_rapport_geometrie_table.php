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
        Schema::create('rapport_geometrie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_id')->constrained('rapports')->onDelete('cascade');
            
            // Instruments de géométrie de base
            $table->integer('regles_graduees')->nullable();
            $table->integer('equerres')->nullable();
            $table->integer('rapporteurs')->nullable();
            $table->integer('compas')->nullable();
            $table->integer('metres_ruban')->nullable();
            
            // État général
            $table->enum('etat_instruments_geometrie', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            
            // Matériel de construction géométrique
            $table->integer('solides_geometriques')->nullable();
            $table->integer('planches_geometrie')->nullable();
            $table->integer('kit_geometrie_enseignant')->nullable();
            
            // Besoins et budget
            $table->text('besoins_geometrie')->nullable();
            $table->integer('budget_estime_geometrie')->nullable();
            $table->text('observations_geometrie')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_geometrie');
    }
};
