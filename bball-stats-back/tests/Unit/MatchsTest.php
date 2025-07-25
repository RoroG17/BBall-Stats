<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Matchs;
use App\Models\Equipe;
use App\Models\Saison;

class MatchsTest extends TestCase
{
    use RefreshDatabase;

    public function test_creation_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $data = [
            'date_match' => '2024-05-01',
            'numero' => 1,
            'domicile' => true,
            'score_f' => 80,
            'score_a' => 75,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $match = Matchs::createMatch($data);
        $this->assertDatabaseHas('matchs', ['numero' => 1, 'score_f' => 80]);
        $this->assertEquals(1, $match->numero);
    }

    public function test_recuperation_match()
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
        $match = Matchs::createMatch($data);
        $recup = Matchs::find($match->Id_Match);
        $this->assertNotNull($recup);
        $this->assertEquals(2, $recup->numero);
    }

    public function test_existence_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $data = [
            'date_match' => '2024-05-03',
            'numero' => 3,
            'domicile' => true,
            'score_f' => 90,
            'score_a' => 85,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $match = Matchs::createMatch($data);
        $this->assertTrue(Matchs::existeMatch($match->Id_Match));
        $this->assertFalse(Matchs::existeMatch(9999));
    }

    public function test_suppression_match()
    {
        $equipe = Equipe::factory()->create();
        $saison = Saison::factory()->create();
        $data = [
            'date_match' => '2024-05-04',
            'numero' => 4,
            'domicile' => false,
            'score_f' => 50,
            'score_a' => 55,
            'Id_Equipe' => $equipe->Id_Equipe,
            'Id_Saison' => $saison->Id_Saison,
        ];
        $match = Matchs::createMatch($data);
        $match->deleteMatch();
        $this->assertDatabaseMissing('matchs', ['numero' => 4, 'score_f' => 50]);
    }
} 