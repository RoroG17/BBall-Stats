<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Matchs;
use App\Models\Equipe;
use App\Models\Saison;

class MatchResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_matchs()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        Matchs::createMatch([
            'date_match' => '2024-05-01',
            'numero' => 1,
            'domicile' => true,
            'score_f' => 80,
            'score_a' => 75,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $response = $this->getJson('/api/matchs');
        $response->assertStatus(200)
                 ->assertJsonFragment(['numero' => 1, 'score_f' => 80]);
    }

    public function test_store_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $data = [
            'date_match' => '2024-05-02',
            'numero' => 2,
            'domicile' => false,
            'score_f' => 60,
            'score_a' => 70,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $response = $this->postJson('/api/matchs', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['numero' => 2, 'score_f' => 60]);
        $this->assertDatabaseHas('matchs', ['numero' => 2, 'score_f' => 60]);
    }

    public function test_store_match_equipe_inexistante()
    {
        $saison = Saison::factory()->create();
        $data = [
            'date_match' => '2024-05-03',
            'numero' => 3,
            'domicile' => true,
            'score_f' => 90,
            'score_a' => 85,
            'Id_Equipe' => 9999,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $response = $this->postJson('/api/matchs', $data);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => "L'équipe n'existe pas"]);
    }

    public function test_store_match_saison_inexistante()
    {
        $equipe = Equipe::factory()->create();
        $data = [
            'date_match' => '2024-05-04',
            'numero' => 4,
            'domicile' => false,
            'score_f' => 50,
            'score_a' => 55,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => 9999,
        ];
        $response = $this->postJson('/api/matchs', $data);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => "La saison n'existe pas"]);
    }

    public function test_show_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $match = Matchs::createMatch([
            'date_match' => '2024-05-05',
            'numero' => 5,
            'domicile' => true,
            'score_f' => 100,
            'score_a' => 90,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $response = $this->getJson('/api/matchs/' . $match->Id_Match);
        $response->assertStatus(200)
                 ->assertJsonFragment(['numero' => 5, 'score_f' => 100]);
    }

    public function test_update_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $match = Matchs::createMatch([
            'date_match' => '2024-05-06',
            'numero' => 6,
            'domicile' => false,
            'score_f' => 70,
            'score_a' => 60,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $update = [
            'date_match' => '2024-05-06',
            'numero' => 6,
            'domicile' => false,
            'score_f' => 75,
            'score_a' => 65,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $response = $this->putJson('/api/matchs/' . $match->Id_Match, $update);
        $response->assertStatus(200)
                 ->assertJsonFragment(['score_f' => 75, 'score_a' => 65]);
        $this->assertDatabaseHas('matchs', ['numero' => 6, 'score_f' => 75, 'score_a' => 65]);
    }

    public function test_update_match_equipe_inexistante()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $match = Matchs::createMatch([
            'date_match' => '2024-05-07',
            'numero' => 7,
            'domicile' => true,
            'score_f' => 80,
            'score_a' => 70,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $update = [
            'Id_Equipe' => 9999,
        ];
        $response = $this->putJson('/api/matchs/' . $match->Id_Match, $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => "L'équipe n'existe pas"]);
    }

    public function test_update_match_saison_inexistante()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $match = Matchs::createMatch([
            'date_match' => '2024-05-08',
            'numero' => 8,
            'domicile' => false,
            'score_f' => 60,
            'score_a' => 50,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $update = [
            'Id_Saison' => 9999,
        ];
        $response = $this->putJson('/api/matchs/' . $match->Id_Match, $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => "La saison n'existe pas"]);
    }

    public function test_destroy_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $match = Matchs::createMatch([
            'date_match' => '2024-05-09',
            'numero' => 9,
            'domicile' => true,
            'score_f' => 110,
            'score_a' => 100,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ]);
        $response = $this->deleteJson('/api/matchs/' . $match->Id_Match);
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Match supprimé']);
        $this->assertDatabaseMissing('matchs', ['numero' => 9, 'score_f' => 110]);
    }

    public function test_show_match_inexistant()
    {
        $response = $this->getJson('/api/matchs/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Match non trouvé']);
    }

    public function test_update_match_inexistant()
    {
        $update = [
            'score_f' => 99,
        ];
        $response = $this->putJson('/api/matchs/9999', $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Match non trouvé']);
    }

    public function test_destroy_match_inexistant()
    {
        $response = $this->deleteJson('/api/matchs/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Match non trouvé']);
    }
} 