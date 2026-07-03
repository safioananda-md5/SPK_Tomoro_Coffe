@extends('layouts.main')
@push('title')
    Alternatif
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Alternatif</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="card-title m-0 p-0" style="fw-bold">Data Alternatif</h5>
                        <div>
                            @if (count($alternatives) > 0)
                                <button type="button" class="btn btn-outline-danger" onclick="deleteAllAlternative()">Hapus
                                    Seluruh Alternatif</button>
                            @endif
                            <a href="{{ route('admin.alternatif.import') }}" class="btn btn-success">
                                <i class="fa fa-file me-2"></i>
                                Import Alternatif
                            </a>
                            <a href="{{ route('admin.alternatif.create') }}" class="btn btn-primary">
                                Tambah Manual Alternatif
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="alternatifTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="5%"> Aksi </th>
                                    <th width="30%"> Nama Menu </th>
                                    <th width="30%"> Kategori </th>
                                    @foreach ($criterias as $criteria)
                                        <th>{{ $criteria->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatives as $alternative)
                                    <tr>
                                        <td class="text-center">
                                            <a href="{{ route('admin.alternatif.edit', Crypt::encrypt($alternative->id)) }}"
                                                class="btn btn-sm btn-warning">Edit</a>
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="deleteAlternative('{{ Crypt::encrypt($alternative->id) }}')">Hapus</button>
                                        </td>
                                        <td>{{ $alternative->name }}</td>
                                        <td>{{ $alternative->category == 1 ? 'Non-Coffe' : 'Coffe' }}</td>
                                        @foreach ($criterias as $criteria)
                                            @foreach ($alternative->alternativecriteria as $AC)
                                                @php
                                                    $ada = false;
                                                @endphp
                                                @if ($AC->criteria_id == $criteria->id)
                                                    @php
                                                        $ada = true;
                                                    @endphp
                                                    <td class="text-center">{{ $AC->value }}</td>
                                                @endif
                                            @endforeach
                                        @endforeach
                                        @if (!$ada)
                                            <td class="text-center">0</td>
                                        @endif
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
        .iconHover {
            cursor: pointer;
        }

        .iconHover:hover {
            font-weight: bold;
        }
    </style>
@endsection
@section('scripts')
    <script>
        function deleteAllAlternative() {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-outline-danger ms-2",
                    cancelButton: "btn btn-primary"
                },
                cancelButtonText: "Batal",
                confirmButtonText: "Iya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin.alternatif.alldelete') }}";
                    $.ajax({
                        url: url,
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
                                text: "Seluruh Alternatif berhasil dihapus.",
                                icon: "success",
                                didClose: () => {
                                    window.location.href =
                                        "{{ route('admin.alternatif.index') }}";
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

        function deleteAlternative(id) {
            Swal.fire({
                title: "Yakin ingin menghapus?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: "btn btn-outline-danger ms-2",
                    cancelButton: "btn btn-primary"
                },
                cancelButtonText: "Batal",
                confirmButtonText: "Iya, Hapus!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let url = "{{ route('admin.alternatif.delete', ':id') }}";
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
                                text: "Alternatif berhasil dihapus.",
                                icon: "success",
                                didClose: () => {
                                    window.location.href =
                                        "{{ route('admin.alternatif.index') }}";
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
            $('#alternatifTable').DataTable({
                "order": [
                    [1]
                ],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [0]
                    },
                    {
                        "searchable": false,
                        "targets": [0]
                    }
                ]
            });
        });
    </script>
@endsection
