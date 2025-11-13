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
        Schema::create('rapport_cfee', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // CFEE (Certificat de Fin d'Études Élémentaires)
            $table->integer('cfee_candidats_total')->default(0);
            $table->integer('cfee_candidats_filles')->default(0);
            $table->integer('cfee_admis_total')->default(0);
            $table->integer('cfee_admis_filles')->default(0);

            // Taux calculés automatiquement
            $table->decimal('cfee_taux_reussite', 5, 2)->default(0)->comment('Calculé: (admis/candidats)*100');
            $table->decimal('cfee_taux_reussite_filles', 5, 2)->default(0)->comment('Calculé: (admis_filles/candidats_filles)*100');

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
        Schema::dropIfExists('rapport_cfee');
    }
};
