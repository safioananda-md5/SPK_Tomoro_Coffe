@extends('layouts.main')
@push('title')
    Kriteria
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Kriteria</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($totalwieght > 100)
                        <div class="alert alert-danger" role="alert">
                            Bobot kriteria melebihi 100%, Nilai bobot: {{ $totalwieght }}%
                        </div>
                    @elseif($totalwieght < 100)
                        <div class="alert alert-warning" role="alert">
                            Bobot kriteria kurang dari 100%, Nilai bobot: {{ $totalwieght }}%
                        </div>
                    @endif
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 style="fw-bold">Data Kriteria</h4>
                        <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">Tambah Kriteria</a>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="criteriaTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%"> Nama Kriteria </th>
                                    <th width="20%"> Jenis </th>
                                    <th width="10%"> Bobot </th>
                                    <th width="20%"> Keterangan </th>
                                    <th width="20%"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criterias as $criteria)
                                    <tr>
                                        <td>{{ $criteria->name }}</td>
                                        <td>{{ Str::title($criteria->type) }}</td>
                                        <td>{{ $criteria->weight }}%</td>
                                        <td>{{ $criteria->description }}</td>
                                        <td>
                                            <a href="{{ route('admin.kriteria.edit', Crypt::encrypt($criteria->id)) }}"
                                                class="btn btn-outline-warning btn-fw">Edit</a>
                                            <button type="button"
                                                onclick="deleteCriteria('{{ Crypt::encrypt($criteria->id) }}')"
                                                class="btn btn-outline-danger btn-fw">Hapus</button>
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
        function deleteCriteria(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                cancelButtonText: "Batal",
                confirmButtonText: "Iya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin.kriteria.delete', ':id') }}";
                    $.ajax({
                        url: url.replace(':id', id),
                        type: "POST",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        data: {
                            _method: 'DELETE',
                            id: null,
                        },
                        success: function(response) {
                            Swal.fire({
                                title: "Terhapus!",
                                text: "Kriteria berhasil dihapus.",
                                icon: "success",
                                didClose: () => {
                                    window.location.href =
                                        "{{ route('admin.kriteria.index') }}";
                                }
                            });
                        },
                        error: function(xhr) {
                            let errorMsg = xhr.responseJSON.message || "Terjadi kesalahan.";
                            Swal.fire("Gagal!", errorMsg, "error");
                        }
                    });
                }
            });
        }

        $(document).ready(function() {
            $('#criteriaTable').DataTable({
                "columnDefs": [{
                        "orderable": false,
                        "targets": [3, 4]
                    },
                    {
                        "searchable": false,
                        "targets": [4]
                    }
                ]
            });

        });
    </script>
@endsection
