<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant un match de basket.
 *
 * @property int $Id_Match
 * @property string $date_match
 * @property int $numero
 * @property int $score_f
 * @property int $score_a
 * @property int $Id_Equipe
 * @property int $Id_Saison
 *
 * @method static \Illuminate\Database\Eloquent\Collection|static[] getAllMatchs()
 * @method static static|null getMatch(int $id)
 * @method static bool existeMatch(int $id)
 * @method static \Illuminate\Database\Eloquent\Builder getMatchBySaison(int $id_saison)
 * @method static \Illuminate\Database\Eloquent\Builder getMatchByEquipe(int $id_equipe)
 * @method static static createMatch(array|object $data)
 */
class Matchs extends Model
{
    use HasFactory;

    protected $table = 'matchs';
    protected $primaryKey = 'Id_Match';
    protected $keyType = 'integer';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['date_match', 'numero', 'equipe_domicile', 'equipe_exterieur', 'score_domicile', 'score_exterieur', 'Id_Saison'];

    /**
     * Récupère tous les matchs avec jointure sur saisons et équipes.
     * @return \Illuminate\Support\Collection
     */
    public static function getAllMatchs() {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
                    ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
                    ->select(
                        'matchs.Id_Match as idMatch',
                        'matchs.numero',
                        'matchs.date_match as dateMatch',
                        'equipeDom.nom as equipeDom',
                        'equipeExt.nom as equipeExt',
                        'matchs.score_domicile as scoreDom',
                        'matchs.score_exterieur as scoreExt',
                        'equipeDom.logo as logoDom',
                        'equipeExt.logo as logoExt'
                    )
                    ->get();
    }

    /**
     * Récupère un match par son id avec jointure sur saisons et équipes.
     * @param int $id
     * @return static|null
     */
    public static function getMatch($id) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.equipe_domicile', 'equipes.Id_Equipe')
                    ->join('equipes', 'matchs.equipe_exterieur', 'equipes.Id_Equipe')
                    ->find($id);
    } 

    /**
     * Vérifie si un match existe par son id.
     * @param int $id
     * @return bool
     */
    public static function existeMatch($id) {
        return self::where('Id_Match', $id)->exists();
    }

    public static function getStatsMatch($id) {
        return self::join('jouer', 'matchs.Id_Match', 'jouer.Id_Match')
                    ->join('joueurs', 'jouer.licence', 'joueurs.licence')
                    ->join('equipes', 'matchs.equipe_domicile', 'equipes.Id_Equipe')
                    ->join('equipes', 'matchs.equipe_exterieur', 'equipes.Id_Equipe')
                    ->where('matchs.Id_Match', $id)
                    ->select('joueurs.*', 'jouer.*', 'matchs.*', 'equipes.nom as equipe')
                    ->get();
    }

    public static function getNextMatch() {
        return self::join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
                    ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
                    ->select(
                        'matchs.Id_Match as idMatch',
                        'matchs.numero',
                        'matchs.date_match as dateMatch',
                        'equipeDom.nom as equipeDom',
                        'equipeExt.nom as equipeExt',
                        'matchs.score_domicile as scoreDom',
                        'matchs.score_exterieur as scoreExt',
                        'equipeDom.logo as logoDom',
                        'equipeExt.logo as logoExt'
                    )
                    ->where('matchs.date_match', '>=', Carbon::now())
                    ->orderBy('matchs.date_match')
                    ->first();
    }


    public static function getPreviousMatch() {
        return self::join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
                    ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
                    ->select(
                        'matchs.Id_Match as idMatch',
                        'matchs.numero',
                        'matchs.date_match as dateMatch',
                        'equipeDom.nom as equipeDom',
                        'equipeExt.nom as equipeExt',
                        'matchs.score_domicile as scoreDom',
                        'matchs.score_exterieur as scoreExt',
                        'equipeDom.logo as logoDom',
                        'equipeExt.logo as logoExt'
                        )
                    ->where('date_match', '<', Carbon::now())
                    ->orderByDESC('date_match')
                    ->first();
    }

    /**
     * Récupère les matchs d'une saison donnée.
     * @param int $id_saison
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getMatchBySaison($id_saison) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.equipe_domicile', 'equipes.Id_Equipe')
                    ->join('equipes', 'matchs.equipe_exterieur', 'equipes.Id_Equipe')
                    ->where('matchs.Id_Saison', $id_saison);
    }

    /**
     * Récupère les matchs d'une équipe donnée.
     * @param int $id_equipe
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getMatchByEquipe($id_equipe) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.equipe_domicile', 'equipes.Id_Equipe')
                    ->join('equipes', 'matchs.equipe_exterieur', 'equipes.Id_Equipe')
                    ->where('matchs.Id_Equipe', $id_equipe);
    }

    /**
     * Crée un nouveau match.
     * @param array|object $data
     * @return static
     */
    public static function createMatch($data) {
        return self::create([
            'date_match' => $data['date_match'] ?? $data->date_match,
            'numero' => $data['numero'] ?? $data->numero,
            'domicile' => $data['domicile'] ?? $data->domicile,
            'score_f' => $data['score_f'] ?? $data->score_f,
            'score_a' => $data['score_a'] ?? $data->score_a,
            'Id_Equipe' => $data['Id_Equipe'] ?? $data->Id_Equipe,
            'Id_Saison' => $data['Id_Saison'] ?? $data->Id_Saison,
        ]);
    }

    /**
     * Met à jour les informations du match.
     * @param array|object $data
     * @return bool
     */
    public function updateMatch($data) {
        $this->date_match = $data['date_match'] ?? $data->date_match;
        $this->numero = $data['numero'] ?? $data->numero;
        $this->domicile = $data['domicile'] ?? $data->domicile;
        $this->score_f = $data['score_f'] ?? $data->score_f;
        $this->score_a = $data['score_a'] ?? $data->score_a;
        $this->Id_Equipe = $data['Id_Equipe'] ?? $data->Id_Equipe;
        $this->Id_Saison = $data['Id_Saison'] ?? $data->Id_Saison;

        return $this->save();
    }

    /**
     * Supprime le match.
     * @return bool|null
     */
    public function deleteMatch() {
        return $this->delete();
    }

    public static function rechercheMatch($data) {
        $query = self::join('saisons', 'saisons.Id_Saison', 'matchs.Id_Saison')
                        ->join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
                        ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe');

        if ($data['annees'] != "") {

        }

        if ($data['championnat'] != "") {
            $query->where('chamionnat', $data['championnat']);
        }

        if ($data['categorie'] != "") {
            $query->where('categorie', $data['categorie']);
        }

        return $query->select(
                        'matchs.Id_Match as idMatch',
                        'matchs.numero',
                        'matchs.date_match as dateMatch',
                        'equipeDom.nom as equipeDom',
                        'equipeExt.nom as equipeExt',
                        'matchs.score_domicile as scoreDom',
                        'matchs.score_exterieur as scoreExt',
                        'equipeDom.logo as logoDom',
                        'equipeExt.logo as logoExt'
                    )
                    ->orderBy('date_match')
                    ->get();
    }
}
