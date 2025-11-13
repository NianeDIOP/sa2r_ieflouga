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
        Schema::create('etablissements', function (Blueprint $table) {
            $table->id();
            $table->string('etablissement', 150);
            $table->string('arrondissement', 100)->nullable();
            $table->string('commune', 100)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('zone', 100)->nullable();
            $table->char('code', 10)->unique();
            $table->integer('geo_ref_x')->nullable();
            $table->integer('geo_ref_y')->nullable();
            $table->string('statut', 50)->nullable();
            $table->string('type_statut', 100)->nullable();
            $table->year('date_creation')->nullable();
            $table->year('date_ouverture')->nullable();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('code');
            $table->index('arrondissement');
            $table->index('commune');
            $table->index('statut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etablissements');
    }
};
