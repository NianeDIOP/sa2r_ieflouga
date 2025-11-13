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
        Schema::create('rapport_infrastructures_base', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // CPE (Case des Tout-Petits)
            $table->boolean('cpe_existe')->default(false);
            $table->integer('cpe_nombre')->default(0);

            // Clôture
            $table->boolean('cloture_existe')->default(false);
            $table->enum('cloture_type', ['dur', 'provisoire', 'haie', 'autre'])->nullable();

            // Eau
            $table->boolean('eau_existe')->default(false);
            $table->enum('eau_type', ['robinet', 'forage', 'puits', 'autre'])->nullable();

            // Électricité
            $table->boolean('electricite_existe')->default(false);
            $table->enum('electricite_type', ['SENELEC', 'solaire', 'groupe', 'autre'])->nullable();

            // Connexion Internet
            $table->boolean('connexion_internet_existe')->default(false);
            $table->enum('connexion_internet_type', ['fibre', '4G', 'satellite', 'autre'])->nullable();

            // Cantine Scolaire
            $table->boolean('cantine_existe')->default(false);
            $table->enum('cantine_type', ['state', 'partenaire', 'communaute', 'autre'])->nullable();

            $table->timestamps();

            // Un seul enregistrement par rapport
            $table->unique('rapport_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_infrastructures_base');
    }
};
