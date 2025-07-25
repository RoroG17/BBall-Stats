<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant une saison de basket.
 *
 * @property int $Id_Saison
 * @property int $annee_debut
 * @property int $annee_fin
 * @property string $championnat
 * @property string $categorie
 *
 * @method static \Illuminate\Database\Eloquent\Collection|static[] getAllSaison()
 * @method static static|null getSaison(int $id)
 * @method static bool existeSaison(int $id)
 * @method static static createSaison(array|object $data)
 */
class Saison extends Model
{
    use HasFactory;

    protected $table = "saisons";
    protected $primaryKey = "Id_Saison";
    protected $keyType = "integer";
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['annee_debut', 'annee_fin', 'championnat', 'categorie'];

    /**
     * Récupère toutes les saisons.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllSaison() {
        return self::all();
    }

    /**
     * Récupère une saison par son id.
     *
     * @param int $id
     * @return static|null
     */
    public static function getSaison($id) {
        return self::find($id);
    } 

    /**
     * Vérifie si une saison existe par son id.
     *
     * @param int $id
     * @return bool
     */
    public static function existeSaison($id) {
        return self::where('Id_Saison', $id)->exists();
    }

    /**
     * Crée une nouvelle saison.
     *
     * @param array|object $data
     * @return static
     */
    public static function createSaison($data) {
        return self::create([
            'annee_debut' => $data['annee_debut'] ?? $data->annee_debut,
            'annee_fin' => $data['annee_fin'] ?? $data->annee_fin,
            'championnat' => $data['championnat'] ?? $data->championnat,
            'categorie' => $data['categorie'] ?? $data->categorie,
        ]);
    }

    /**
     * Met à jour les informations de la saison.
     *
     * @param array|object $data
     * @return bool
     */
    public function updateSaison($data) {
        $this->annee_debut = $data['annee_debut'] ?? $data->annee_debut;
        $this->annee_fin = $data['annee_fin'] ?? $data->annee_fin;
        $this->championnat = $data['championnat'] ?? $data->championnat;
        $this->categorie = $data['categorie'] ?? $data->categorie;

        return $this->save();
    }

    /**
     * Supprime la saison.
     *
     * @return bool|null
     */
    public function deleteSaison() {
        return $this->delete();
    }
}
