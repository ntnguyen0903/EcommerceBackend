<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ApiAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    // // public function handle(Request $request, Closure $next)
    // {
    //     if (Auth::check()) {
    //         if ($request->user()->can('server:admin')) {
    //             return $next($request);
    //         } 
    //         else {
    //             return response()->json([
    //                 'message' => 'Quyền truy cập bị Từ chối! Bạn không phải là Quản trị viên',
    //                 'status'=>403,
    //             ]);
    //         }
    //     } 
    //     else {
    //         return response()->json([
    //             'status' => 401,
    //             'message' => 'Vui lòng đăng nhập trước.',
    //         ]);
    //     }
    // }
    public function handle(Request $request, Closure $next)
    {
        // Kiểm tra người dùng đã đăng nhập chưa
        if (!$request->user()) {
            return response()->json([
                'status' => 401,
                'message' => 'Vui lòng đăng nhập trước.',
            ]);
        }

        // Kiểm tra vai trò của người dùng
        if ($request->user()->role_as != 1) {
            return response()->json([
                'message' => 'Quyền truy cập bị Từ chối! Bạn không phải là Quản trị viên',
                'status'=>403,
            ]);
        }

        return $next($request);
    }
}
