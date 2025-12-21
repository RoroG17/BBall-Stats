<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Joueur;

class JoueurResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_joueurs()
    {
        Joueur::createJoueur([
            'licence' => 'LIC200',
            'nom' => 'Test1',
            'prenom' => 'Alpha',
            'civilite' => 'M',
            'date_naissance' => '2000-01-01',
            'photo' => null,
        ]);
        $response = $this->getJson('/api/joueurs');
        $response->assertStatus(200)
                 ->assertJsonFragment(['licence' => 'LIC200']);
    }

    public function test_store_joueur()
    {
        $data = [
            'licence' => 'LIC201',
            'nom' => 'Test2',
            'prenom' => 'Beta',
            'civilite' => 'F',
            'date_naissance' => '2001-02-02',
            'photo' => null,
        ];
        $response = $this->postJson('/api/joueurs', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['licence' => 'LIC201']);
        $this->assertDatabaseHas('joueurs', ['licence' => 'LIC201']);
    }

    public function test_show_joueur()
    {
        Joueur::createJoueur([
            'licence' => 'LIC202',
            'nom' => 'Test3',
            'prenom' => 'Gamma',
            'civilite' => 'M',
            'date_naissance' => '2002-03-03',
            'photo' => null,
        ]);
        $response = $this->getJson('/api/joueurs/LIC202');
        $response->assertStatus(200)
                 ->assertJsonFragment(['licence' => 'LIC202']);
    }

    public function test_show_joueur_inexistant()
    {
        $response = $this->getJson('/api/joueurs/INEXISTANT');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Joueur non trouvé']);
    }

    public function test_update_joueur()
    {
        Joueur::createJoueur([
            'licence' => 'LIC203',
            'nom' => 'Test4',
            'prenom' => 'Delta',
            'civilite' => 'F',
            'date_naissance' => '2003-04-04',
            'photo' => null,
        ]);
        $update = [
            'nom' => 'Test4Modif',
            'prenom' => 'DeltaModif',
            'civilite' => 'M',
            'date_naissance' => '2010-10-10',
            'photo' => 'nouvelle_photo.jpg',
        ];
        $response = $this->putJson('/api/joueurs/LIC203', $update);
        $response->assertStatus(200)
                 ->assertJsonFragment([
                    'nom' => 'Test4Modif',
                    'prenom' => 'DeltaModif',
                    'civilite' => 'M',
                    'date_naissance' => '2010-10-10',
                    'photo' => 'nouvelle_photo.jpg',
                 ]);
        $this->assertDatabaseHas('joueurs', array_merge(['licence' => 'LIC203'], $update));
    }

    public function test_update_joueur_inexistant()
    {
        $update = [
            'nom' => 'Test',
            'prenom' => 'Test',
            'civilite' => 'M',
            'date_naissance' => '2010-10-10',
            'photo' => 'photo.jpg',
        ];
        $response = $this->putJson('/api/joueurs/INEXISTANT', $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Joueur non trouvé']);
    }

    public function test_destroy_joueur()
    {
        Joueur::createJoueur([
            'licence' => 'LIC204',
            'nom' => 'Test5',
            'prenom' => 'Epsilon',
            'civilite' => 'M',
            'date_naissance' => '2004-05-05',
            'photo' => null,
        ]);
        $response = $this->deleteJson('/api/joueurs/LIC204');
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Joueur supprimé']);
        $this->assertDatabaseMissing('joueurs', ['licence' => 'LIC204']);
    }

    public function test_destroy_joueur_inexistant()
    {
        $response = $this->deleteJson('/api/joueurs/INEXISTANT');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Joueur non trouvé']);
    }
} 