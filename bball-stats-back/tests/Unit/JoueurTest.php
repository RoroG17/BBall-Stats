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
} 