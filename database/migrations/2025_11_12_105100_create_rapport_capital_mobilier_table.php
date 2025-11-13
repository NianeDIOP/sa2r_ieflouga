<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_capital_mobilier', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');

            // MOBILIER ÉLÈVES
            $table->integer('tables_bancs_total')->default(0);
            $table->integer('tables_bancs_bon_etat')->default(0);

            // MOBILIER ENSEIGNANTS
            $table->integer('bureaux_total')->default(0);
            $table->integer('bureaux_bon_etat')->default(0);
            $table->integer('chaises_total')->default(0);
            $table->integer('chaises_bon_etat')->default(0);
            $table->integer('armoires_total')->default(0);
            $table->integer('armoires_bon_etat')->default(0);

            // TABLEAUX
            $table->integer('tableaux_mobiles_total')->default(0);
            $table->integer('tableaux_mobiles_bon_etat')->default(0);
            $table->integer('tableaux_interactifs_total')->default(0);
            $table->integer('tableaux_interactifs_bon_etat')->default(0);
            $table->integer('tableaux_muraux_total')->default(0);
            $table->integer('tableaux_muraux_bon_etat')->default(0);

            // SYMBOLES NATIONAUX
            $table->integer('mat_drapeau_total')->default(0);
            $table->integer('mat_drapeau_bon_etat')->default(0);
            $table->integer('drapeau_total')->default(0);
            $table->integer('drapeau_bon_etat')->default(0);

            $table->timestamps();
            $table->unique('rapport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_capital_mobilier');
    }
};
