<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\AccountActivationMail;
use App\Mail\UserVerification;
use Illuminate\Http\Request;
use App\Models\User;

use App\Models\Category;
use App\Models\Orderitems;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;

class AuthController extends Controller
{
    // top1
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|min:8'
        ]);
        if ($validator->fails()) {
            return response()->json(([
                'validation_errors' => $validator->errors(),
            ]));
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            $token =  $user->createToken($user->email . '_Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'username' => $user->name,
                'token' => $token,
                'message' => 'Đăng ký thành công',
            ]);
        }
    }

    

    //04/07
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:191',
    //         'email' => 'required|email|max:191|unique:users,email',
    //         'password' => 'required|min:8'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'validation_errors' => $validator->errors(),
    //         ]);
    //     } else {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'activation_token' => Str::random(60),
    //         ]);

    //         $token =  $user->createToken($user->email . '_Token')->plainTextToken;

    //         if ($user) {
    //             try {
    //                 Mail::to($user->email)->send(new AccountActivationMail($user));
    //                 return response()->json([
    //                     'status' => 200,
    //                     'username' => $user->name,
    //                     'token' => $token,
    //                     'activation_token' => $user->activation_token,
    //                     'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.',
    //                     'activation_link' => url('/api/activate-account/' . $user->activation_token),
    //                 ]);
    //             } catch (\Exception $err) {
    //                 $user->delete();
    //                 return response()->json([
    //                     'status' => 500,
    //                     'message' => 'Không thể gửi email kích hoạt, vui lòng thử lại.',
    //                 ]);
    //             }
    //         }
    //     }
    // }

    public function activateAccount($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return response()->json([
                'message' => 'Mã kích hoạt không hợp lệ.',
            ], 404);
        }

        $user->email_verified_at = now();
        $user->activation_token = null;
        $user->save();

        return response()->json([
            'message' => 'Tài khoản đã được kích hoạt thành công.',
        ], 200);
    }
    //test gửi email
    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:191',
    //         'email' => 'required|email|max:191|unique:users,email',
    //         'password' => 'required|min:8'
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(([
    //             'validation_errors' => $validator->errors(),
    //         ]));
    //     } else {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             // Gán giá trị 1 cho vai trò quản trị viên
    //         ]);

    //         $token = $user->createToken($user->email . '_Token')->plainTextToken;

    //         if ($user) {
    //             try {
    //                 Mail::mailer('smtp')->to($user->email)->send(new UserVerification($user));
    //                 return response()->json([
    //                     'status' => 200,
    //                     'username' => $user->name,
    //                     'token' => $token,
    //                     'message' => 'Đăng ký thành công',
    //                 ]);
    //             } catch (\Exception $err) {
    //                 $user->delete();
    //                 return response()->json([
    //                     'status' => 500,
    //                     'message' => 'Could not send email verification, please try again',
    //                 ]);
    //             }
    //         }
    //     }
    // }

    // public function register(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|max:191',
    //         'email' => 'required|email|max:191|unique:users,email',
    //         'password' => 'required|min:8'
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json(([
    //             'validation_errors' => $validator->errors(),
    //         ]));
    //     } else {
    //         $user = User::create([
    //             'name' => $request->name,
    //             'email' => $request->email,
    //             'password' => Hash::make($request->password),
    //             'activation_token' => Str::random(60), // Tạo mã token kích hoạt mới

    //         ]);
    //         $token =  $user->createToken($user->email . '_Token')->plainTextToken;


    //          // Gửi email kích hoạt
    //           Mail::to($user->email)->send(new AccountActivationMail($user));
    //         return response()->json([
    //             'status' => 200,
    //             'username' => $user->name,
    //             'token' => $token,
    //             'message' => 'Đăng ký thành công. Vui lòng kiểm tra email để kích hoạt tài khoản.',

    //         ]);
    //     }
    // }
    // //
 

   // public function activateAccount($token)
    // {
    //     $user = User::where('activation_token', $token)->first();

    //     if (!$user) {
    //         return response()->json([
    //             'message' => 'Mã kích hoạt không hợp lệ.',
    //         ], 404);
    //     }

    //     $user->email_verified_at = now();
    //     $user->activation_token = null;
    //     $user->save();

    //     return response()->json([
    //         'message' => 'Tài khoản đã được kích hoạt thành công.',
    //     ], 200);
    // }
    
    public function login(Request $request)
    {
        $validator  =  Validator::make($request->all(), [
            'email' => 'required|max:191',
            'password' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'validation_errors' => $validator->errors(),
            ]);
        } 
        else {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => 401,
                    'message' => 'Thông tin không hợp lệ',
                ]);
            }
             else {
                if ($user->role_as == 1) 
                {
                    $role = 'admin';
                    $token  = $user->createToken($user->email . '_AdminToken', ['server:admin'])->plainTextToken;
                } 
                else {
                    $role = 'user'; // Hoặc giá trị khác phù hợp
                    $token =  $user->createToken($user->email . '_Token', ['='])->plainTextToken;
                }
                return response()->json([
                    'status' => 200,
                    'username' => $user->name,
                    'email' => $user->email,
                    'token' => $token,
                    'message' => 'Đăng nhập thành công',
                    'role' => $role,
                ]);
            }
        }
    }

    public function logout()
    {
        $user = new User();
        $user->tokens()->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Đăng xuất thành công',
        ]);
    }
    //view  khách hàng với role bằng 0
    public function index()
    {
        $users = User::where('role_as', 0)->get();
        $usersCount = $users->count();
        return response()->json([
            'status' => 200,
            'users' => $users,
            'usersCount' => $usersCount,
        ]);
    }
    //xóa  Khách hàng
    public function destroy($id)
    {
        try {
            $user = User::find($id);
            if ($user) {
                $user->delete();
                return response()->json([
                    'status' => 200,
                    'message' => 'Xóa khách hàng thành công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Xóa không thành công không tìm thấy id',
                ]);
            }
        } catch (QueryException $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Không thể xóa người  dùng vì đã có đơn hàng',
            ]);
        }
    }
    //edit Khách hàng
    public function edit($id)
    {

        $user = User::find($id);
        if ($user) {
            return response()->json([
                'status' => 200,
                'user' => $user,

            ]);
        } else {
            return response()->json([
                'status' => 404,
                'message' => 'Không tìm thấy ID user',
            ]);
        }
    }
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'name' => 'required|max:191',
            'email' => 'required|email|max:191',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        } else {
            $user = User::find($id);
            if ($user) {
                $user->name = $request->input('name');
                $user->email = $request->input('email');
                $user->password = Hash::make($request->input('password')); // Mã hóa mật khẩu
                $user->role_as = $request->input('role_as') === '1' ? '1' : '0';
                $user->save();
                return response()->json([
                    'status' => 200,
                    'message' => 'Cập nhật thông tin người dùng thành công',
                ]);
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'Không tìm thấy người dùng',
                ]);
            }
        }
    }
    //  view thông  tin khách  hàng từ khách hàng

    public function indexUser()
    {
        if (auth('sanctum')->check()) {
            $user = auth('sanctum')->user();
            return response()->json([
                'status' => 200,
                'user' => $user,
            ]);
        }
    }
    public function updateUser(Request $request)
    {
        // Lấy thông tin người dùng từ request
        $name = $request->input('name');
        $email = $request->input('email');

        // Cập nhật thông tin người dùng vào cơ sở dữ liệu
        $user = User::find(auth('sanctum')->user()->id);
        $user->name = $name;
        $user->email = $email;
        $user->save();

        // Trả về phản hồi thành công
        return response()->json(['message' => 'Cập nhật thông tin người dùng thành công']);
    }
    // public function changMk(Request $request)
    // {
    //     $user = auth()->user();

    //     $currentPassword = $request->input('currentPassword');
    //     $newPassword = $request->input('newPassword');

    //     if (!Hash::check($currentPassword, $user->password)) {
    //         return response()->json([
    //             'message' => 'Mật khẩu hiện tại không đúng.',
    //         ], 400);
    //     }

    //     $user->password = Hash::make($newPassword);
    //     $user->save();

    //     return response()->json([
    //         'message' => 'Đổi mật khẩu thành công.',
    //     ]);
    // }
    public function change_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'password' => 'required|min:6|max:100',
            'confirm_password' => 'required|same:password'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'Xác thực không thành công',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
            return response()->json([
                'message' => 'Mật khẩu được cập nhật thành công',
            ], 200);
        } else {
            return response()->json([
                'message' => 'Mật khẩu cũ không khớp',
            ], 400);
        }
    }
    //tim Khách hàng  bằng gmail
    public function search(Request $request)
    {
        $term = $request->input('term');
        // $minPrice = $request->input('minPrice');
        // $maxPrice = $request->input('maxPrice');

        // Tìm kiếm dựa trên tên sản phẩm và giá bán
        $results = User::where('email', 'like', "%$term%")
            //   ->whereBetween('selling_price', [$minPrice, $maxPrice])
            ->get();

        return response()->json([
            'results' => $results,
        ]);
    }
}
