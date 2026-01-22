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
                                        <p class="statistics-title">Jumlah Menu</p>
                                        <h3 class="rate-percentage">{{ $CountAlternative }}</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Jumlah Kriteria</p>
                                        <h3 class="rate-percentage">{{ $CountCriteria }}</h3>
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
                                                        <h4 class="card-title card-title-dash">Rangking Menu</h4>
                                                        <p class="card-subtitle card-subtitle-dash">Menu terbaik dari yang
                                                            terbaik berdasarkan komposisinya</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table class="table select-table" id="rankingTable">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <h6>Ranking</h6>
                                                                </th>
                                                                <th>
                                                                    <h6>Nama Menu</h6>
                                                                </th>
                                                                <th class="text-center">
                                                                    <h6>Score</h6>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @if ($sorted)
                                                                @foreach ($sorted as $name => $item)
                                                                    <tr>
                                                                        <td class="text-center">
                                                                            <h6>{{ $loop->iteration }}</h6>
                                                                        </td>
                                                                        <td>
                                                                            <h6>{{ $name }}</h6>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <h6>{{ $item }}</h6>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            @else
                                                                <tr>
                                                                    <td colspan="3" class="text-center">Tidak ada data
                                                                        alternatif</td>
                                                                </tr>
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
@section('scripts')
    @if ($sorted)
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
    @endif
@endsection
