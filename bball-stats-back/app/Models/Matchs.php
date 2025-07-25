<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modèle Eloquent représentant un match de basket.
 *
 * @property int $Id_Match
 * @property string $date_match
 * @property int $numero
 * @property bool $domicile
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

    protected $fillable = ['date_match', 'numero', 'domicile', 'score_f', 'score_a', 'Id_Equipe', 'Id_Saison'];

    protected $casts = [
        'domicile' => 'boolean',
    ];

    /**
     * Récupère tous les matchs avec jointure sur saisons et équipes.
     * @return \Illuminate\Support\Collection
     */
    public static function getAllMatchs() {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.Id_Equipe', 'equipes.Id_Equipe')
                    ->get();
    }

    /**
     * Récupère un match par son id avec jointure sur saisons et équipes.
     * @param int $id
     * @return static|null
     */
    public static function getMatch($id) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.Id_Equipe', 'equipes.Id_Equipe')
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
                    ->join('equipes', 'matchs.Id_Equipe', 'equipe.Id_Equipe')
                    ->where('matchs.Id_Match', $id)
                    ->select('joueurs.*', 'jouer.*', 'matchs.*', 'equipes.nom as equipe')
                    ->get();
    }

    /**
     * Récupère les matchs d'une saison donnée.
     * @param int $id_saison
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getMatchBySaison($id_saison) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.Id_Equipe', 'equipes.Id_Equipe')
                    ->where('matchs.Id_Saison', $id_saison);
    }

    /**
     * Récupère les matchs d'une équipe donnée.
     * @param int $id_equipe
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function getMatchByEquipe($id_equipe) {
        return self::join('saisons', 'matchs.Id_Saison', 'saisons.Id_Saison')
                    ->join('equipes', 'matchs.Id_Equipe', 'equipes.Id_Equipe')
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

    public function equipe() {
        return $this->belongsTo(Equipe::class, 'Id_Equipe');
    }
    public function saison() {
        return $this->belongsTo(Saison::class, 'Id_Saison');
    }
}
