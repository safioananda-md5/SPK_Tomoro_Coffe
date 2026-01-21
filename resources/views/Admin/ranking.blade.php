@extends('layouts.main')
@push('title')
    Perangkingan
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Perangkingan</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 mb-3">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" disabled>Ranking</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('admin.perangkingan.utility') }}">Nilai Utility</a>
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
                        <h4 style="fw-bold">Hasil Perangkingan</h4>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="rankingTable" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Ranking</th>
                                    <th>Nama Menu</th>
                                    <th class="text-center">Nilai</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sorted as $name => $item)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>{{ $name }}</td>
                                        <td class="text-center">{{ $item }}</td>
                                    </tr>
                                @endforeach
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
    <script>
        $(document).ready(function() {
            $('#rankingTable').DataTable({
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": []
            });
        });
    </script>
@endsection
