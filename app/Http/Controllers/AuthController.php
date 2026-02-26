<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Throwable;

class AuthController extends Controller
{
    public function index()
    {
        if (Auth::user()) {
            $name = Auth::user()->role . '.dashboard';
            return redirect(route($name));
        } else {
            return view('Auth.login');
        }
    }

    public function store(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'Masukkan email dan password valid.',
                'email.email' => 'Masukkan email dan password valid.',
                'password' => 'Masukkan email dan password valid.',
            ]);

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                flash()->success('Login berhasil, Selamat datang!');
                return redirect()->route(Auth::user()->role . '.dashboard');
            } else {
                throw new Exception('Login gagal, Kredensial akun tidak valid!');
            }
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $allErrors = collect($errors)->flatten()->implode('<br> • ');
            flash()->error($allErrors);
            return redirect()->back();
        } catch (Throwable $e) {
            flash()->error($e->getMessage());
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        flash()->success('Logout berhasil, Sampai jumpa kembali!');

        return redirect(route('login'));
    }
}
