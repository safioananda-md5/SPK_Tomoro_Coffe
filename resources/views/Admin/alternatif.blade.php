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
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h4 style="fw-bold">Data Alternatif</h4>
                        <a href="{{ route('admin.alternatif.create') }}" class="btn btn-primary">Tambah Alternatif</a>
                    </div>
                    <div class="table-responsive mt-3">
                        <table class="table table-striped" id="alternatifTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%"> Nama Menu </th>
                                    @foreach ($criterias as $criteria)
                                        <th>{{ $criteria->name }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($alternatives as $alternative)
                                    <tr>
                                        <td>{{ $alternative->name }}</td>
                                        @foreach ($criterias as $criteria)
                                            @foreach ($alternative->alternativecriteria as $AC)
                                                @if ($AC->criteria_id == $criteria->id)
                                                    <td>{{ $AC->value }}</td>
                                                @endif
                                            @endforeach
                                        @endforeach
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
        // function deleteCriteria(id) {
        //     Swal.fire({
        //         title: "Yakin ingin menghapus?",
        //         text: "Data yang dihapus tidak dapat dikembalikan!",
        //         icon: "warning",
        //         showCancelButton: true,
        //         confirmButtonColor: "#3085d6",
        //         cancelButtonColor: "#d33",
        //         cancelButtonText: "Batal",
        //         confirmButtonText: "Iya, Hapus!"
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             let url = "{{ route('admin.kriteria.delete', ':id') }}";
        //             $.ajax({
        //                 url: url.replace(':id', id),
        //                 type: "POST",
        //                 headers: {
        //                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //                 },
        //                 data: {
        //                     _method: 'DELETE',
        //                     id: null,
        //                 },
        //                 success: function(response) {
        //                     Swal.fire({
        //                         title: "Terhapus!",
        //                         text: "Kriteria berhasil dihapus.",
        //                         icon: "success",
        //                         didClose: () => {
        //                             window.location.href =
        //                                 "{{ route('admin.kriteria.index') }}";
        //                         }
        //                     });
        //                 },
        //                 error: function(xhr) {
        //                     let errorMsg = xhr.responseJSON.message || "Terjadi kesalahan.";
        //                     Swal.fire("Gagal!", errorMsg, "error");
        //                 }
        //             });
        //         }
        //     });
        // }

        $(document).ready(function() {
            $('#alternatifTable').DataTable();
        });
    </script>
@endsection
