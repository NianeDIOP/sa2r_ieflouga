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
        Schema::table('rapports', function (Blueprint $table) {
            // Ajouter les colonnes manquantes pour le suivi des rejets
            $table->timestamp('date_soumission')->nullable()->after('submitted_at');
            $table->timestamp('date_validation')->nullable()->after('validated_at');
            $table->timestamp('date_rejet')->nullable()->after('date_validation');
            $table->text('motif_rejet')->nullable()->after('commentaire_validation');
            $table->text('commentaire_admin')->nullable()->after('motif_rejet');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rapports', function (Blueprint $table) {
            $table->dropColumn(['date_soumission', 'date_validation', 'date_rejet', 'motif_rejet', 'commentaire_admin']);
        });
    }
};
