<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Saison;

class SaisonResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_saisons()
    {
        Saison::createSaison([
            'annee_debut' => 2020,
            'annee_fin' => 2021,
            'championnat' => 'National 2',
            'categorie' => 'U20',
        ]);
        $response = $this->getJson('/api/saisons');
        $response->assertStatus(200)
                 ->assertJsonFragment(['annee_debut' => 2020, 'annee_fin' => 2021]);
    }

    public function test_store_saison()
    {
        $data = [
            'annee_debut' => 2022,
            'annee_fin' => 2023,
            'championnat' => 'National 1',
            'categorie' => 'U18',
        ];
        $response = $this->postJson('/api/saisons', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['annee_debut' => 2022, 'annee_fin' => 2023]);
        $this->assertDatabaseHas('saisons', ['annee_debut' => 2022, 'annee_fin' => 2023]);
    }

    public function test_show_saison()
    {
        $saison = Saison::createSaison([
            'annee_debut' => 2018,
            'annee_fin' => 2019,
            'championnat' => 'Départemental',
            'categorie' => 'U13',
        ]);
        $response = $this->getJson('/api/saisons/' . $saison->Id_Saison);
        $response->assertStatus(200)
                 ->assertJsonFragment(['annee_debut' => 2018, 'annee_fin' => 2019]);
    }

    public function test_update_saison()
    {
        $saison = Saison::createSaison([
            'annee_debut' => 2016,
            'annee_fin' => 2017,
            'championnat' => 'Départemental',
            'categorie' => 'U11',
        ]);
        $update = [
            'annee_debut' => 2017,
            'annee_fin' => 2018,
            'championnat' => 'Régional',
            'categorie' => 'U15',
        ];
        $response = $this->putJson('/api/saisons/' . $saison->Id_Saison, $update);
        $response->assertStatus(200)
                 ->assertJsonFragment(['annee_debut' => 2017, 'annee_fin' => 2018, 'championnat' => 'Régional', 'categorie' => 'U15']);
        $this->assertDatabaseHas('saisons', $update);
    }

    public function test_destroy_saison()
    {
        $saison = Saison::createSaison([
            'annee_debut' => 2014,
            'annee_fin' => 2015,
            'championnat' => 'Départemental',
            'categorie' => 'U9',
        ]);
        $response = $this->deleteJson('/api/saisons/' . $saison->Id_Saison);
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Saison supprimée']);
        $this->assertDatabaseMissing('saisons', ['annee_debut' => 2014, 'annee_fin' => 2015]);
    }

    public function test_show_saison_inexistante()
    {
        $response = $this->getJson('/api/saisons/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Saison non trouvée']);
    }

    public function test_update_saison_inexistante()
    {
        $update = [
            'annee_debut' => 2030,
            'annee_fin' => 2031,
            'championnat' => 'Futur',
            'categorie' => 'U99',
        ];
        $response = $this->putJson('/api/saisons/9999', $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Saison non trouvée']);
    }

    public function test_destroy_saison_inexistante()
    {
        $response = $this->deleteJson('/api/saisons/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Saison non trouvée']);
    }
} 