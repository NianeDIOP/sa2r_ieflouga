<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Etablissement;
use App\Models\AnneeScolaire;
use App\Models\Rapport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class RapportValidationTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $etablissement;
    protected $anneeScolaire;
    protected $rapport;

    protected function setUp(): void
    {
        parent::setUp();

        // Créer un utilisateur de test
        $this->user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);

        // Créer un établissement
        $this->etablissement = Etablissement::create([
            'nom' => 'École Test',
            'code' => 'TEST001',
            'type' => 'Primaire',
            'region' => 'Dakar',
            'departement' => 'Dakar',
            'commune' => 'Dakar',
        ]);

        // Créer une année scolaire
        $this->anneeScolaire = AnneeScolaire::create([
            'annee' => '2024-2025',
            'date_debut' => '2024-10-01',
            'date_fin' => '2025-06-30',
            'active' => true,
        ]);

        // Créer un rapport
        $this->rapport = Rapport::create([
            'etablissement_id' => $this->etablissement->id,
            'annee_scolaire_id' => $this->anneeScolaire->id,
            'statut' => 'brouillon',
        ]);
    }

    #[Test]
    public function it_saves_valid_cfee_data()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => [
                'cfee_candidats_total' => 100,
                'cfee_candidats_filles' => 45,
                'cfee_admis_total' => 80,
                'cfee_admis_filles' => 36,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Données CFEE sauvegardées avec succès',
        ]);
    }

    #[Test]
    public function it_rejects_cfee_when_admis_greater_than_candidats()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => [
                'cfee_candidats_total' => 100,
                'cfee_admis_total' => 110, // Invalide
            ],
        ]);

        // Le frontend devrait bloquer, mais testons aussi le backend
        $response->assertStatus(422);
    }

    #[Test]
    public function it_validates_100_percent_admission_coherence()
    {
        // Si 100% d'admis, les filles admises doivent être égales aux candidates
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => [
                'cfee_candidats_total' => 100,
                'cfee_candidats_filles' => 45,
                'cfee_admis_total' => 100, // 100% d'admis
                'cfee_admis_filles' => 40, // Devrait être 45
            ],
        ]);

        // Validation frontend devrait bloquer
        // Si arrive au backend, devrait être rejeté
        $response->assertStatus(422);
    }

    #[Test]
    public function it_saves_valid_entree_sixieme_data()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'entree-sixieme',
            'data' => [
                'sixieme_candidats_total' => 50,
                'sixieme_candidats_filles' => 20,
                'sixieme_admis_total' => 40,
                'sixieme_admis_filles' => 16,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'message' => 'Données Entrée Sixième sauvegardées avec succès',
        ]);
    }

    #[Test]
    public function it_saves_valid_capital_immobilier_data()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'capital-immobilier',
            'data' => [
                'salles_dur_total' => 10,
                'salles_dur_bon_etat' => 8,
                'abris_provisoires_total' => 2,
                'abris_provisoires_bon_etat' => 1,
            ],
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
        ]);
    }

    #[Test]
    public function it_rejects_bon_etat_greater_than_total()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'capital-immobilier',
            'data' => [
                'salles_dur_total' => 10,
                'salles_dur_bon_etat' => 15, // Invalide
            ],
        ]);

        // Frontend devrait bloquer
        $response->assertStatus(422);
    }

    #[Test]
    public function it_rejects_negative_values()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'capital-immobilier',
            'data' => [
                'salles_dur_total' => -5, // Invalide
                'salles_dur_bon_etat' => 3,
            ],
        ]);

        // Frontend devrait bloquer
        $response->assertStatus(422);
    }

    #[Test]
    public function it_saves_valid_capital_mobilier_data()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'capital-mobilier',
            'data' => [
                'tables_bancs_total' => 100,
                'tables_bancs_bon_etat' => 80,
                'chaises_eleves_total' => 200,
                'chaises_eleves_bon_etat' => 150,
            ],
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_saves_valid_equipements_informatiques_data()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'equipements-informatiques',
            'data' => [
                'ordinateurs_fixes_total' => 5,
                'ordinateurs_fixes_bon_etat' => 4,
                'ordinateurs_portables_total' => 3,
                'ordinateurs_portables_bon_etat' => 2,
            ],
        ]);

        $response->assertStatus(200);
    }

    #[Test]
    public function it_handles_empty_values_as_zero()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => [
                'cfee_candidats_total' => '',
                'cfee_candidats_filles' => '',
            ],
        ]);

        // Les valeurs vides devraient être converties en 0
        $response->assertStatus(200);
    }

    #[Test]
    public function it_validates_effectifs_mai_less_than_octobre()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'effectifs',
            'data' => [
                'effectif_octobre_ci_garcons' => 50,
                'effectif_mai_ci_garcons' => 60, // Invalide
            ],
        ]);

        // Frontend devrait bloquer
        $response->assertStatus(422);
    }

    #[Test]
    public function it_validates_abandons_within_difference()
    {
        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'abandons',
            'data' => [
                'octobre_ci_garcons' => 100,
                'mai_ci_garcons' => 90,
                'abandons_ci_garcons' => 15, // Différence = 10, invalide
            ],
        ]);

        $response->assertStatus(422);
    }

    #[Test]
    public function it_saves_autosave_data_correctly()
    {
        // Simuler plusieurs sauvegardes AutoSave
        $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => ['cfee_candidats_total' => 50],
        ]);

        $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => ['cfee_candidats_total' => 100],
        ]);

        $response = $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => ['cfee_candidats_total' => 120],
        ]);

        $response->assertStatus(200);
        
        // Vérifier que la dernière valeur est bien sauvegardée
        $this->assertDatabaseHas('rapport_cfee', [
            'rapport_id' => $this->rapport->id,
            'candidats_total' => 120,
        ]);
    }

    #[Test]
    public function it_persists_data_after_multiple_saves()
    {
        // Sauvegarder plusieurs sections
        $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'cfee',
            'data' => ['cfee_candidats_total' => 100],
        ]);

        $this->actingAs($this->user)->postJson('/rapport-rentree/save-section', [
            'section' => 'entree-sixieme',
            'data' => ['sixieme_candidats_total' => 50],
        ]);

        // Vérifier persistance
        $this->assertDatabaseHas('rapport_cfee', [
            'candidats_total' => 100,
        ]);

        $this->assertDatabaseHas('rapport_entree_sixieme', [
            'candidats_total' => 50,
        ]);
    }
}
