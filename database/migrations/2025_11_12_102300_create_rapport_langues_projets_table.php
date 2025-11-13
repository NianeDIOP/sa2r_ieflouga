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
        Schema::create('rapport_langues_projets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // Langue nationale enseignée
            $table->enum('langue_nationale', ['wolof', 'pulaar', 'serer', 'mandinka', 'soninke', 'autre'])->nullable();

            // Enseignement de l'Arabe
            $table->boolean('enseignement_arabe_existe')->default(false);

            // Projets informatiques
            $table->boolean('projets_informatiques_existe')->default(false);
            $table->text('projets_informatiques_nom')->nullable()->comment('Nom/description des projets');

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
        Schema::dropIfExists('rapport_langues_projets');
    }
};
