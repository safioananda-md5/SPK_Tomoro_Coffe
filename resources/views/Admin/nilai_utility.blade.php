@extends('layouts.main')
@push('title')
    Perangkingan
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.perangkingan.index') }}">Perangkingan</a></li>
            <li class="breadcrumb-item active" aria-current="page">Nilai Utility</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.perangkingan.index') }}">Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" disabled>Nilai Utility</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.perangkingan.bobotutility') }}">Bobot Utility</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.perangkingan.nilaiakhir') }}">Nilai Akhir</a>
                </li>
            </ul>
        </div>
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 style="fw-bold">Perhitungan Nilai Utility</h4>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="rankingTable" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Keterangan</th>
                                    @foreach ($criterias as $criteria)
                                        <th>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="text-center">{{ $criteria->name }}</div>
                                                <div class="text-center" style="font-weight: normal; font-size: 12px">
                                                    {{ Str::title($criteria->type) }}</div>
                                            </div>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="text-center">C max</td>
                                    @foreach ($criterias as $criteria)
                                        <td class="text-center">{{ $utilityMax[$criteria->id] }}</td>
                                    @endforeach
                                </tr>
                                <tr>
                                    <td class="text-center">C min</td>
                                    @foreach ($criterias as $criteria)
                                        <td class="text-center">{{ $utilityMin[$criteria->id] }}</td>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <style>
        .nav-tabs .nav-link.active {
            font-weight: bold !important;
        }

        .nav-tabs .nav-link {
            background: none;
            color: #000000;
            border-radius: 0;
            border: none;
            padding: 0.75rem 1.5rem;
        }

        .nav-tabs .nav-link:hover {
            background: lightgrey;
            color: #000000;
            border-radius: 0;
            border: none;
            padding: 0.75rem 1.5rem;
        }
    </style>
@endsection
@section('scripts')
@endsection
