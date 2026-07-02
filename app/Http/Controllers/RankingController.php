<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeCriteria;
use App\Models\Criteria;
use App\Models\Periode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class RankingController extends Controller
{
    protected $globalAlternatives;

    public function __construct()
    {
        // 2. Isi data di dalam constructor
        // Ini bisa berupa array, hasil query database, atau logic lainnya
        $alternatives = Alternative::all();
        $loop = 0;
        foreach ($alternatives as $alternative) {
            $loop = $loop + 1;
            $this->globalAlternatives[$alternative->id] = [
                'NAME' => $alternative->name,
                'CODE' => 'A' . $loop,
            ];
        }
    }

    // public function periode()
    // {
    //     try {
    //         $criterias = Criteria::all();
    //         $totalwieght = 0;
    //         $someempty = false;
    //         foreach ($criterias as $criteria) {
    //             if ($criteria->weight <= 0 || $criteria->weight == null) {
    //                 $someempty = true;
    //             } else {
    //                 $totalwieght = bcadd($totalwieght, $criteria->weight);
    //             }
    //         }

    //         if ($someempty) {
    //             throw new Exception('Terdapat bobot kriteria bernilai 0.');
    //         }

    //         if ($totalwieght != 100) {
    //             throw new Exception('Bobot kriteria tidak 100%, Nilai bobot: ' . $totalwieght . '%.');
    //         }
    //     } catch (Throwable $e) {
    //         DB::rollback();
    //         flash()->error($e->getMessage());
    //         return redirect(route('admin.kriteria.index'));
    //     }

    //     $Periodes = Periode::orderBy('id', 'desc')->get();
    //     $alternativecriteria = AlternativeCriteria::get();

    //     $criteria_active = array_map('intval', Criteria::pluck('id')->toArray());
    //     sort($criteria_active);

    //     $Periodes->transform(function ($Periode) use ($criteria_active) {

    //         $firstAlternativeId = $Periode->alternatives[0] ?? 0;

    //         $array_tadi = AlternativeCriteria::where('alternative_id', (int)$firstAlternativeId)
    //             ->pluck('criteria_id')
    //             ->toArray();

    //         $array_tadi = array_map('intval', $array_tadi);
    //         sort($array_tadi);

    //         $Periode->is_equal = ($criteria_active == $array_tadi);

    //         return $Periode;
    //     });

    //     $latest = Periode::latest()->value('id');

    //     return view('Admin.periode', compact(['Periodes', 'latest', 'alternativecriteria', 'criteria_active']));
    // }

    // public function post_periode()
    // {
    //     try {
    //         DB::beginTransaction();

    //         $oldAlternatives = Alternative::where('available', true)->get();
    //         $ids = [];
    //         foreach ($oldAlternatives as $alternative) {
    //             $newAlternative = $alternative->replicate();
    //             $newAlternative->available = false;
    //             $newAlternative->save();
    //             $oldCriteria = AlternativeCriteria::where('alternative_id', $alternative->id)->get();
    //             foreach ($oldCriteria as $criteria) {
    //                 $newCriteria = $criteria->replicate();
    //                 $newCriteria->alternative_id = $newAlternative->id;
    //                 $newCriteria->save();
    //             }

    //             $ids[] = $newAlternative->id;
    //         }

    //         Periode::create([
    //             'name' => now()->format('d/m/Y H:i:s'),
    //             'alternatives' => $ids,
    //         ]);

    //         DB::commit();
    //         flash()->success('Priode pengujian berhasil dibuat.');
    //         return redirect()->back();
    //     } catch (ValidationException $e) {
    //         $errors = $e->errors();
    //         $allErrors = collect($errors)->flatten()->implode('<br> • ');
    //         flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> • ' . $allErrors);
    //         return redirect()->back();
    //     } catch (Throwable $e) {
    //         DB::rollback();
    //         flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    // public function delete_periode($id)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $periode = Periode::find($id);
    //         $alternativeIds = array_map('intval', $periode->alternatives ?? []);
    //         if (!empty($alternativeIds)) {
    //             Alternative::whereIn('id', $alternativeIds)->forceDelete();
    //         }
    //         $periode->delete();
    //         DB::commit();
    //         flash()->success('Priode pengujian berhasil dihapus.');
    //         return redirect()->back();
    //     } catch (ValidationException $e) {
    //         $errors = $e->errors();
    //         $allErrors = collect($errors)->flatten()->implode('<br> • ');
    //         flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> • ' . $allErrors);
    //         return redirect()->back();
    //     } catch (Throwable $e) {
    //         DB::rollback();
    //         flash()->error('Inputan Gagal! Periksa kembali isian Anda. <br> ' . $e->getMessage());
    //         return redirect()->back();
    //     }
    // }

    public function show()
    {
        return view('Admin.show');
    }

    public function nilai_asli($type)
    {
        $raw_alterantives = Alternative::with(['alternativecriteria.criteria'])->where('category', $type)->get();
        $Alternatives = [];
        $headers = [];
        $headers[] = 'Nama Alternatif';
        foreach ($raw_alterantives->first()->alternativecriteria as $alternativecriteria) {
            $headers[] = $alternativecriteria->criteria->name;
        }

        foreach ($raw_alterantives as $alternative) {
            $alterantive_criterias = [];
            foreach ($alternative->alternativecriteria as $alternativecriteria) {
                $alterantive_criterias[] = [
                    'name' => $alternativecriteria->criteria->name,
                    'value' => $alternativecriteria->value,
                    'bobot' => $alternativecriteria->criteria->weight,
                    'normalisasi' => $alternativecriteria->criteria->weight / 100,
                ];
            }
            $Alternatives[] = [
                'name' => $alternative->name,
                'alterantive_criterias' => $alterantive_criterias,
            ];
        }



        return response()->json([
            'message' => 'Berhasil!',
            'headers' => $headers,
            'alterantives' => $Alternatives,
        ], 200);
    }

    // public function ranking($id)
    // {
    //     $periode = periode::find($id);
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

    //     if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
    //         flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
    //         return redirect(route('admin.dashboard'));
    //     }

    //     $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
    //     $utilityMax = [];
    //     $utilityMin = [];

    //     $convertionCriteria = [];
    //     foreach ($criterias as $criteria) {
    //         $arrvalueraw = [];
    //         foreach ($alternativecriterias as $alternativecriteria) {
    //             if ($alternativecriteria->criteria_id == $criteria->id) {
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 $arrvalueraw[] = $alternativeValue;
    //             }
    //         }
    //         $utilityMax[$criteria->id] = max($arrvalueraw);
    //         $utilityMin[$criteria->id] = min($arrvalueraw);
    //         $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
    //     }

    //     $ArrNilaiAkhir = [];
    //     foreach ($alternatives as $alternative) {
    //         $Arrcriteria = [];
    //         foreach ($criterias as $criteria) {
    //             foreach ($alternative->alternativecriteria as $alternativecriteria) {
    //                 $weightValue = 0;
    //                 $firstsub = null;
    //                 $secondsub = null;
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 if ($alternativecriteria->criteria_id == $criteria->id) {
    //                     if ($criteria->type == 'benefit') {
    //                         $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     } else if ($criteria->type == 'cost') {
    //                         $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     }

    //                     $Arrcriteria[$criteria->id] = $weightValue;
    //                 }
    //             }
    //         }

    //         $totalNilaiAkhir = 0;
    //         $newArrcriteria = [];
    //         foreach ($Arrcriteria as $indexACT => $ACT) {
    //             $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
    //             $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
    //             $newArrcriteria[$indexACT] = $ACTmultiW;
    //         }

    //         $ArrNilaiAkhir[$alternative->id] = $totalNilaiAkhir;
    //     }

    //     $sorted = collect($ArrNilaiAkhir)->sortDesc();

    //     $AAA = $this->globalAlternatives;
    //     return view('admin.ranking', compact(['sorted', 'AAA', 'id']));
    // }

    // public function utility($id)
    // {
    //     $periode = periode::find($id);
    //     $criterias = Criteria::all();
    //     $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('id', $periode->alternatives)->get();

    //     if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
    //         flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
    //         return redirect(route('admin.dashboard'));
    //     }

    //     $utilityMax = [];
    //     $utilityMin = [];
    //     foreach ($criterias as $criteria) {
    //         $arrvalueraw = [];
    //         foreach ($alternativecriterias as $alternativecriteria) {
    //             if ($alternativecriteria->criteria_id == $criteria->id) {
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 $arrvalueraw[] = $alternativeValue;
    //             }
    //         }
    //         $utilityMax[$criteria->id] = max($arrvalueraw);
    //         $utilityMin[$criteria->id] = min($arrvalueraw);
    //     }

    //     $AAA = $this->globalAlternatives;
    //     return view('admin.nilai_utility', compact(['criterias', 'alternativecriterias', 'utilityMax', 'utilityMin', 'AAA', 'id']));
    // }

    // public function bobotutility($id)
    // {
    //     $periode = periode::find($id);
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

    //     if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
    //         flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
    //         return redirect(route('admin.dashboard'));
    //     }

    //     $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
    //     $utilityMax = [];
    //     $utilityMin = [];
    //     foreach ($criterias as $criteria) {
    //         $arrvalueraw = [];
    //         foreach ($alternativecriterias as $alternativecriteria) {
    //             if ($alternativecriteria->criteria_id == $criteria->id) {
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 $arrvalueraw[] = $alternativeValue;
    //             }
    //         }
    //         $utilityMax[$criteria->id] = max($arrvalueraw);
    //         $utilityMin[$criteria->id] = min($arrvalueraw);
    //     }

    //     $bobotUtility = [];
    //     foreach ($alternatives as $alternative) {
    //         $Arrcriteria = [];
    //         foreach ($criterias as $criteria) {
    //             foreach ($alternative->alternativecriteria as $alternativecriteria) {
    //                 $weightValue = 0;
    //                 $firstsub = null;
    //                 $secondsub = null;
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 if ($alternativecriteria->criteria_id == $criteria->id) {
    //                     if ($criteria->type == 'benefit') {
    //                         $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     } else if ($criteria->type == 'cost') {
    //                         $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     }

    //                     $Arrcriteria[$criteria->id] = $weightValue;
    //                 }
    //             }
    //         }

    //         $bobotUtility[$alternative->id] = [
    //             'name' => $alternative->name,
    //             'criterias' => $Arrcriteria
    //         ];
    //     }

    //     $AAA = $this->globalAlternatives;
    //     return view('admin.bobot_utility', compact(['criterias', 'bobotUtility', 'AAA', 'id']));
    // }

    // public function nilaiakhir($id)
    // {
    //     $periode = periode::find($id);
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

    //     if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
    //         flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
    //         return redirect(route('admin.dashboard'));
    //     }

    //     $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
    //     $utilityMax = [];
    //     $utilityMin = [];

    //     $convertionCriteria = [];
    //     foreach ($criterias as $criteria) {
    //         $arrvalueraw = [];
    //         foreach ($alternativecriterias as $alternativecriteria) {
    //             if ($alternativecriteria->criteria_id == $criteria->id) {
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 $arrvalueraw[] = $alternativeValue;
    //             }
    //         }
    //         $utilityMax[$criteria->id] = max($arrvalueraw);
    //         $utilityMin[$criteria->id] = min($arrvalueraw);
    //         $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
    //     }

    //     $ArrNilaiAkhir = [];
    //     foreach ($alternatives as $alternative) {
    //         $Arrcriteria = [];
    //         foreach ($criterias as $criteria) {
    //             foreach ($alternative->alternativecriteria as $alternativecriteria) {
    //                 $weightValue = 0;
    //                 $firstsub = null;
    //                 $secondsub = null;
    //                 $alternativeValue = convertion_value($alternativecriteria->value);
    //                 if ($alternativecriteria->criteria_id == $criteria->id) {
    //                     if ($criteria->type == 'benefit') {
    //                         $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     } else if ($criteria->type == 'cost') {
    //                         $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
    //                         $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                         if ($firstsub > 0 & $secondsub > 0) {
    //                             $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                         } else {
    //                             $weightValue = 0;
    //                         }
    //                     }

    //                     $Arrcriteria[$criteria->id] = $weightValue;
    //                 }
    //             }
    //         }

    //         $totalNilaiAkhir = 0;
    //         $newArrcriteria = [];
    //         foreach ($Arrcriteria as $indexACT => $ACT) {
    //             $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
    //             $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
    //             $newArrcriteria[$indexACT] = $ACTmultiW;
    //         }

    //         $ArrNilaiAkhir[$alternative->id] = [
    //             'name' => $alternative->name,
    //             'criterias' => $newArrcriteria,
    //             'nilaiakhir' => $totalNilaiAkhir
    //         ];
    //     }

    //     $AAA = $this->globalAlternatives;
    //     return view('admin.nilai_akhir', compact(['criterias', 'ArrNilaiAkhir', 'AAA', 'id']));
    // }
}
