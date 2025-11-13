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
        Schema::create('rapport_cmg', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // CMG (Cours Moyen Groupe)
            $table->integer('cmg_nombre')->default(0)->comment('Nombre de CMG');
            $table->string('cmg_combinaison_1')->nullable()->comment('Ex: CM1-CM2');
            $table->string('cmg_combinaison_2')->nullable();
            $table->string('cmg_combinaison_3')->nullable();
            $table->string('cmg_combinaison_autres')->nullable();

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
        Schema::dropIfExists('rapport_cmg');
    }
};
