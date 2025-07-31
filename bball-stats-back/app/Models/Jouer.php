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

    public static function getStatsMatch($id) {
        return self::join('joueurs', 'jouer.licence', 'joueurs.licence')
                    ->where('Id_Match', $id)
                    ->get();
    }

    public static function getStatsJoueur($id) {
        return self::join('joueurs', 'jouer.licence', '=', 'joueurs.licence')
            ->join('matchs', 'jouer.Id_Match', '=', 'matchs.Id_Match')
            ->join('equipes as equipeDom', 'matchs.equipe_domicile', '=', 'equipeDom.Id_Equipe')
            ->join('equipes as equipeExt', 'matchs.equipe_exterieur', '=', 'equipeExt.Id_Equipe')
            ->select(
                'jouer.*',
                //DB::raw("CONCAT(equipeDom.nom, ' - ', equipeExt.nom) as match")
                DB::raw("equipeDom.nom || ' - ' || equipeExt.nom as match")
            )
            ->where('joueurs.licence', $id)
            ->get();
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
