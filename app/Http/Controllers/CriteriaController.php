<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        $totalwieght = 0;
        foreach ($criterias as $criteria) {
            $totalwieght = bcadd($totalwieght, $criteria->weight);
        }
        return view('admin.kriteria', compact(['criterias', 'totalwieght']));
    }

    public function create()
    {
        $edit = false;
        return view('admin.kriteria_create', compact(['edit']));
    }

    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'type' => 'required',
                    'weight' => 'required|numeric|max:100',
                    'description' => 'sometimes',
                ],
                [
                    'name.required' => 'Nama kriteria wajib diisi.',
                    'type.required' => 'Tipe kriteria wajib diisi.',
                    'weight.required' => 'Bobot kriteria wajib diisi.',
                    'weight.numeric' => 'Bobot kriteria wajib angka.',
                    'weight.max' => 'Maksimum bobot kriteria 100%',
                ]
            );
            DB::beginTransaction();
            Criteria::create([
                'name' => $request->name,
                'type' => $request->type,
                'weight' => $request->weight,
                'description' => $request->description ?? null,
            ]);
            DB::commit();
            flash()->success('Data kriteria berhasil ditambahkan.');
            return redirect(route('admin.kriteria.index'));
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

    public function edit($id)
    {
        $edit = true;
        $criteria = Criteria::where('id', $id)->firstOrFail();
        return view('admin.kriteria_create', compact(['edit', 'criteria']));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate(
                [
                    'name' => 'required',
                    'type' => 'required',
                    'weight' => 'required|numeric|max:100',
                    'description' => 'sometimes',
                ],
                [
                    'name.required' => 'Nama kriteria wajib diisi.',
                    'type.required' => 'Tipe kriteria wajib diisi.',
                    'weight.required' => 'Bobot kriteria wajib diisi.',
                    'weight.numeric' => 'Bobot kriteria wajib angka.',
                    'weight.max' => 'Maksimum bobot kriteria 100%',
                ]
            );
            DB::beginTransaction();
            Criteria::where('id', $id)->update([
                'name' => $request->name,
                'type' => $request->type,
                'weight' => $request->weight,
                'description' => $request->description ?? null,
            ]);
            DB::commit();
            flash()->success('Data kriteria berhasil diperbarui.');
            return redirect(route('admin.kriteria.index'));
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
            Criteria::where('id', $id)->delete();
            DB::commit();
            flash()->success('Data kriteria berhasil dihapus.');
            return redirect()->back();
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
