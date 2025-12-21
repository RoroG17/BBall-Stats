<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Equipe;

class EquipeResourceTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_equipes()
    {
        Equipe::createEquipe([
            'nom' => 'Les Aigles',
            'logo' => 'aigle.png',
        ]);
        $response = $this->getJson('/api/equipes');
        $response->assertStatus(200)
                 ->assertJsonFragment(['nom' => 'Les Aigles']);
    }

    public function test_store_equipe()
    {
        $data = [
            'nom' => 'Les Lions',
            'logo' => 'lion.png',
        ];
        $response = $this->postJson('/api/equipes', $data);
        $response->assertStatus(201)
                 ->assertJsonFragment(['nom' => 'Les Lions']);
        $this->assertDatabaseHas('equipes', ['nom' => 'Les Lions']);
    }

    public function test_show_equipe()
    {
        $equipe = Equipe::createEquipe([
            'nom' => 'Les Tigres',
            'logo' => 'tigre.png',
        ]);
        $response = $this->getJson('/api/equipes/' . $equipe->Id_Equipe);
        $response->assertStatus(200)
                 ->assertJsonFragment(['nom' => 'Les Tigres']);
    }

    public function test_update_equipe()
    {
        $equipe = Equipe::createEquipe([
            'nom' => 'Les Ours',
            'logo' => 'ours.png',
        ]);
        $update = [
            'nom' => 'Les Ours Modifiés',
            'logo' => 'ours_modif.png',
        ];
        $response = $this->putJson('/api/equipes/' . $equipe->Id_Equipe, $update);
        $response->assertStatus(200)
                 ->assertJsonFragment(['nom' => 'Les Ours Modifiés', 'logo' => 'ours_modif.png']);
        $this->assertDatabaseHas('equipes', ['nom' => 'Les Ours Modifiés', 'logo' => 'ours_modif.png']);
    }

    public function test_destroy_equipe()
    {
        $equipe = Equipe::createEquipe([
            'nom' => 'Les Panthères',
            'logo' => 'panthere.png',
        ]);
        $response = $this->deleteJson('/api/equipes/' . $equipe->Id_Equipe);
        $response->assertStatus(200)
                 ->assertJsonFragment(['message' => 'Équipe supprimée']);
        $this->assertDatabaseMissing('equipes', ['nom' => 'Les Panthères']);
    }

    public function test_show_equipe_inexistante()
    {
        $response = $this->getJson('/api/equipes/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Équipe non trouvée']);
    }

    public function test_update_equipe_inexistante()
    {
        $update = [
            'nom' => 'Test',
            'logo' => 'test.png',
        ];
        $response = $this->putJson('/api/equipes/9999', $update);
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Équipe non trouvée']);
    }

    public function test_destroy_equipe_inexistante()
    {
        $response = $this->deleteJson('/api/equipes/9999');
        $response->assertStatus(404)
                 ->assertJsonFragment(['message' => 'Équipe non trouvée']);
    }
} 