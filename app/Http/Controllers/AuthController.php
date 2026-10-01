<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    // หน้า Login
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    // ประมวลผล Login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();
            return $this->redirectBasedOnRole($user)
                ->with('success', 'ยินดีต้อนรับคุณ ' . $user->name . ' เข้าสู่ระบบ');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง กรุณาตรวจสอบอีกครั้ง',
        ])->onlyInput('email');
    }

    // ปุ่มลัดล็อกอินด่วน (Demo 1-Click Login สำหรับทดสอบระบบและตรวจงาน)
    public function demoLogin(string $role)
    {
        $user = match ($role) {
            'admin' => User::where('email', 'raphiphat001@porsche-service.th')->first(),
            'technician' => User::where('email', 'thanasak017@porsche-service.th')->first(),
            'customer' => User::where('email', 'sompong@gmail.com')->first(),
            default => null,
        };

        if (!$user) {
            $user = User::where('role', $role)->first();
        }

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            return $this->redirectBasedOnRole($user)
                ->with('success', 'เข้าสู่ระบบในบทบาท: ' . ($role === 'admin' ? 'ผู้ดูแลระบบ (Admin: Raphiphat001)' : ($role === 'technician' ? 'ช่างเทคนิค (Thanasak 017)' : 'ลูกค้า (Customer)')));
        }

        return redirect()->route('login')->with('error', 'ไม่พบบัญชีผู้ใช้ตัวอย่าง');
    }

    // หน้าสมัครสมาชิกสำหรับลูกค้า
    public function showRegister()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.register');
    }

    // ประมวลผลสมัครสมาชิก
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีความยาวอย่างน้อย 6 ตัวอักษร',
            'password.confirmed' => 'การยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('customer.repairs.index')
            ->with('success', 'ลงทะเบียนสำเร็จ ยินดีต้อนรับคุณ ' . $user->name);
    }

    // ออกจากระบบ
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'คุณได้ออกจากระบบเรียบร้อยแล้ว');
    }

    // นำทางตามบทบาท
    private function redirectBasedOnRole(User $user)
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'technician' => redirect()->route('technician.repairs.index'),
            'customer' => redirect()->route('customer.repairs.index'),
            default => redirect()->route('home'),
        };
    }
}
