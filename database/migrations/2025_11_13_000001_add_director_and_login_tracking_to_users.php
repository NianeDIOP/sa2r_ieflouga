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
        Schema::table('users', function (Blueprint $table) {
            // Informations du directeur (pour les établissements)
            $table->string('directeur_nom')->nullable()->after('statut');
            $table->string('directeur_telephone', 20)->nullable()->after('directeur_nom');
            
            // Suivi des connexions
            $table->timestamp('last_login_at')->nullable()->after('directeur_telephone');
            $table->integer('login_count')->default(0)->after('last_login_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['directeur_nom', 'directeur_telephone', 'last_login_at', 'login_count']);
        });
    }
};
