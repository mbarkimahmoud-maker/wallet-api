<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public  function balance(){
        $user=auth('api')->user();

        return response()->json([
            'data'    => [
                'solde' => $user->solde,
            ],
            'message' => 'Balance retrieved successfully',
        ], 200);
    }

    public function spend(Request $request){
        $validated = $request->validate([
            'montant' => 'required|integer|min:10', //valeur min=10
        ]);
        $user = auth('api')->user();
        if ($user->solde < $validated['montant']) {
            return response()->json([
                'message' => 'Solde insuffisant',
            ], 422);
        }

        $user->solde -= $validated['montant'];
        $user->save();
        return response()->json([
            'data'    => [
                'nouveau_solde' => $user->solde,
            ],
            'message' => 'Points dépensés avec succès',
        ], 200);

    }
}
