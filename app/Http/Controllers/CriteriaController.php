<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use Exception;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CriteriaController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        $totalwieght = 0;
        $someempty = false;
        foreach ($criterias as $criteria) {
            if ($criteria->weight <= 0 || $criteria->weight == null) {
                $someempty = true;
            } else {
                $totalwieght = bcadd($totalwieght, $criteria->weight);
            }
        }
        return view('admin.kriteria', compact(['criterias', 'totalwieght', 'someempty']));
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
                ],
                [
                    'name.required' => 'Nama kriteria wajib diisi.',
                    'type.required' => 'Tipe kriteria wajib diisi.',
                ]
            );
            DB::beginTransaction();

            Criteria::create([
                'name' => $request->name,
                'short_name' => Str::lower($request->name),
                'type' => $request->type,
                'weight' => 0,
                'description' => 'none',
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
                ],
                [
                    'name.required' => 'Nama kriteria wajib diisi.',
                    'type.required' => 'Tipe kriteria wajib diisi.',
                ]
            );

            DB::beginTransaction();
            Criteria::where('id', $id)->update([
                'name' => $request->name,
                'short_name' => Str::lower($request->name),
                'type' => $request->type,
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

    public function updatebobot(Request $request)
    {
        try {
            $request->validate([
                'weight' => 'required',
                'weight.*' => 'required|numeric|min:1',
            ], [
                'weight.required' => 'Kriteria tidak dapat ditentukan.',
                'weight.*.required' => 'Terdapat bobot kriteria yang masih kosong.',
                'weight.*.min' => 'Isian bobot kriteria tidak boleh 0',
            ]);

            $total = 0;
            foreach ($request->weight as $idcriteria => $weight) {
                $total += (int) $weight;
            }

            if ($total !== 100) {
                throw new Exception('Total bobot kriteria wajib 100%.');
            }

            DB::beginTransaction();
            foreach ($request->weight as $idcriteria => $weight) {
                Criteria::where('id', $idcriteria)->update([
                    'weight' => $weight
                ]);
            }
            DB::commit();

            flash()->success('Bobot kriteria telah di atur.');
            return redirect()->back();
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
