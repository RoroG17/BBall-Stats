<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Jouer extends Model
{
    use HasFactory;

    protected $table = 'jouer';
    protected $primaryKey = 'id';
    protected $keyType = 'integer';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'Id_Match',
        'licence',
        'minutes',

        'q1_passes_decisives',
        'q1_rebonds_offensifs',
        'q1_rebonds_defensifs',
        'q1_interceptions',
        'q1_contres',
        'q1_ballons_perdus',
        'q1_fautes',
        'q1_tirs_2pts_reussis',
        'q1_tirs_2pts_manques',
        'q1_tirs_3pts_reussis',
        'q1_tirs_3pts_manques',
        'q1_lancers_francs_reussis',
        'q1_lancers_francs_rates',
        'q1_passes_reussies',
        'q1_passes_rates',

        // Quart-temps 2
        'q2_passes_decisives',
        'q2_rebonds_offensifs',
        'q2_rebonds_defensifs',
        'q2_interceptions',
        'q2_contres',
        'q2_ballons_perdus',
        'q2_fautes',
        'q2_tirs_2pts_reussis',
        'q2_tirs_2pts_manques',
        'q2_tirs_3pts_reussis',
        'q2_tirs_3pts_manques',
        'q2_lancers_francs_reussis',
        'q2_lancers_francs_rates',
        'q2_passes_reussies',
        'q2_passes_rates',

        // Quart-temps 3
        'q3_passes_decisives',
        'q3_rebonds_offensifs',
        'q3_rebonds_defensifs',
        'q3_interceptions',
        'q3_contres',
        'q3_ballons_perdus',
        'q3_fautes',
        'q3_tirs_2pts_reussis',
        'q3_tirs_2pts_manques',
        'q3_tirs_3pts_reussis',
        'q3_tirs_3pts_manques',
        'q3_lancers_francs_reussis',
        'q3_lancers_francs_rates',
        'q3_passes_reussies',
        'q3_passes_rates',

        // Quart-temps 4
        'q4_passes_decisives',
        'q4_rebonds_offensifs',
        'q4_rebonds_defensifs',
        'q4_interceptions',
        'q4_contres',
        'q4_ballons_perdus',
        'q4_fautes',
        'q4_tirs_2pts_reussis',
        'q4_tirs_2pts_manques',
        'q4_tirs_3pts_reussis',
        'q4_tirs_3pts_manques',
        'q4_lancers_francs_reussis',
        'q4_lancers_francs_rates',
        'q4_passes_reussies',
        'q4_passes_rates'
    ];

    public static function getAllStats() {
        return self::join('joueurs', 'jouer.licence', 'joueurs.licence')
                    ->join('matchs', 'jouer.Id_Match', 'matchs.Id_Match')
                    ->join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
                    ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
                    ->get();
    }

    public static function getStatsSaison($id) {
        return self::join('matchs', 'jouer.Id_Match', 'matchs.Id_Match')
                    ->where('Id_Saison', $id)
                    ->get();
    }

    public static function getTotalMatchSaison($id) {
        return self::join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
        ->select(
            'matchs.numero',
            DB::raw('
                SUM(
                    (jouer.q1_tirs_2pts_reussis + jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis) * 2 +
                    (jouer.q1_tirs_3pts_reussis + jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis) * 3 +
                    (jouer.q1_lancers_francs_reussis + jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis)
                ) AS Points
            '),
            DB::raw('
                SUM(jouer.q1_passes_decisives + jouer.q2_passes_decisives + jouer.q3_passes_decisives + jouer.q4_passes_decisives) AS Passe_decisives
            '),
            DB::raw('
                SUM(jouer.q1_rebonds_offensifs + jouer.q2_rebonds_offensifs + jouer.q3_rebonds_offensifs + jouer.q4_rebonds_offensifs) AS Rebonds_offensifs
            '),
            DB::raw('
                SUM(jouer.q1_rebonds_defensifs + jouer.q2_rebonds_defensifs + jouer.q3_rebonds_defensifs + jouer.q4_rebonds_defensifs) AS Rebonds_defensifs
            '),
            DB::raw('
                SUM(jouer.q1_interceptions + jouer.q2_interceptions + jouer.q3_interceptions + jouer.q4_interceptions) AS Interceptions
            '),
            DB::raw('
                SUM(jouer.q1_contres + jouer.q2_contres + jouer.q3_contres + jouer.q4_contres) AS Contres
            '),
            DB::raw('
                SUM(jouer.q1_ballons_perdus + jouer.q2_ballons_perdus + jouer.q3_ballons_perdus + jouer.q4_ballons_perdus) AS Ballons_perdus
            '),
            DB::raw('
                SUM(jouer.q1_fautes + jouer.q2_fautes + jouer.q3_fautes + jouer.q4_fautes) AS Fautes
            ')
        )
        ->where('matchs.Id_Saison', $id)
        ->groupBy('matchs.numero')
        ->orderBy('matchs.numero')
        ->get();
    }

    public static function getClassPTS($saison = null)
    {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
            ->select(
                'joueurs.licence',
                'joueurs.nom',
                'joueurs.prenom',
                'joueurs.photo',
                DB::raw('ROUND(AVG(
                    (jouer.q1_tirs_2pts_reussis + jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis) * 2 +
                    (jouer.q1_tirs_3pts_reussis + jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis) * 3 +
                    (jouer.q1_lancers_francs_reussis + jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis))
                , 2) as valeur'),
                
                DB::raw('SUM(
                    (jouer.q1_tirs_2pts_reussis + jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis) * 2 +
                    (jouer.q1_tirs_3pts_reussis + jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis) * 3 +
                    (jouer.q1_lancers_francs_reussis + jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis))
                as total')
            )
            ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
            ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }


    public static function getClassPD($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG(jouer.q1_passes_decisives + jouer.q2_passes_decisives + jouer.q3_passes_decisives + jouer.q4_passes_decisives), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_passes_decisives + jouer.q2_passes_decisives + jouer.q3_passes_decisives + jouer.q4_passes_decisives) as total')
                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassReb($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG(jouer.q1_rebonds_offensifs + jouer.q2_rebonds_offensifs + jouer.q3_rebonds_offensifs + jouer.q4_rebonds_offensifs + jouer.q1_rebonds_defensifs + jouer.q2_rebonds_defensifs + jouer.q3_rebonds_defensifs + jouer.q4_rebonds_defensifs), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_rebonds_offensifs + jouer.q2_rebonds_offensifs + jouer.q3_rebonds_offensifs + jouer.q4_rebonds_offensifs + jouer.q1_rebonds_defensifs + jouer.q2_rebonds_defensifs + jouer.q3_rebonds_defensifs + jouer.q4_rebonds_defensifs) as total')
                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassInt($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG(jouer.q1_interceptions + jouer.q2_interceptions + jouer.q3_interceptions + jouer.q4_interceptions), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_interceptions + jouer.q2_interceptions + jouer.q3_interceptions + jouer.q4_interceptions) as total')
                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassContre($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG(jouer.q1_contres + jouer.q2_contres + jouer.q3_contres + jouer.q4_contres), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_contres + jouer.q2_contres + jouer.q3_contres + jouer.q4_contres) as total')
                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassBP($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG(jouer.q1_ballons_perdus + jouer.q2_ballons_perdus + jouer.q3_ballons_perdus + jouer.q4_ballons_perdus), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_ballons_perdus + jouer.q2_ballons_perdus + jouer.q3_ballons_perdus + jouer.q4_ballons_perdus) as total')
                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassShoot($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG((jouer.q1_tirs_2pts_reussis+ jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis) / (jouer.q1_tirs_2pts_reussis+ jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis + jouer.q1_tirs_2pts_manques + jouer.q2_tirs_2pts_manques + jouer.q3_tirs_2pts_manques + jouer.q4_tirs_2pts_manques) * 100), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_tirs_2pts_reussis+ jouer.q2_tirs_2pts_reussis + jouer.q3_tirs_2pts_reussis + jouer.q4_tirs_2pts_reussis) as total')

                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClass3Pts($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG((jouer.q1_tirs_3pts_reussis+ jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis) / (jouer.q1_tirs_3pts_reussis+ jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis + jouer.q1_tirs_3pts_manques + jouer.q2_tirs_3pts_manques + jouer.q3_tirs_3pts_manques + jouer.q4_tirs_3pts_manques) * 100), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_tirs_3pts_reussis+ jouer.q2_tirs_3pts_reussis + jouer.q3_tirs_3pts_reussis + jouer.q4_tirs_3pts_reussis) as total')

                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getClassLF($saison = null) {
        $baseQuery = self::join('joueurs', 'joueurs.licence', '=', 'jouer.licence')
                    ->select(
                        'joueurs.licence',
                        'joueurs.nom',
                        'joueurs.prenom',
                        'joueurs.photo',
                        DB::raw('ROUND(AVG((jouer.q1_lancers_francs_reussis+ jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis) / (jouer.q1_lancers_francs_reussis+ jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis + jouer.q1_lancers_francs_rates + jouer.q2_lancers_francs_rates + jouer.q3_lancers_francs_rates + jouer.q4_lancers_francs_rates) * 100), 2) as valeur'),
                        DB::raw('SUM(jouer.q1_lancers_francs_reussis+ jouer.q2_lancers_francs_reussis + jouer.q3_lancers_francs_reussis + jouer.q4_lancers_francs_reussis) as total')

                    )
                    ->groupBy('joueurs.licence', 'joueurs.nom', 'joueurs.prenom', 'joueurs.photo')
                    ->orderByDesc('valeur');

        if ($saison) {
            $baseQuery->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
                    ->where('matchs.Id_Saison', $saison);
        }

        return $baseQuery->get();
    }

    public static function getStatsMatch($id) {
        return self::join('joueurs', 'jouer.licence', 'joueurs.licence')
		    ->join('equipes', 'joueurs.Id_Equipe', 'equipes.Id_Equipe')
                    ->where('Id_Match', $id)
		    ->select('joueurs.*', 'jouer.*', 'equipes.logo', 'equipes.nom AS equipe')
                    ->get();
    }

    public static function getStatsJoueur($id) {
        return self::join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
            ->join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
            ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
	    ->join('saisons', 'matchs.Id_Saison', '=', 'saisons.Id_Saison')
            ->select(
                'jouer.*',
		'saisons.Id_Saison',
                DB::raw("CONCAT(equipeDom.nom, ' - ', equipeExt.nom) as match_libelle")
            )
            ->where('jouer.licence', $id)
	    ->orderby('matchs.date_match')
            ->get();
    }

    public static function getStatsAvg($id) {
$sub = DB::table('jouer')
    ->join('matchs', 'matchs.Id_Match', '=', 'jouer.Id_Match')
    ->where('Id_Saison', $id)
    ->selectRaw('
        matchs.Id_Match,
        matchs.Id_Saison,

        SUM(
            jouer.q1_tirs_2pts_reussis * 2 + jouer.q1_tirs_3pts_reussis * 3 + jouer.q1_lancers_francs_reussis +
            jouer.q2_tirs_2pts_reussis * 2 + jouer.q2_tirs_3pts_reussis * 3 + jouer.q2_lancers_francs_reussis +
            jouer.q3_tirs_2pts_reussis * 2 + jouer.q3_tirs_3pts_reussis * 3 + jouer.q3_lancers_francs_reussis +
            jouer.q4_tirs_2pts_reussis * 2 + jouer.q4_tirs_3pts_reussis * 3 + jouer.q4_lancers_francs_reussis
        ) AS points_par_match,

        SUM(
            jouer.q1_passes_decisives +
            jouer.q2_passes_decisives +
            jouer.q3_passes_decisives +
            jouer.q4_passes_decisives
        ) AS assists_par_match,

        SUM(
            jouer.q1_rebonds_offensifs + jouer.q1_rebonds_defensifs +
            jouer.q2_rebonds_offensifs + jouer.q2_rebonds_defensifs +
            jouer.q3_rebonds_offensifs + jouer.q3_rebonds_defensifs +
            jouer.q4_rebonds_offensifs + jouer.q4_rebonds_defensifs
        ) AS rebonds_par_match,

        SUM(
            jouer.q1_interceptions +
            jouer.q2_interceptions +
            jouer.q3_interceptions +
            jouer.q4_interceptions
        ) AS interceptions_par_match,

        SUM(
            jouer.q1_contres +
            jouer.q2_contres +
            jouer.q3_contres +
            jouer.q4_contres
        ) AS contres_par_match,

        SUM(
            jouer.q1_ballons_perdus +
            jouer.q2_ballons_perdus +
            jouer.q3_ballons_perdus +
            jouer.q4_ballons_perdus
        ) AS bp_par_match,

        SUM(
            jouer.q1_fautes +
            jouer.q2_fautes +
            jouer.q3_fautes +
            jouer.q4_fautes
        ) AS fautes_par_match
    ')
    ->groupBy('matchs.Id_Match', 'matchs.Id_Saison');

return DB::query()
    ->fromSub($sub, 't')
    ->selectRaw('
        t.Id_Saison,
        AVG(t.points_par_match) AS Points,
        AVG(t.assists_par_match) AS Assists,
        AVG(t.rebonds_par_match) AS Rebonds,
        AVG(t.interceptions_par_match) AS Interceptions,
        AVG(t.contres_par_match) AS Contres,
        AVG(t.bp_par_match) AS BP,
        AVG(t.fautes_par_match) AS Fautes
    ')
    ->groupBy('t.Id_Saison')
    ->first();
}


    public static function getStats($id) {
        return self::find($id);
    }

    public static function existeStats($id) {
        return self::where('id', $id)->exists();
    }

    public static function createStats(array $data)
    {
        return self::create($data);
    }

    public function updateStats(array $data) {
        return $this->update($data);
    }

    public function deleteStats(){
        return $this->delete();
    }

}
