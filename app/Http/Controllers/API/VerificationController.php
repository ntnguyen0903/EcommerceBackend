<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    //
    public function verify($user_id, Request $request)
    {
        if (!$request->hasValidSignature()) {
            return response()->json(["msg" => "Invalid/Expired url provided."], 401);
        }

        $user = User::findOrFail($user_id);

        if ($user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        } else {
            return response()->json([
                "status" => 400,
                "message" => "Email already verified",
            ]);
        }

        return response()->json([
            "status" => 200,
            "message" => "Your email $user->email successfully verified",
        ]);
    }
}
