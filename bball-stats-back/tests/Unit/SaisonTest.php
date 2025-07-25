<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Saison;

class SaisonTest extends TestCase
{
    use RefreshDatabase;

    public function test_creation_saison()
    {
        $data = [
            'annee_debut' => 2022,
            'annee_fin' => 2023,
            'championnat' => 'National 1',
            'categorie' => 'U18',
        ];
        $saison = Saison::createSaison($data);
        $this->assertDatabaseHas('saisons', ['annee_debut' => 2022, 'annee_fin' => 2023]);
        $this->assertEquals('National 1', $saison->championnat);
    }

    public function test_recuperation_saison()
    {
        $data = [
            'annee_debut' => 2020,
            'annee_fin' => 2021,
            'championnat' => 'Régional',
            'categorie' => 'U15',
        ];
        $saison = Saison::createSaison($data);
        $recup = Saison::getSaison($saison->Id_Saison);
        $this->assertNotNull($recup);
        $this->assertEquals('Régional', $recup->championnat);
    }

    public function test_existence_saison()
    {
        $data = [
            'annee_debut' => 2018,
            'annee_fin' => 2019,
            'championnat' => 'Départemental',
            'categorie' => 'U13',
        ];
        $saison = Saison::createSaison($data);
        $this->assertTrue(Saison::existeSaison($saison->Id_Saison));
        $this->assertFalse(Saison::existeSaison(9999));
    }

    public function test_suppression_saison()
    {
        $data = [
            'annee_debut' => 2016,
            'annee_fin' => 2017,
            'championnat' => 'Départemental',
            'categorie' => 'U11',
        ];
        $saison = Saison::createSaison($data);
        $saison->deleteSaison();
        $this->assertDatabaseMissing('saisons', ['annee_debut' => 2016, 'annee_fin' => 2017]);
    }
} 