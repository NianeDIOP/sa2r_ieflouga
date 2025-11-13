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
        Schema::create('rapport_structures_communautaires', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // CGE (Comité de Gestion de l'École)
            $table->boolean('cge_existe')->default(false);
            $table->integer('cge_hommes')->nullable();
            $table->integer('cge_femmes')->nullable();
            $table->integer('cge_total')->nullable()->comment('Calculé automatiquement');
            $table->string('cge_president_nom')->nullable();
            $table->string('cge_president_contact')->nullable();
            $table->string('cge_tresorier_nom')->nullable();
            $table->string('cge_tresorier_contact')->nullable();

            // G.Scol (Gouvernement Scolaire)
            $table->boolean('gscol_existe')->default(false);
            $table->integer('gscol_garcons')->nullable();
            $table->integer('gscol_filles')->nullable();
            $table->integer('gscol_total')->nullable()->comment('Calculé automatiquement');
            $table->string('gscol_president_nom')->nullable();
            $table->string('gscol_president_contact')->nullable();

            // APE (Association des Parents d'Élèves)
            $table->boolean('ape_existe')->default(false);
            $table->integer('ape_hommes')->nullable();
            $table->integer('ape_femmes')->nullable();
            $table->integer('ape_total')->nullable()->comment('Calculé automatiquement');
            $table->string('ape_president_nom')->nullable();
            $table->string('ape_president_contact')->nullable();

            // AME (Association des Mères d'Élèves)
            $table->boolean('ame_existe')->default(false);
            $table->integer('ame_nombre')->nullable()->comment('Nombre total de membres');
            $table->string('ame_president_nom')->nullable();
            $table->string('ame_president_contact')->nullable();

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
        Schema::dropIfExists('rapport_structures_communautaires');
    }
};
