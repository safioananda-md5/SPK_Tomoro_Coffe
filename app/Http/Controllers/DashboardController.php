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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class DashboardController extends Controller
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
                'CODE' => (int) $loop,
            ];
        }
    }

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

        $periode = Periode::latest()->first();

        if ($periode) {
            $CountCriteria = count(Criteria::all());
            $CountAlternative = count(Alternative::whereIn('id', $periode->alternatives)->get());
        } else {
            $CountCriteria = 0;
            $CountAlternative = 0;
        }

        if ($CountCriteria > 0 && $CountAlternative > 2 && $periode) {
            $criterias = Criteria::all();
            $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();
            $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('alternative_id', $periode->alternatives)->get();
            $utilityMax = [];
            $utilityMin = [];

            $convertionCriteria = [];
            foreach ($criterias as $criteria) {
                $arrvalueraw = [];
                foreach ($alternativecriterias as $alternativecriteria) {
                    if ($alternativecriteria->criteria_id == $criteria->id) {
                        $alternativeValue = convertion_value($alternativecriteria->value);
                        $arrvalueraw[] = $alternativeValue;
                    }
                }
                $utilityMax[$criteria->id] = max($arrvalueraw);
                $utilityMin[$criteria->id] = min($arrvalueraw);
                $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
            }

            $ArrNilaiAkhir = [];
            foreach ($alternatives as $alternative) {
                $Arrcriteria = [];
                foreach ($criterias as $criteria) {
                    foreach ($alternative->alternativecriteria as $alternativecriteria) {
                        $weightValue = 0;
                        $firstsub = null;
                        $secondsub = null;
                        $alternativeValue = convertion_value($alternativecriteria->value);
                        if ($alternativecriteria->criteria_id == $criteria->id) {
                            if ($criteria->type == 'benefit') {
                                $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
                                $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                                if ($firstsub > 0 & $secondsub > 0) {
                                    $weightValue = bcdiv($firstsub, $secondsub, 3);
                                } else {
                                    $weightValue = 0;
                                }
                            } else if ($criteria->type == 'cost') {
                                $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
                                $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                                if ($firstsub > 0 & $secondsub > 0) {
                                    $weightValue = bcdiv($firstsub, $secondsub, 3);
                                } else {
                                    $weightValue = 0;
                                }
                            }

                            $Arrcriteria[$criteria->id] = $weightValue;
                        }
                    }
                }

                $totalNilaiAkhir = 0;
                $newArrcriteria = [];
                foreach ($Arrcriteria as $indexACT => $ACT) {
                    $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
                    $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
                    $newArrcriteria[$indexACT] = $ACTmultiW;
                }

                $ArrNilaiAkhir[$alternative->name] = $totalNilaiAkhir;
            }

            $sorted = collect($ArrNilaiAkhir)->sortDesc();
        } else {
            $CountAlternative = null;
            $CountCriteria = null;
            $sorted = null;
        }

        return view(Str::title(Auth::user()->role) . '.dashboard', compact(['greeting', 'CountAlternative', 'CountCriteria', 'sorted']));
    }

    public function landing()
    {
        if (isset(Auth::user()->id)) {
            return redirect(route('login'));
        }

        $periode = Periode::latest()->first();
        if ($periode) {
            $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();
            $criteriasOrder = Criteria::orderBy('weight', 'desc')->get();

            $CountCriteria = count(Criteria::all());
            $CountAlternative = count(Alternative::whereIn('id', $periode->alternatives)->get());

            if ($CountCriteria > 0 && $CountAlternative > 2) {
                $criterias = Criteria::all();
                $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();
                $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('alternative_id', $periode->alternatives)->get();
                $utilityMax = [];
                $utilityMin = [];

                $convertionCriteria = [];
                foreach ($criterias as $criteria) {
                    $arrvalueraw = [];
                    foreach ($alternativecriterias as $alternativecriteria) {
                        if ($alternativecriteria->criteria_id == $criteria->id) {
                            $alternativeValue = convertion_value($alternativecriteria->value);
                            $arrvalueraw[] = $alternativeValue;
                        }
                    }
                    $utilityMax[$criteria->id] = max($arrvalueraw);
                    $utilityMin[$criteria->id] = min($arrvalueraw);
                    $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
                }

                $ArrNilaiAkhir = [];
                foreach ($alternatives as $alternative) {
                    $Arrcriteria = [];
                    foreach ($criterias as $criteria) {
                        foreach ($alternative->alternativecriteria as $alternativecriteria) {
                            $weightValue = 0;
                            $firstsub = null;
                            $secondsub = null;
                            $alternativeValue = convertion_value($alternativecriteria->value);
                            if ($alternativecriteria->criteria_id == $criteria->id) {
                                if ($criteria->type == 'benefit') {
                                    $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
                                    $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                                    if ($firstsub > 0 & $secondsub > 0) {
                                        $weightValue = bcdiv($firstsub, $secondsub, 3);
                                    } else {
                                        $weightValue = 0;
                                    }
                                } else if ($criteria->type == 'cost') {
                                    $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
                                    $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                                    if ($firstsub > 0 & $secondsub > 0) {
                                        $weightValue = bcdiv($firstsub, $secondsub, 3);
                                    } else {
                                        $weightValue = 0;
                                    }
                                }

                                $Arrcriteria[$criteria->id] = $weightValue;
                            }
                        }
                    }

                    $totalNilaiAkhir = 0;
                    $newArrcriteria = [];
                    foreach ($Arrcriteria as $indexACT => $ACT) {
                        $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
                        $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
                        $newArrcriteria[$indexACT] = $ACTmultiW;
                    }

                    $Incriteria = [];
                    foreach ($alternative->alternativecriteria as $AC) {
                        if ($AC->value > 0) {
                            $Incriteria[] = $AC->criteria_id;
                        }
                    }

                    $nameCriterias = implode(', ', Criteria::whereIn('id', $Incriteria)->whereNotNull('short_name')->pluck('short_name')->toArray());

                    $ArrNilaiAkhir[$alternative->name] = [
                        $totalNilaiAkhir,
                        'Rp ' . number_format($alternative->price, 0, ',', '.'),
                        $nameCriterias
                    ];
                }

                $sorted = collect($ArrNilaiAkhir)->sortByDesc('0');
            } else {
                $CountAlternative = null;
                $CountCriteria = null;
                $sorted = null;
            }
        } else {
            $CountAlternative = null;
            $CountCriteria = null;
            $sorted = null;
            $alternatives = [];
            $criteriasOrder = Criteria::orderBy('weight', 'desc')->get();
        }

        $Setting = Setting::latest()->first();

        $latestPeriode = Periode::latest()->first()?->id ?? '';
        return view('Owner.dashboard', compact(['alternatives', 'latestPeriode', 'criteriasOrder', 'sorted', 'Setting']));
    }

    public function perhitungan($id)
    {
        $id = Crypt::decrypt($id);
        $periode = periode::find($id);
        $criterias = Criteria::all();
        $alternativecriterias = AlternativeCriteria::with(['alternative'])->whereIn('id', $periode->alternatives)->get();

        if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
            flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
            return redirect(route('admin.dashboard'));
        }

        $utilityMax = [];
        $utilityMin = [];
        foreach ($criterias as $criteria) {
            $arrvalueraw = [];
            foreach ($alternativecriterias as $alternativecriteria) {
                if ($alternativecriteria->criteria_id == $criteria->id) {
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    $arrvalueraw[] = $alternativeValue;
                }
            }
            $utilityMax[$criteria->id] = max($arrvalueraw);
            $utilityMin[$criteria->id] = min($arrvalueraw);
        }

        $AAA = $this->globalAlternatives;
        return view('Owner.nilai_utility', compact(['criterias', 'alternativecriterias', 'utilityMax', 'utilityMin', 'AAA', 'id']));
    }

    public function bobotutility($id)
    {
        $id = Crypt::decrypt($id);
        $periode = periode::find($id);
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

        if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
            flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
            return redirect(route('admin.dashboard'));
        }

        $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
        $utilityMax = [];
        $utilityMin = [];
        foreach ($criterias as $criteria) {
            $arrvalueraw = [];
            foreach ($alternativecriterias as $alternativecriteria) {
                if ($alternativecriteria->criteria_id == $criteria->id) {
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    $arrvalueraw[] = $alternativeValue;
                }
            }
            $utilityMax[$criteria->id] = max($arrvalueraw);
            $utilityMin[$criteria->id] = min($arrvalueraw);
        }

        $bobotUtility = [];
        foreach ($alternatives as $alternative) {
            $Arrcriteria = [];
            foreach ($criterias as $criteria) {
                foreach ($alternative->alternativecriteria as $alternativecriteria) {
                    $weightValue = 0;
                    $firstsub = null;
                    $secondsub = null;
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    if ($alternativecriteria->criteria_id == $criteria->id) {
                        if ($criteria->type == 'benefit') {
                            $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        } else if ($criteria->type == 'cost') {
                            $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        }

                        $Arrcriteria[$criteria->id] = $weightValue;
                    }
                }
            }

            $bobotUtility[$alternative->id] = [
                'name' => $alternative->name,
                'criterias' => $Arrcriteria
            ];
        }

        $AAA = $this->globalAlternatives;
        return view('Owner.bobot_utility', compact(['criterias', 'bobotUtility', 'AAA', 'id']));
    }

    public function nilaiakhir($id)
    {
        $id = Crypt::decrypt($id);
        $periode = periode::find($id);
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

        if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
            flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
            return redirect(route('admin.dashboard'));
        }

        $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
        $utilityMax = [];
        $utilityMin = [];

        $convertionCriteria = [];
        foreach ($criterias as $criteria) {
            $arrvalueraw = [];
            foreach ($alternativecriterias as $alternativecriteria) {
                if ($alternativecriteria->criteria_id == $criteria->id) {
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    $arrvalueraw[] = $alternativeValue;
                }
            }
            $utilityMax[$criteria->id] = max($arrvalueraw);
            $utilityMin[$criteria->id] = min($arrvalueraw);
            $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
        }

        $ArrNilaiAkhir = [];
        foreach ($alternatives as $alternative) {
            $Arrcriteria = [];
            foreach ($criterias as $criteria) {
                foreach ($alternative->alternativecriteria as $alternativecriteria) {
                    $weightValue = 0;
                    $firstsub = null;
                    $secondsub = null;
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    if ($alternativecriteria->criteria_id == $criteria->id) {
                        if ($criteria->type == 'benefit') {
                            $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        } else if ($criteria->type == 'cost') {
                            $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        }

                        $Arrcriteria[$criteria->id] = $weightValue;
                    }
                }
            }

            $totalNilaiAkhir = 0;
            $newArrcriteria = [];
            foreach ($Arrcriteria as $indexACT => $ACT) {
                $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
                $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
                $newArrcriteria[$indexACT] = $ACTmultiW;
            }

            $ArrNilaiAkhir[$alternative->id] = [
                'name' => $alternative->name,
                'criterias' => $newArrcriteria,
                'nilaiakhir' => $totalNilaiAkhir
            ];
        }

        $AAA = $this->globalAlternatives;
        return view('Owner.nilai_akhir', compact(['criterias', 'ArrNilaiAkhir', 'AAA', 'id']));
    }

    public function ranking($id)
    {
        $id = Crypt::decrypt($id);
        $periode = periode::find($id);
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->whereIn('id', $periode->alternatives)->get();

        if (count($criterias = Criteria::all()) < 1 || count(Alternative::with(['alternativecriteria'])->get()) < 2) {
            flash()->error('Data kriteria atau alternatif tidak memenuhi syarat perhitungan.');
            return redirect(route('admin.dashboard'));
        }

        $alternativecriterias = AlternativeCriteria::with(['alternative'])->get();
        $utilityMax = [];
        $utilityMin = [];

        $convertionCriteria = [];
        foreach ($criterias as $criteria) {
            $arrvalueraw = [];
            foreach ($alternativecriterias as $alternativecriteria) {
                if ($alternativecriteria->criteria_id == $criteria->id) {
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    $arrvalueraw[] = $alternativeValue;
                }
            }
            $utilityMax[$criteria->id] = max($arrvalueraw);
            $utilityMin[$criteria->id] = min($arrvalueraw);
            $convertionCriteria[$criteria->id] = bcdiv($criteria->weight, 100, 3);
        }

        $ArrNilaiAkhir = [];
        foreach ($alternatives as $alternative) {
            $Arrcriteria = [];
            foreach ($criterias as $criteria) {
                foreach ($alternative->alternativecriteria as $alternativecriteria) {
                    $weightValue = 0;
                    $firstsub = null;
                    $secondsub = null;
                    $alternativeValue = convertion_value($alternativecriteria->value);
                    if ($alternativecriteria->criteria_id == $criteria->id) {
                        if ($criteria->type == 'benefit') {
                            $firstsub = bcsub($alternativeValue, $utilityMin[$criteria->id], 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        } else if ($criteria->type == 'cost') {
                            $firstsub = bcsub($utilityMax[$criteria->id], $alternativeValue, 3);
                            $secondsub = bcsub($utilityMax[$criteria->id], $utilityMin[$criteria->id], 3);
                            if ($firstsub > 0 & $secondsub > 0) {
                                $weightValue = bcdiv($firstsub, $secondsub, 3);
                            } else {
                                $weightValue = 0;
                            }
                        }

                        $Arrcriteria[$criteria->id] = $weightValue;
                    }
                }
            }

            $totalNilaiAkhir = 0;
            $newArrcriteria = [];
            foreach ($Arrcriteria as $indexACT => $ACT) {
                $ACTmultiW = bcmul($ACT, $convertionCriteria[$indexACT], 3);
                $totalNilaiAkhir =  bcadd($totalNilaiAkhir, $ACTmultiW, 3);
                $newArrcriteria[$indexACT] = $ACTmultiW;
            }

            $ArrNilaiAkhir[$alternative->id] = $totalNilaiAkhir;
        }

        $sorted = collect($ArrNilaiAkhir)->sortDesc();

        $AAA = $this->globalAlternatives;
        return view('Owner.ranking', compact(['sorted', 'AAA', 'id']));
    }
}
