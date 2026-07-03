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
        try {
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

            if ($someempty) {
                throw new Exception('Terdapat bobot kriteria bernilai 0.');
            }

            if ($totalwieght != 100) {
                throw new Exception('Bobot kriteria tidak 100%, Nilai bobot: ' . $totalwieght . '%.');
            }
        } catch (Throwable $e) {
            flash()->error($e->getMessage());
            return redirect(route('admin.kriteria.index'));
        }

        $criterias = Criteria::all();
        $alternatives = Alternative::with('alternativecriteria.criteria')->where('available', true)->get();
        return view('admin.alternatif', compact(['criterias', 'alternatives']));
    }

    public function import()
    {
        return view('admin.alternatif_import');
    }

    public function create()
    {
        $criterias = Criteria::all();
        $edit = false;
        return view('admin.alternatif_create', compact(['criterias', 'edit']));
    }

    public function edit($id)
    {
        $criterias = Criteria::all();
        $edit = true;
        $alternative = Alternative::find($id);
        $alternative_critera = AlternativeCriteria::where('alternative_id', $id)->get();
        return view('admin.alternatif_create', compact(['criterias', 'alternative', 'edit', 'alternative_critera']));
    }

    public function store_import(Request $request)
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
                // dd($item['kategori'] == 'coffe' ? 0 : 1);
                $alternative = Alternative::updateOrCreate(
                    [
                        'name' => $item['daftar_menu_tomoro_coffee'],
                    ],
                    [
                        'price' => $item['harga'],
                        'category' => $item['kategori'] == 'coffe' ? 0 : 1,
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

    public function store(Request $request)
    {
        try {
            $rules = [
                'name'       => 'required|string',
                'price'      => 'required|numeric|min:0',
                'category'      => 'required',
                'criteria'   => 'required|array',
                'criteria.*' => 'required|numeric|min:0|max:100',
            ];

            $messages = [
                'name.required'       => 'Nama Alternatif wajib diisi.',
                'name.string'         => 'Nama Alternatif harus berupa teks.',

                'price.required'      => 'Harga Alternatif wajib diisi.',
                'price.numeric'       => 'Harga Alternatif harus berupa angka.',
                'price.min'           => 'Harga Alternatif tidak boleh minus.',

                'category.required'   => 'Kategori Alternatif wajib diisi.',

                'criteria.required'   => 'Kandungan bahan wajib diisi.',
                'criteria.array'      => 'Format kriteria tidak valid.',

                'criteria.*.required' => 'Semua nilai kandungan bahan wajib diisi, tidak boleh ada yang kosong.',
                'criteria.*.numeric'  => 'Nilai kandungan bahan harus berupa angka.',
                'criteria.*.min'      => 'Nilai kandungan bahan tidak boleh minus.',
                'criteria.*.max'      => 'Nilai kandungan bahan maksimal 100%.',
            ];

            $validatedData = $request->validate($rules, $messages);

            DB::beginTransaction();

            $alternative = Alternative::create([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            foreach ($request->criteria as $idcriteria => $kandungan) {
                AlternativeCriteria::create([
                    'alternative_id' => $alternative->id,
                    'criteria_id' => $idcriteria,
                    'value' => $kandungan,
                ]);
            }

            DB::commit();
            flash()->success('Data alternatif berhasil ditambahkan.');
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

    public function update(Request $request, $id)
    {
        try {
            $rules = [
                'name'       => 'required|string',
                'price'      => 'required|numeric|min:0',
                'category'      => 'required',
                'criteria'   => 'required|array',
                'criteria.*' => 'required|numeric|min:0|max:100',
            ];

            $messages = [
                'name.required'       => 'Nama Alternatif wajib diisi.',
                'name.string'         => 'Nama Alternatif harus berupa teks.',

                'price.required'      => 'Harga Alternatif wajib diisi.',
                'price.numeric'       => 'Harga Alternatif harus berupa angka.',
                'price.min'           => 'Harga Alternatif tidak boleh minus.',

                'category.required'   => 'Kategori Alternatif wajib diisi.',

                'criteria.required'   => 'Kandungan bahan wajib diisi.',
                'criteria.array'      => 'Format kriteria tidak valid.',

                'criteria.*.required' => 'Semua nilai kandungan bahan wajib diisi, tidak boleh ada yang kosong.',
                'criteria.*.numeric'  => 'Nilai kandungan bahan harus berupa angka.',
                'criteria.*.min'      => 'Nilai kandungan bahan tidak boleh minus.',
                'criteria.*.max'      => 'Nilai kandungan bahan maksimal 100%.',
            ];

            $validatedData = $request->validate($rules, $messages);

            DB::beginTransaction();

            $alternative = Alternative::where('id', $id)->update([
                'name' => $request->name,
                'price' => $request->price,
            ]);

            AlternativeCriteria::where('alternative_id', $id)->delete();

            foreach ($request->criteria as $idcriteria => $kandungan) {
                AlternativeCriteria::create([
                    'alternative_id' => $id,
                    'criteria_id' => $idcriteria,
                    'value' => $kandungan,
                ]);
            }

            DB::commit();
            flash()->success('Data alternatif berhasil diperbarui.');
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
