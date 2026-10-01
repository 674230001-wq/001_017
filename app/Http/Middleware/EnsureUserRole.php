<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('warning', 'กรุณาเข้าสู่ระบบก่อนทำรายการ');
        }

        $user = auth()->user();

        // แอดมินสามารถเข้าถึงได้ทุกส่วน
        if ($user->role === 'admin') {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'ขออภัย คุณไม่มีสิทธิ์เข้าถึงหน้านี้ (สำหรับ ' . implode(', ', $roles) . ' เท่านั้น)');
        }

        return $next($request);
    }
}
