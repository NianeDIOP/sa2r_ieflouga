<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rapport_equipement_informatique', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')->references('id')->on('rapports')->onDelete('cascade');

            // ORDINATEURS
            $table->integer('ordinateurs_fixes_total')->default(0);
            $table->integer('ordinateurs_fixes_bon_etat')->default(0);
            $table->integer('ordinateurs_portables_total')->default(0);
            $table->integer('ordinateurs_portables_bon_etat')->default(0);
            $table->integer('tablettes_total')->default(0);
            $table->integer('tablettes_bon_etat')->default(0);

            // IMPRIMANTES
            $table->integer('imprimantes_laser_total')->default(0);
            $table->integer('imprimantes_laser_bon_etat')->default(0);
            $table->integer('imprimantes_jet_encre_total')->default(0);
            $table->integer('imprimantes_jet_encre_bon_etat')->default(0);
            $table->integer('imprimantes_multifonction_total')->default(0);
            $table->integer('imprimantes_multifonction_bon_etat')->default(0);
            $table->integer('photocopieuses_total')->default(0);
            $table->integer('photocopieuses_bon_etat')->default(0);

            // ÉQUIPEMENT AUDIOVISUEL
            $table->integer('videoprojecteurs_total')->default(0);
            $table->integer('videoprojecteurs_bon_etat')->default(0);
            $table->integer('autres_equipements_total')->default(0);
            $table->integer('autres_equipements_bon_etat')->default(0);

            $table->timestamps();
            $table->unique('rapport_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rapport_equipement_informatique');
    }
};
