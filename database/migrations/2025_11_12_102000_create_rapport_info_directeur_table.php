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
        Schema::create('rapport_info_directeur', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            // Informations du directeur
            $table->string('directeur_nom')->nullable();
            $table->string('directeur_contact_1')->nullable();
            $table->string('directeur_contact_2')->nullable();
            $table->string('directeur_email')->nullable();
            
            // Distance du siège IEF
            $table->decimal('distance_siege', 8, 2)->nullable()->comment('Distance en km du siège IEF');

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
        Schema::dropIfExists('rapport_info_directeur');
    }
};
