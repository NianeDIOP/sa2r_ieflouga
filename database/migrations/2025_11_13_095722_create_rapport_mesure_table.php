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
        Schema::create('rapport_mesure', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rapport_id')->constrained('rapports')->onDelete('cascade');
            
            // Instruments de mesure longueur
            $table->integer('decametres')->nullable();
            $table->integer('metres_plies')->nullable();
            $table->integer('centimetres')->nullable();
            $table->integer('reglets')->nullable();
            
            // Instruments de mesure masse
            $table->integer('balances_plateaux')->nullable();
            $table->integer('balances_electroniques')->nullable();
            $table->integer('poids_masses')->nullable();
            
            // Instruments de mesure capacité
            $table->integer('recipients_gradues')->nullable();
            $table->integer('eprouvettes')->nullable();
            $table->integer('verres_doseurs')->nullable();
            
            // Instruments de mesure temps
            $table->integer('chronometres')->nullable();
            $table->integer('horloges_demonstration')->nullable();
            $table->integer('sabliers')->nullable();
            
            // État général et besoins
            $table->enum('etat_instruments_mesure', ['excellent', 'bon', 'moyen', 'mauvais'])->nullable();
            $table->text('besoins_mesure')->nullable();
            $table->integer('budget_estime_mesure')->nullable();
            $table->text('observations_mesure')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_mesure');
    }
};
