<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function connect(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'nullable|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        // 1) Si l’utilisateur n’a PAS encore de mot de passe
        if ($user->password === null) {
            return response()->json(['message' => 'Mot de passe à initialiser'], 201);
        }

        // 2) Si l’utilisateur a un mot de passe mais que l’utilisateur n'en a pas envoyé
        if (!$request->filled('password')) {
            return response()->json(['message' => 'Mot de passe requis'], 400);
        }

        // 3) Vérification du mot de passe
        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Mot de passe incorrect'], 401);
        }

        // 4) Connexion OK
        return response()->json(['message' => 'Connexion réussie'], 200);
    }


    public function updatePassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string',
        ]);
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->password = $request->password;
        $user->save();
        return response()->json(['message' => 'Mot de passe mis à jour'], 200);
    }

    public function disconnect(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Déconnexion réussie'], 200);
    }
}
