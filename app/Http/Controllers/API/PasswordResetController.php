<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class PasswordResetController extends Controller
{
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $user = User::where('email', $request->email)->first();
    
        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng với địa chỉ email này.'], 404);
        }
    
        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            ['token' => bcrypt($user->email . time())]
        );
    
        $encodedToken = urlencode($passwordReset->token);
        Mail::to($user->email)->send(new ResetPasswordMail($passwordReset->token));
    
        return response()->json([
            'message' => 'Liên kết đặt lại mật khẩu đã được gửi đến địa chỉ email của bạn.',
            'token' => $encodedToken,
        ]);
    }
    
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $passwordReset = PasswordReset::where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$passwordReset) {
            return response()->json(['message' => 'Đường dẫn đặt lại mật khẩu không hợp lệ.'], 422);
        }

        $user = User::where('email', $passwordReset->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Không tìm thấy người dùng với địa chỉ email này.'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        $passwordReset->delete();

        return response()->json(['message' => 'Mật khẩu đã được đặt lại thành công.']);
    }

}
