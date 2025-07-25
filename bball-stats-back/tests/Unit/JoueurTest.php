<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Joueur;

class JoueurTest extends TestCase
{
    use RefreshDatabase;

    public function test_creation_joueur()
    {
        $data = [
            'licence' => 'LIC123',
            'nom' => 'Dupont',
            'prenom' => 'Jean',
            'civilite' => 'M',
            'date_naissance' => '2000-01-01',
            'photo' => null,
        ];
        $joueur = Joueur::createJoueur($data);
        $this->assertDatabaseHas('joueurs', ['licence' => 'LIC123']);
        $this->assertEquals('Dupont', $joueur->nom);
    }

    public function test_recuperation_joueur()
    {
        $data = [
            'licence' => 'LIC124',
            'nom' => 'Martin',
            'prenom' => 'Paul',
            'civilite' => 'M',
            'date_naissance' => '1999-05-10',
            'photo' => null,
        ];
        Joueur::createJoueur($data);
        $joueur = Joueur::getJoueur('LIC124');
        $this->assertNotNull($joueur);
        $this->assertEquals('Martin', $joueur->nom);
    }

    public function test_existence_joueur()
    {
        $data = [
            'licence' => 'LIC125',
            'nom' => 'Durand',
            'prenom' => 'Luc',
            'civilite' => 'M',
            'date_naissance' => '1998-03-15',
            'photo' => null,
        ];
        Joueur::createJoueur($data);
        $this->assertTrue(Joueur::existeJoueur('LIC125'));
        $this->assertFalse(Joueur::existeJoueur('LIC999'));
    }

    public function test_suppression_joueur()
    {
        $data = [
            'licence' => 'LIC126',
            'nom' => 'Petit',
            'prenom' => 'Marc',
            'civilite' => 'M',
            'date_naissance' => '2001-07-21',
            'photo' => null,
        ];
        $joueur = Joueur::createJoueur($data);
        $joueur->deleteJoueur();
        $this->assertDatabaseMissing('joueurs', ['licence' => 'LIC126']);
    }

    public function test_get_date_anniversaire()
    {
        // Création de joueurs avec différentes dates de naissance
        Joueur::createJoueur([
            'licence' => 'LIC200',
            'nom' => 'Anniv1',
            'prenom' => 'Test',
            'civilite' => 'M',
            'date_naissance' => '2000-01-01',
            'photo' => null,
        ]);
        Joueur::createJoueur([
            'licence' => 'LIC201',
            'nom' => 'Anniv2',
            'prenom' => 'Test',
            'civilite' => 'F',
            'date_naissance' => '2000-12-31',
            'photo' => null,
        ]);

        $result = Joueur::getDateAnniversaire();
        $this->assertNotEmpty($result);
        $this->assertTrue($result->contains('licence', 'LIC200'));
        $this->assertTrue($result->contains('licence', 'LIC201'));
        // Vérifie que le champ date_anniversaire est bien un objet Carbon et dans le futur
        foreach ($result as $joueur) {
            $this->assertInstanceOf(\Carbon\Carbon::class, $joueur->date_anniversaire);
            $this->assertTrue($joueur->date_anniversaire->greaterThanOrEqualTo(now()));
        }
    }
} 