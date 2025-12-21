<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Equipe;

class EquipeTest extends TestCase
{
    use RefreshDatabase;

    public function test_creation_equipe()
    {
        $data = [
            'nom' => 'Les Aigles',
            'logo' => 'logo.png',
        ];
        $equipe = Equipe::createEquipe($data);
        $this->assertDatabaseHas('equipes', ['nom' => 'Les Aigles']);
        $this->assertEquals('Les Aigles', $equipe->nom);
    }

    public function test_recuperation_equipe()
    {
        $data = [
            'nom' => 'Les Lions',
            'logo' => 'lion.png',
        ];
        $equipe = Equipe::createEquipe($data);
        $recup = Equipe::getEquipe($equipe->Id_Equipe);
        $this->assertNotNull($recup);
        $this->assertEquals('Les Lions', $recup->nom);
    }

    public function test_existence_equipe()
    {
        $data = [
            'nom' => 'Les Tigres',
            'logo' => 'tigre.png',
        ];
        $equipe = Equipe::createEquipe($data);
        $this->assertTrue(Equipe::existeEquipe($equipe->Id_Equipe));
        $this->assertFalse(Equipe::existeEquipe(9999));
    }

    public function test_suppression_equipe()
    {
        $data = [
            'nom' => 'Les Ours',
            'logo' => 'ours.png',
        ];
        $equipe = Equipe::createEquipe($data);
        $equipe->deleteEquipe();
        $this->assertDatabaseMissing('equipes', ['nom' => 'Les Ours']);
    }
} 