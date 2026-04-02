<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class AdminWalletController extends Controller
{
    public function credit(Request $request, $userId){

        $validated = $request->validate([
            'montant' => 'required|integer|min:1', //le montant doit etre strictement positive
        ]);

        $user = User::find($userId); //valeur {user} dans l'URl
        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        //ajoute des points aux comptes 
        $user->solde += $validated['montant'];
        $user->save();

        return response()->json([
            'data'    => [
                'user'          => $user->name,
                'nouveau_solde' => $user->solde,
            ],
            'message' => 'Points crédités avec succès',
        ], 200);
    }

    public function debit(Request $request, $userId){
        $validated = $request->validate([
            'montant' => 'required|integer|min:1',
        ]);

        $user = User::find($userId);
        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // verif le solde 
        if ($user->solde < $validated['montant']) {
            return response()->json([
                'message' => 'Solde insuffisant pour ce débit',
            ], 422);
        }

        //retirer des points du compte 
        $user->solde -= $validated['montant'];
        $user->save();

        return response()->json([
            'data'    => [
                'user'          => $user->name,
                'nouveau_solde' => $user->solde,
            ],
            'message' => 'Points débités avec succès',
        ], 200);
    }
}
