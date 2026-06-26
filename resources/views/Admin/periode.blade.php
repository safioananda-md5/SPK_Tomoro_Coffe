@extends('layouts.main')
@push('title')
    Periode Perangkingan
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Periode Perangkingan</li>
        </ol>
    </nav>
    <div class="row">
        <div class="col-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 style="fw-bold">Data Periode</h4>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalPerangkingan">
                            Buat Perangkingan
                        </button>
                        <div class="modal fade" id="modalPerangkingan" tabindex="-1"
                            aria-labelledby="modalPerangkinganLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modalPerangkinganLabel">Konfirmasi Tindakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        Apakah Anda yakin ingin membuat data perangkingan sekarang?
                                        <br>
                                        <small class="text-muted">Proses ini akan menggunakan seluruh data terbaru dari
                                            sistem.</small>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-danger"
                                            data-bs-dismiss="modal">Batal</button>

                                        <form action="{{ route('admin.perangkingan.post_periode') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary">Ya, Buat Sekarang</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table" id="periodeTable" width="100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Periode</th>
                                    <th class="text-center">Jumlah Alternatif</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($Periodes as $Periode)
                                    <tr>
                                        <td class="text-center">
                                            {{ $Periode->name }}
                                            @if ($Periode->id == $latest)
                                                <span class="badge rounded-pill text-bg-primary ms-3">Periode Aktif</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ count($Periode->alternatives) }}</td>
                                        <td class="text-center">
                                            @if ($Periode->is_equal)
                                                <a href="{{ route('admin.perangkingan.index', Crypt::encrypt($Periode->id)) }}"
                                                    class="btn btn-primary">
                                                    Detail
                                                </a>
                                            @else
                                                <span class="badge bg-danger rounded-pill mb-3 text-wrap">Data kriteria yang
                                                    digunakan
                                                    pada periode ini
                                                    tidak sesuai dengan kriteria aktif saat ini.</span>
                                                <br>
                                            @endif
                                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modalHapus">
                                                Hapus Periode
                                            </button>
                                            <div class="modal fade" id="modalHapus" tabindex="-1"
                                                aria-labelledby="modalHapusLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="modalHapusLabel">Konfirmasi Hapus
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body text-center">
                                                            <div class="mb-3">
                                                                <i class="bi bi-exclamation-triangle text-danger"
                                                                    style="font-size: 3rem;"></i>
                                                            </div>
                                                            <p>Apakah Anda yakin ingin menghapus data periode ini?</p>
                                                            <strong class="text-danger">Tindakan ini tidak dapat
                                                                dibatalkan!</strong>
                                                        </div>
                                                        <div class="modal-footer justify-content-center">
                                                            <button type="button" class="btn btn-outline-danger"
                                                                data-bs-dismiss="modal">Batal</button>
                                                            <form
                                                                action="{{ route('admin.perangkingan.delete_periode', Crypt::encrypt($Periode->id)) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Ya, Hapus
                                                                    Data</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
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
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#periodeTable').DataTable({
                "order": [
                    [0, 'asc']
                ],
                "columnDefs": [{
                    "orderable": false,
                    "targets": [1]
                }, ]
            });
        });
    </script>
@endsection
