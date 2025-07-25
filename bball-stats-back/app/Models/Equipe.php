<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant une équipe de basket.
 *
 * @property int $Id_Equipe
 * @property string $nom
 * @property string|null $logo
 *
 * @method static \Illuminate\Database\Eloquent\Collection|static[] getAllEquipe()
 * @method static static|null getEquipe(int $id)
 * @method static bool existeEquipe(int $id)
 * @method static static createEquipe(array|object $data)
 */
class Equipe extends Model
{
    use HasFactory;

    protected $table = 'equipes';
    protected $primaryKey = 'Id_Equipe'; // Attention à la casse, doit correspondre à la BDD
    protected $keyType = 'int';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['nom', 'logo'];

    /**
     * Récupère toutes les équipes.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllEquipe() {
        return self::all();
    }

    /**
     * Récupère une équipe par son id.
     *
     * @param int $id
     * @return static|null
     */
    public static function getEquipe($id) {
        return self::find($id);
    }

    /**
     * Vérifie si une équipe existe par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function existeEquipe($id) {
        return self::where('Id_Equipe', $id)->exists();
    }

    /**
     * Crée une nouvelle équipe.
     *
     * @param array|object $data
     * @return static
     */
    public static function createEquipe($data) {
        return self::create([
            'nom' => is_array($data) ? ($data['nom'] ?? null) : ($data->nom ?? null),
            'logo' => is_array($data) ? ($data['logo'] ?? null) : ($data->logo ?? null),
        ]);
    }

    /**
     * Met à jour les informations de l'équipe.
     *
     * @param array|object $data
     * @return bool
     */
    public function updateEquipe($data) {
        $this->nom = is_array($data) ? ($data['nom'] ?? $this->nom) : ($data->nom ?? $this->nom);
        $this->logo = is_array($data) ? ($data['logo'] ?? $this->logo) : ($data->logo ?? $this->logo);
        return $this->save();
    }

    /**
     * Supprime l'équipe.
     *
     * @return bool|null
     */
    public function deleteEquipe() {
        return $this->delete();
    }
}
