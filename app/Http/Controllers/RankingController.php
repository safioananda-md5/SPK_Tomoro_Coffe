<?php

namespace App\Http\Controllers;

use App\Models\Alternative;
use App\Models\AlternativeCriteria;
use App\Models\Criteria;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function index()
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->get();
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

            $ArrNilaiAkhir[$alternative->name] = $totalNilaiAkhir;
        }

        $sorted = collect($ArrNilaiAkhir)->sortDesc();

        return view('admin.ranking', compact(['sorted']));
    }

    public function utility()
    {
        $criterias = Criteria::all();
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

        return view('admin.nilai_utility', compact(['criterias', 'alternativecriterias', 'utilityMax', 'utilityMin']));
    }

    public function bobotutility()
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->get();
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

            $bobotUtility[] = [
                'name' => $alternative->name,
                'criterias' => $Arrcriteria
            ];
        }

        return view('admin.bobot_utility', compact(['criterias', 'bobotUtility']));
    }

    public function nilaiakhir()
    {
        $criterias = Criteria::all();
        $alternatives = Alternative::with(['alternativecriteria'])->get();
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

            $ArrNilaiAkhir[] = [
                'name' => $alternative->name,
                'criterias' => $newArrcriteria,
                'nilaiakhir' => $totalNilaiAkhir
            ];
        }

        return view('admin.nilai_akhir', compact(['criterias', 'ArrNilaiAkhir']));
    }
}
