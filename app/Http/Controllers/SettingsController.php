<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class SettingsController extends Controller
{
    public function index()
    {
        $Setting = Setting::latest()->first();
        return view('Admin.settings', compact(['Setting']));
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();
            $ID = Setting::latest()->first()->value('id');
            Setting::updateOrCreate([
                'id' => $ID
            ], [
                'main_title' => $request->main_title,
                'main_desc_1' => $request->main_desc_1,
                'main_desc_2' => $request->main_desc_2,
                'second_title' => $request->second_title,
                'second_desc' => $request->second_desc,
            ]);
            DB::commit();
            flash()->success('Settings berhasil dirubah.');
            return redirect(route('admin.settings.index'));
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $allErrors = collect($errors)->flatten()->implode('<br> • ');
            flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> • ' . $allErrors);
            return redirect()->back();
        } catch (Throwable $e) {
            DB::rollback();
            flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> ' . $e->getMessage());
            return redirect()->back();
        }
    }
}
