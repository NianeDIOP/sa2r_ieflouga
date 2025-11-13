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
        Schema::create('rapport_historique', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('rapport_id');
            $table->foreign('rapport_id')
                  ->references('id')
                  ->on('rapports')
                  ->onDelete('cascade');

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            // action performed: create, update, submit, validate, reject, comment, autosave, etc.
            $table->string('action', 50);

            // Optional textual note
            $table->text('commentaire')->nullable();

            // old/new status when applicable
            $table->string('ancien_statut')->nullable();
            $table->string('nouveau_statut')->nullable();

            $table->timestamps();

            $table->index(['rapport_id', 'user_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rapport_historique');
    }
};
