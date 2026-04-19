<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeCriteria;
use App\Models\Criteria;
use App\Models\Periode;
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

    public function periode()
    {
        $Periodes = periode::orderBy('id', 'desc')->get();
        return view('Admin.periode', compact(['Periodes']));
    }

    public function post_periode()
    {
        try {
            DB::beginTransaction();
            Periode::create([
                'name' => now()->format('d/m/Y H:i:s'),
                'alternatives' => Alternative::pluck('id')->toArray(),
            ]);
            DB::commit();
            flash()->success('Priode pengujian berhasil dibuat.');
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

    public function delete_periode($id)
    {
        try {
            DB::beginTransaction();
            Periode::where('id', $id)->delete();
            DB::commit();
            flash()->success('Priode pengujian berhasil dihapus.');
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

    public function index($id)
    {
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
        return view('admin.ranking', compact(['sorted', 'AAA', 'id']));
    }

    public function utility($id)
    {
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
        return view('admin.nilai_utility', compact(['criterias', 'alternativecriterias', 'utilityMax', 'utilityMin', 'AAA', 'id']));
    }

    public function bobotutility($id)
    {
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
        return view('admin.bobot_utility', compact(['criterias', 'bobotUtility', 'AAA', 'id']));
    }

    public function nilaiakhir($id)
    {
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
        return view('admin.nilai_akhir', compact(['criterias', 'ArrNilaiAkhir', 'AAA', 'id']));
    }
}
