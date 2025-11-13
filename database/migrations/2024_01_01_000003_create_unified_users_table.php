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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            
            // Type d'utilisateur : 'admin' ou 'etablissement'
            $table->enum('type', ['admin', 'etablissement'])->default('etablissement');
            
            // Champs communs
            $table->string('email')->unique()->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            
            // Champs pour Admin
            $table->string('username')->unique()->nullable(); // Pour admin
            $table->string('nom_complet')->nullable(); // Pour admin
            $table->enum('role', ['super_admin', 'admin'])->nullable(); // Pour admin
            
            // Champs pour Établissement
            $table->string('code', 10)->unique()->nullable(); // Pour établissement
            $table->string('nom')->nullable(); // Pour établissement
            $table->string('arrondissement')->nullable(); // Pour établissement
            $table->string('commune')->nullable(); // Pour établissement
            $table->string('zone')->nullable(); // Pour établissement
            $table->enum('statut', ['Public', 'Privé'])->nullable(); // Pour établissement
            
            $table->rememberToken();
            $table->timestamps();
            
            // Index pour améliorer les performances
            $table->index('type');
            $table->index('username');
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
