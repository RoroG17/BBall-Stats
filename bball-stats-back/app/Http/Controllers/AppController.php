<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Matchs;
use App\Models\Jouer;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function accueil() {
        $dernierMatch = Matchs::getPreviousMatch();
        $prochainMatch = Matchs::getNextMatch();
        $dateAnniversaire = Joueur::getDateAnniversaire();
        $dateMatch = Matchs::getAllMatchs();

        return response()->json([
            'previousGame' => $dernierMatch,
            'nextGame' => $prochainMatch,
            'birthday' => $dateAnniversaire,
            'matchday' => $dateMatch
        ])->header('Access-Control-Allow-Origin', '*');
    }

    public function rechercheMatch(Request $request) {
        $matchs = Matchs::rechercheMatch($request->filtre);

        return response()->json($matchs)->header('Access-Control-Allow-Origin', '*');
    }

    public function rechercheJoueur(Request $request) {
        $joueurs = Joueur::rechercheJoueur($request->filtre);

        return response()->json($joueurs)->header('Access-Control-Allow-Origin', '*');
    }

    public function addStats(Request $request) {
        $stats = Jouer::createStats($request->all());

        return response()->json($stats)->header('Access-Control-Allow-Origin', '*');
    }

    public function getStatsEquipe($saison) {
        $stats = Jouer::getStatsSaison($saison);
        $total = Jouer::getTotalMatchSaison($saison);

        $classPTSActuelle = Jouer::getClassPTS($saison);
        $classPDActuelle = Jouer::getClassPD($saison);
        $classRebActuelle = Jouer::getClassReb($saison);
        $classIntActuelle = Jouer::getClassInt($saison);
        $classContreActuelle = Jouer::getClassContre($saison);
        $classBPActuelle = Jouer::getClassBP($saison);

        $classShootActuelle = Jouer::getClassShoot($saison);
        $class3PtsActuelle = Jouer::getClass3Pts($saison);
        $classLFActuelle = Jouer::getClassLF($saison);

        $classPTS = Jouer::getClassPTS();
        $classPD = Jouer::getClassPD();
        $classReb = Jouer::getClassReb();
        $classInt = Jouer::getClassInt();
        $classContre = Jouer::getClassContre();
        $classBP = Jouer::getClassBP();

        $classShoot = Jouer::getClassShoot();
        $class3Pts = Jouer::getClass3Pts();
        $classLF = Jouer::getClassLF();

        return response()->json([
                'stats' => $stats, 
                'total' => $total,

                'classPTSActuelle' => $classPTSActuelle,
                'classPDActuelle' => $classPDActuelle,
                'classRebActuelle' => $classRebActuelle,
                'classIntActuelle' => $classIntActuelle,
                'classContreActuelle' => $classContreActuelle,
                'classBPActuelle' => $classBPActuelle,
                
                'classShootActuelle' => $classShootActuelle,
                'class3PtsActuelle' => $class3PtsActuelle,
                'classLFActuelle' => $classLFActuelle,

                'classPTS' => $classPTS,
                'classPD' => $classPD,
                'classReb' => $classReb,
                'classInt' => $classInt,
                'classContre' => $classContre,
                'classBP' => $classBP,

                'classShoot' => $classShoot,
                'class3Pts' => $class3Pts,
                'classLF' => $classLF,
            ])->header('Access-Control-Allow-Origin', '*'); 
    }
}
