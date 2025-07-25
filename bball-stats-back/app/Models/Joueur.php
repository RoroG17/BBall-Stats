<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant un joueur de basket.
 *
 * @property string $licence
 * @property string $nom
 * @property string $prenom
 * @property string $civilite
 * @property string $date_naissance
 * @property string|null $photo
 *
 * @method static \Illuminate\Database\Eloquent\Collection|static[] getAllJoueurs()
 * @method static static|null getJoueur(string $licence)
 * @method static bool existeJoueur(string $licence)
 * @method static static createJoueur(array|object $data)
 */
class Joueur extends Model
{
    use HasFactory;
    
    protected $table = 'joueurs';
    protected $primaryKey = 'licence';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['licence', 'nom', 'prenom', 'civilite', 'date_naissance', 'photo'];

    /**
     * Récupère tous les joueurs.
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public static function getAllJoueurs() {
        return self::all();
    }

    /**
     * Récupère un joueur par sa licence.
     *
     * @param string $licence
     * @return static|null
     */
    public static function getJoueur($licence) {
        return self::find($licence);
    }

    /**
     * Vérifie si un joueur existe par sa licence.
     *
     * @param string $licence
     * @return bool
     */
    public static function existeJoueur($licence) {
        return self::where('licence', $licence)->exists();
    }

    public static function getStatsJoueur($licence) {
        return self::join('jouer', 'joueurs.licence', 'jouer.licence')
                    ->join('matchs', 'matchs.Id_Match', 'jouer.Id_Match')
                    ->join('equipes', 'matchs.Id_Equipe', 'equipe.Id_Equipe')
                    ->where('joueurs.licence', $licence)
                    ->select('joueurs.*', 'jouer.*', 'matchs.*', 'equipes.nom as equipe')
                    ->get();
    }

    /**
     * Crée un nouveau joueur.
     *
     * @param array|object $data
     * @return static
     */
    public static function createJoueur($data) {
        return self::create([
            'licence' => $data['licence'] ?? $data->licence,
            'nom' => $data['nom'] ?? $data->nom,
            'prenom' => $data['prenom'] ?? $data->prenom,
            'civilite' => $data['civilite'] ?? $data->civilite,
            'date_naissance' => $data['date_naissance'] ?? $data->date_naissance,
            'photo' => $data['photo'] ?? $data->photo ?? null,
        ]);
    }

    /**
     * Met à jour les informations du joueur.
     *
     * @param array|object $data
     * @return bool
     */
    public function updateJoueur($data) {
        $this->nom = $data['nom'] ?? $data->nom;
        $this->prenom = $data['prenom'] ?? $data->prenom;
        $this->civilite = $data['civilite'] ?? $data->civilite;
        $this->date_naissance = $data['date_naissance'] ?? $data->date_naissance;
        $this->photo = $data['photo'] ?? $data->photo ?? null;

        return $this->save();
    }

    /**
     * Supprime le joueur.
     *
     * @return bool|null
     */
    public function deleteJoueur() {
        return $this->delete();
    }

    /**
     * Récupère la liste des joueurs avec la date de leur prochain anniversaire (dans l'année en cours ou la suivante).
     *
     * @return \Illuminate\Support\Collection Liste des joueurs avec les champs licence, nom, prenom, et date_anniversaire (objet Carbon)
     */
    public static function getDateAnniversaire () {
        $anniversaire = self::select('licence', 'nom', 'prenom', 'date_naissance AS date_anniversaire')
                                ->get();
        
        foreach ($anniversaire as $a) {
            $date = Carbon::parse($a->date_anniversaire);
            $now = Carbon::now();
            $date->setYear($now->year);
            if ($date < $now) {
                $date->addYear(1);
            }

            $a->date_anniversaire = $date->format('Y-m-d');
        }

        return $anniversaire;
    }
}
