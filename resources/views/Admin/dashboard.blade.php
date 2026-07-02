@extends('layouts.main')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h5 class="text-muted">Good Morning, <span class="text-black fw-bold">{{ Auth::user()->name }}</span></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-start gap-5">
                                    <div>
                                        <p class="statistics-title">Jumlah Menu Total</p>
                                        <h3 class="rate-percentage">{{ $CountAlternative ?? 0 }}</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Jumlah Kriteria Total</p>
                                        <h3 class="rate-percentage">{{ $CountCriteria ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">10 Menu Terbaru
                                                        </h4>
                                                        <p class="card-subtitle card-subtitle-dash">Daftar menu yang baru
                                                            dimasukkan ke dalam sistem.</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table class="table select-table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <h6>No.</h6>
                                                                </th>
                                                                <th>
                                                                    <h6>Nama Menu</h6>
                                                                </th>
                                                                <th class="text-center">
                                                                    <h6>Kategori</h6>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($top10newalterantives)
                                                                @php
                                                                    $i = 1;
                                                                @endphp
                                                                @foreach ($top10newalterantives as $item)
                                                                    @if ($i <= 10)
                                                                        <tr>
                                                                            <td class="text-center">
                                                                                <h6>{{ $loop->iteration }}</h6>
                                                                            </td>
                                                                            <td>
                                                                                <h6>{{ $item->name }}</h6>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <h6>
                                                                                    {{ $item->category == 1 ? 'Non-Coffe' : 'Coffe' }}
                                                                                </h6>
                                                                            </td>
                                                                        </tr>
                                                                        @php
                                                                            $i++;
                                                                        @endphp
                                                                    @endif
                                                                @endforeach
                                                            @else
                                                                <td colspan="3" class="text-center">Periode belum
                                                                    ditentukan</td>
                                                            @endif
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
