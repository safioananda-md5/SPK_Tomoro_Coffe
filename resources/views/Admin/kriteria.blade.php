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
    <a href="{{ route('admin.dashboard') }}" class="btn btn-dark">&leftarrow; <span class="ms-2">Kembali</span></a>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center">
                            <h5 class="card-title m-0 p-0" style="fw-bold">Data Kriteria</h5>
                            @if ($totalwieght == 100)
                                <span class="badge bg-success rounded-pill ms-3">Total Bobot Sudah 100%</span>
                            @endif
                        </div>
                        <div>
                            {{-- <!-- Button trigger modal -->
                            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#bobot">
                                Atur Bobot
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="bobot" tabindex="-1" aria-labelledby="bobotModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <div>
                                                <h1 class="modal-title fs-5" id="bobotModalLabel">Atur Bobot</h1>
                                                <div class="d-flex flex-column">
                                                    <em><small><span style="color: red">*</span>Menandakan kolom wajib
                                                            diisi.</small></em>
                                                    <em><small><span style="color: black">*</span>Bobot total wajib
                                                            100%</small></em>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('admin.kriteria.updatebobot') }}" method="POST"
                                            id="updatebobotform">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                @foreach ($criterias as $criteria)
                                                    <div class="mb-3">
                                                        <label for="weight_{{ $criteria->id }}" class="form-label">Bobot
                                                            Kriteria
                                                            {{ $criteria->name }}<span style="color: red">*</span></label>
                                                        <div class="input-group mb-3">
                                                            <input type="number" class="form-control"
                                                                name="weight[{{ $criteria->id }}]"
                                                                id="weight_{{ $criteria->id }}"
                                                                @if ($criteria->weight > 0) value="{{ $criteria->weight }}" @endif>
                                                            <span class="input-group-text">%</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-outline-danger"
                                                    data-bs-dismiss="modal" id="cancel">Batal</button>
                                                <button type="submit" class="btn btn-primary"
                                                    id="SubmitForm">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div> --}}
                            <a href="{{ route('admin.kriteria.create') }}" class="btn btn-primary">Tambah Kriteria</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    @if ($totalwieght != 100)
                        <div class="alert alert-warning" role="alert">
                            Bobot kriteria kurang dari 100%, Nilai bobot hanya {{ $totalwieght }}%, kurang
                            {{ 100 - $totalwieght }}%.
                        </div>
                    @endif
                    @if ($someempty)
                        <div class="alert alert-danger" role="alert">
                            Terdapat bobot kriteria bernilai 0.
                        </div>
                    @endif
                    <div class="table-responsive mt-3">
                        <table class="table table-hover" id="criteriaTable" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%" class="text-center"> Nama Kriteria </th>
                                    <th width="20%" class="text-center"> Jenis </th>
                                    <th width="10%" class="text-center"> Bobot </th>
                                    <th width="20%" class="text-center"> Aksi </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($criterias as $criteria)
                                    <tr @if ($criteria->weight <= 0) class="table-danger" @endif>
                                        <td class="text-center">{{ $criteria->name }}</td>
                                        <td class="text-center">{{ Str::title($criteria->type) }}</td>
                                        <td class="text-center">{{ $criteria->weight }}%
                                            @if ($criteria->weight <= 0)
                                                <span class="ms-2 badge rounded-pill text-bg-danger">Bobot keriteria
                                                    dilarang
                                                    0.</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('admin.kriteria.edit', Crypt::encrypt($criteria->id)) }}"
                                                class="btn btn-warning btn-fw">Edit</a>
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
                "order": [],
                "columnDefs": [{
                        "orderable": false,
                        "targets": [3]
                    },
                    {
                        "searchable": false,
                        "targets": [3]
                    }
                ]
            });

            $('#SubmitForm').on('click', function() {
                $(this).prop('disabled', true).html('Proses...');
                $('#cancel').hide();
                const form = $('#updatebobotform')[0];

                if (form.reportValidity()) {
                    $(form).submit();
                } else {
                    $(this).prop('disabled', false).html('Simpan');
                    $('#cancel').show();
                }
            })
        });
    </script>
@endsection
