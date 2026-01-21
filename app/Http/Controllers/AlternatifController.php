<?php

namespace App\Http\Controllers;

use Exception;
use Throwable;
use App\Models\Criteria;
use App\Imports\CacheImport;
use App\Models\Alternative;
use App\Models\AlternativeCriteria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

class AlternatifController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with('alternativecriteria.criteria')->get();
        return view('admin.alternatif', compact(['criterias', 'alternatives']));
    }

    public function create()
    {
        return view('admin.alternatif_create');
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:xls,xlsx|max:15360',
            ], [
                'file.required' => 'File wajib diunggah.',
                'file.mimes' => 'Format file harus Excel (.xls atau .xlsx).',
                'file.max' => 'Ukuran file maksimal 15MB.',
            ]);

            // dd($request->all());
            $file = $request->file('file');
            $sheets = Excel::toCollection(new CacheImport, $file);
            $dataCollection = $sheets->first();
            $header = [];

            $criterias = Criteria::all();
            $i = 0;
            DB::beginTransaction();
            foreach ($dataCollection as $index_data => $item) {
                $alternative = Alternative::updateOrCreate(
                    [
                        'name' => $item['daftar_menu_tomoro_coffee']
                    ],
                    [
                        'name' => $item['daftar_menu_tomoro_coffee']
                    ]
                );

                foreach ($criterias as $criteria) {
                    $criteriaName = strtolower(str_replace([' ', '-'], '_', $criteria->name));
                    if (str_contains($criteriaName, '/')) {
                        $criteriaName = str_replace('/', '', $criteriaName);
                    }
                    $header[$item['daftar_menu_tomoro_coffee']][$criteriaName] = $item[$criteriaName];
                    AlternativeCriteria::updateOrCreate(
                        [
                            'alternative_id' => $alternative->id,
                            'criteria_id' => $criteria->id,
                        ],
                        [
                            'value' => $item[$criteriaName]
                        ]
                    );
                }
            }

            if (empty($header)) {
                throw new Exception('• Data kriteria tidak ditemukan!');
            }
            // dd($header);
            DB::commit();
            // dd($header);
            flash()->success('Data alternatif berhasil diunggah.');
            return redirect(route('admin.alternatif.index'));
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

    public function delete($id)
    {
        try {
            DB::beginTransaction();
            Alternative::where('id', $id)->delete();
            AlternativeCriteria::where('alternative_id', $id)->delete();
            DB::commit();
            // flash()->success('Data alternatif berhasil dihapus.');
            return response()->json([
                'message' => 'success'
            ], 200);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $allErrors = collect($errors)->flatten()->implode('<br> • ');
            return response()->json([
                'message' => 'Inputan Gagal! Periksa kembali isian Anda. <br> • ' . $allErrors
            ], 400);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Inputan Gagal! Periksa kembali isian Anda. <br> ' . $e->getMessage()
            ], 500);
        }
    }

    public function alldelete()
    {
        try {
            DB::beginTransaction();
            Alternative::query()->delete();
            AlternativeCriteria::query()->delete();
            DB::commit();
            return response()->json([
                'message' => 'Data seluruh alternatif berhasil dihapus.'
            ], 200);
        } catch (ValidationException $e) {
            $errors = $e->errors();
            $allErrors = collect($errors)->flatten()->implode('<br> • ');
            return response()->json([
                'message' => 'Inputan Gagal! Periksa kembali isian Anda. <br> • ' . $allErrors
            ], 400);
        } catch (Throwable $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Inputan Gagal! Periksa kembali isian Anda. <br> ' . $e->getMessage()
            ], 500);
        }
    }
}
