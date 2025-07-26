<?php

namespace App\Http\Controllers;

use App\Models\Joueur;
use App\Models\Matchs;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function accueil() {
        $dernierMatch = Matchs::getPreviousMatch();
        $prochainMatch = Matchs::getNextMatch();
        $dateAnniversaire = Joueur::getDateAnniversaire();
        //$dateMatch = Matchs::getAllMatchs();

        return response()->json([
            'previousGame' => $dernierMatch,
            'nextGame' => $prochainMatch,
            'birthday' => $dateAnniversaire,
           // 'matchday' => $dateMatch
        ])->header('Access-Control-Allow-Origin', '*');
    }
}
