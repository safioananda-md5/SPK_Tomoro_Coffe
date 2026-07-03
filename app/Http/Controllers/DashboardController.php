<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Criteria;
use App\Models\Alternative;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\AlternativeCriteria;
use App\Models\Periode;
use App\Models\Setting;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

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

        $CountCriteria = Criteria::count();
        $CountAlternative = Alternative::count();

        $top10newalterantives = Alternative::latest()->limit(10)->get();

        return view(Str::title(Auth::user()->role) . '.dashboard', compact(['greeting', 'CountAlternative', 'CountCriteria', 'top10newalterantives']));
    }

    public function landing()
    {
        if (isset(Auth::user()->id)) {
            return redirect(route('login'));
        }

        $alternativescoffe = Alternative::where('category', 0)->get();
        $alternativesnoncoffe = Alternative::where('category', 1)->get();
        $Setting = Setting::latest()->first();


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

        $periode = Periode::where('name', 'satu')->first();

        $adaPeriode = 'tidak';
        if ($periode) {
            $adaPeriode = 'ada';
        }

        return view('Owner.dashboard', compact(['alternativescoffe', 'alternativesnoncoffe', 'Setting', 'someempty', 'totalwieght', 'adaPeriode']));
    }

    public function perhitungan($type)
    {
        return view('Owner.perhitungan', compact(['type']));
    }

    public function nilai_asli($type)
    {
        $periodeAlternatives = Periode::where('name', 'satu')->value('alternatives');
        $raw_alterantives = Alternative::with(['alternativecriteria.criteria'])
            ->when($periodeAlternatives !== null, function ($query) use ($periodeAlternatives) {
                return $query
                    ->whereIn('id', $periodeAlternatives);
            })
            ->when($type !== 'all', function ($query) use ($type) {
                return $query
                    ->where('category', $type);
            })
            ->get();
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

    public function periode(Request $request)
    {
        $AlterantifArray = Alternative::pluck('id')->toArray();
        $periodeUpdate = Periode::updateOrCreate([
            'name' => 'satu',
        ], [
            'alternatives' => $AlterantifArray,
        ]);

        if (!$periodeUpdate->wasRecentlyCreated && !$periodeUpdate->wasChanged()) {
            $periodeUpdate->touch();
        }

        return response()->json([
            'message' => 'Berhasil Membuat Periode!',
            'update' => $periodeUpdate->updated_at->format('d/m/Y H:i'),
        ], 200);
    }

    // public function perhitungan($type)
    // {
    //     // $id = Crypt::decrypt($id);
    //     // $periode = periode::find($id);
    //     $criterias = Criteria::all();
    //     $Alternative = Alternative::where('category', $type)->pluck('id')->toArray();
    //     $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('alternative_id', $Alternative)->get();

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

    //     return view('Owner.nilai_utility', compact(['criterias', 'alternativecriterias', 'utilityMax', 'utilityMin', 'type']));
    // }

    // public function bobotutility($type)
    // {
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->where('category', $type)->get();

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

    //     $loop = 0;
    //     $AAA = [];
    //     foreach ($alternatives as $alternative) {
    //         $loop = $loop + 1;
    //         $AAA[$alternative->id] = [
    //             'NAME' => $alternative->name,
    //             'CODE' => (int) $loop,
    //         ];
    //     }

    //     return view('Owner.bobot_utility', compact(['criterias', 'bobotUtility', 'AAA', 'type']));
    // }

    // public function nilaiakhir($type)
    // {
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->where('category', $type)->get();

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

    //     $loop = 0;
    //     $AAA = [];
    //     foreach ($alternatives as $alternative) {
    //         $loop = $loop + 1;
    //         $AAA[$alternative->id] = [
    //             'NAME' => $alternative->name,
    //             'CODE' => (int) $loop,
    //         ];
    //     }

    //     return view('Owner.nilai_akhir', compact(['criterias', 'ArrNilaiAkhir', 'AAA', 'type']));
    // }

    // public function ranking($type)
    // {
    //     $criterias = Criteria::all();
    //     $alternatives = Alternative::with(['alternativecriteria'])->where('category', $type)->get();

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

    //     $loop = 0;
    //     $AAA = [];
    //     foreach ($alternatives as $alternative) {
    //         $loop = $loop + 1;
    //         $AAA[$alternative->id] = [
    //             'NAME' => $alternative->name,
    //             'CODE' => (int) $loop,
    //         ];
    //     }

    //     return view('Owner.ranking', compact(['sorted', 'AAA', 'type']));
    // }

    // public function coffe()
    // {
    //     $alternatives = Alternative::with(['alternativecriteria'])->where('category', 0)->get();
    //     $criteriasOrder = Criteria::orderBy('weight', 'desc')->get();

    //     $CountCriteria = count(Criteria::all());
    //     $CountAlternative = count(Alternative::where('category', 0)->get());

    //     if ($CountCriteria > 0 && $CountAlternative > 2) {
    //         $criterias = Criteria::all();
    //         $alternatives = Alternative::with(['alternativecriteria'])->where('category', 0)->get();
    //         $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('alternative_id', $alternatives->pluck('id')->toArray())->get();
    //         $utilityMax = [];
    //         $utilityMin = [];

    //         $convertionCriteria = [];
    //         foreach ($criterias as $criteria) {
    //             $arrvalueraw = [];
    //             foreach ($alternativecriterias as $alternativecriteria) {
    //                 if ($alternativecriteria->criteria_id == $criteria->id) {
    //                     $alternativeValue = convertion_value($alternativecriteria->value);
    //                     $arrvalueraw[] = $alternativeValue;
    //                 }
    //             }
    //             $utilityMax[$criteria->id] = max($arrvalueraw);
    //             $utilityMin[$criteria->id] = min($arrvalueraw);
    //             $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
    //         }

    //         $ArrNilaiAkhir = [];
    //         foreach ($alternatives as $alternative) {
    //             $Arrcriteria = [];
    //             foreach ($criterias as $criteria) {
    //                 foreach ($alternative->alternativecriteria as $alternativecriteria) {
    //                     $weightValue = 0;
    //                     $firstsub = null;
    //                     $secondsub = null;
    //                     $alternativeValue = convertion_value($alternativecriteria->value);
    //                     if ($alternativecriteria->criteria_id == $criteria->id) {
    //                         if ($criteria->type == 'benefit') {
    //                             $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
    //                             $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                             if ($firstsub > 0 & $secondsub > 0) {
    //                                 $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                             } else {
    //                                 $weightValue = 0;
    //                             }
    //                         } else if ($criteria->type == 'cost') {
    //                             $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
    //                             $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                             if ($firstsub > 0 & $secondsub > 0) {
    //                                 $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                             } else {
    //                                 $weightValue = 0;
    //                             }
    //                         }

    //                         $Arrcriteria[$criteria->id] = $weightValue;
    //                     }
    //                 }
    //             }

    //             $totalNilaiAkhir = 0;
    //             $newArrcriteria = [];
    //             foreach ($Arrcriteria as $indexACT => $ACT) {
    //                 $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
    //                 $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
    //                 $newArrcriteria[$indexACT] = $ACTmultiW;
    //             }

    //             $Incriteria = [];
    //             foreach ($alternative->alternativecriteria as $AC) {
    //                 if ($AC->value > 0) {
    //                     $Incriteria[] = $AC->criteria_id;
    //                 }
    //             }

    //             $nameCriterias = implode(', ', Criteria::whereIn('id', $Incriteria)->whereNotNull('short_name')->pluck('short_name')->toArray());

    //             $ArrNilaiAkhir[$alternative->name] = [
    //                 $totalNilaiAkhir,
    //                 'Rp ' . number_format($alternative->price, 0, ',', '.'),
    //                 $nameCriterias
    //             ];
    //         }

    //         $sorted = collect($ArrNilaiAkhir)->sortByDesc('0');
    //     } else {
    //         $CountAlternative = null;
    //         $CountCriteria = null;
    //         $sorted = null;
    //     }

    //     // dd($sorted);
    //     return view('Owner.coffe', compact(['alternatives', 'criteriasOrder', 'sorted']));
    // }

    // public function non_coffe()
    // {
    //     $alternatives = Alternative::with(['alternativecriteria'])->where('category', 1)->get();
    //     $criteriasOrder = Criteria::orderBy('weight', 'desc')->get();

    //     $CountCriteria = count(Criteria::all());
    //     $CountAlternative = count(Alternative::where('category', 1)->get());

    //     if ($CountCriteria > 0 && $CountAlternative > 2) {
    //         $criterias = Criteria::all();
    //         $alternatives = Alternative::with(['alternativecriteria'])->where('category', 1)->get();
    //         $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('alternative_id', $alternatives->pluck('id')->toArray())->get();
    //         $utilityMax = [];
    //         $utilityMin = [];

    //         $convertionCriteria = [];
    //         foreach ($criterias as $criteria) {
    //             $arrvalueraw = [];
    //             foreach ($alternativecriterias as $alternativecriteria) {
    //                 if ($alternativecriteria->criteria_id == $criteria->id) {
    //                     $alternativeValue = convertion_value($alternativecriteria->value);
    //                     $arrvalueraw[] = $alternativeValue;
    //                 }
    //             }
    //             $utilityMax[$criteria->id] = max($arrvalueraw);
    //             $utilityMin[$criteria->id] = min($arrvalueraw);
    //             $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
    //         }

    //         $ArrNilaiAkhir = [];
    //         foreach ($alternatives as $alternative) {
    //             $Arrcriteria = [];
    //             foreach ($criterias as $criteria) {
    //                 foreach ($alternative->alternativecriteria as $alternativecriteria) {
    //                     $weightValue = 0;
    //                     $firstsub = null;
    //                     $secondsub = null;
    //                     $alternativeValue = convertion_value($alternativecriteria->value);
    //                     if ($alternativecriteria->criteria_id == $criteria->id) {
    //                         if ($criteria->type == 'benefit') {
    //                             $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
    //                             $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                             if ($firstsub > 0 & $secondsub > 0) {
    //                                 $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                             } else {
    //                                 $weightValue = 0;
    //                             }
    //                         } else if ($criteria->type == 'cost') {
    //                             $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
    //                             $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
    //                             if ($firstsub > 0 & $secondsub > 0) {
    //                                 $weightValue = bcdiv($firstsub, $secondsub, 3);
    //                             } else {
    //                                 $weightValue = 0;
    //                             }
    //                         }

    //                         $Arrcriteria[$criteria->id] = $weightValue;
    //                     }
    //                 }
    //             }

    //             $totalNilaiAkhir = 0;
    //             $newArrcriteria = [];
    //             foreach ($Arrcriteria as $indexACT => $ACT) {
    //                 $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
    //                 $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
    //                 $newArrcriteria[$indexACT] = $ACTmultiW;
    //             }

    //             $Incriteria = [];
    //             foreach ($alternative->alternativecriteria as $AC) {
    //                 if ($AC->value > 0) {
    //                     $Incriteria[] = $AC->criteria_id;
    //                 }
    //             }

    //             $nameCriterias = implode(', ', Criteria::whereIn('id', $Incriteria)->whereNotNull('short_name')->pluck('short_name')->toArray());

    //             $ArrNilaiAkhir[$alternative->name] = [
    //                 $totalNilaiAkhir,
    //                 'Rp ' . number_format($alternative->price, 0, ',', '.'),
    //                 $nameCriterias
    //             ];
    //         }

    //         $sorted = collect($ArrNilaiAkhir)->sortByDesc('0');
    //     } else {
    //         $CountAlternative = null;
    //         $CountCriteria = null;
    //         $sorted = null;
    //     }

    //     // dd($sorted);
    //     return view('Owner.non_coffe', compact(['alternatives', 'criteriasOrder', 'sorted']));
    // }
}
