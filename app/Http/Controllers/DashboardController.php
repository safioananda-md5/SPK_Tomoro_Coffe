<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $hour = Carbon::now('Asia/Jakarta')->format('H');

        if ($hour >= 5 && $hour < 11) {
            $greeting = "Selamat Pagi";
        } elseif ($hour >= 11 && $hour < 15) {
            $greeting = "Selamat Siang";
        } elseif ($hour >= 15 && $hour < 18) {
            $greeting = "Selamat Sore";
        } else {
            $greeting = "Selamat Malam";
        }

        return view('Admin.dashboard', compact(['greeting']));
    }
}
