<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class Participer extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DB::table('participer')
            ->join('saisons', 'participer.Id_Saison', '=', 'saisons.Id_Saison')
            ->join('joueurs', 'participer.licence', '=', 'joueurs.licence')
            ->select(
                'participer.Id_Saison',
                'participer.licence',
                'joueurs.nom',
                'joueurs.prenom'
            )
            ->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'Id_Saison' => 'required|exists:saisons,Id_Saison',
            'licence'   => 'required|exists:joueurs,licence',
        ]);

        DB::table('participer')->insert([
            'Id_Saison' => $request->Id_Saison,
            'licence'   => $request->licence,
        ]);

        return response()->json(['message' => 'Participation ajoutée'], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'Id_Saison' => 'required',
            'licence'   => 'required',
        ]);

        DB::table('participer')
            ->where('Id_Saison', $request->Id_Saison)
            ->where('licence', $request->licence)
            ->delete();

        return response()->json(['message' => 'Participation supprimée']);
    }
}

