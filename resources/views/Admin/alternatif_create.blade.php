@extends('layouts.main')
@push('title')
    @if ($edit)
        Edit
    @else
        Tambah
    @endif Alternatif
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.alternatif.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item active" aria-current="page">
                @if ($edit)
                    Edit
                @else
                    Tambah
                @endif Alternatif
            </li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold">Form @if ($edit)
                            Edit
                        @else
                            Tambah
                        @endif Alternatif</h4>
                    <small><em><span style="color:red">*</span> Menandakan kolom wajib diisi atau dipilih.</em></small><br>
                    <hr>

                    <form
                        @if ($edit) action="{{ route('admin.alternatif.update', Crypt::encrypt($alternative->id)) }}"
                @else
                    action="{{ route('admin.alternatif.store') }}" @endif
                        method="POST" id="TambahAlternatif">
                        @csrf
                        @if ($edit)
                            @method('PUT')
                        @endif
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Alternatif<span style="color:red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                @if ($edit) value="{{ $alternative->name }}" @endif
                                placeholder="Masukkan nama alternatif">
                        </div>
                        <div class="mb-3">
                            <label for="price" class="form-label">Harga Alternatif<span
                                    style="color:red">*</span></label>
                            <input type="number" class="form-control" id="price" name="price"
                                @if ($edit) value="{{ $alternative->price }}" @endif
                                placeholder="Masukkan harga alternatif">
                        </div>
                        <div class="mb-3">
                            <label for="category" class="form-label">Kategori Alternatif<span
                                    style="color:red">*</span></label>
                            <select class="form-select" name="category" aria-label="Default select example">
                                <option value="">-- Pilih Kategori --</option>
                                <option value="0"
                                    @if ($edit) @if ($alternative->category !== null && $alternative->category == 0) selected @endif
                                    @endif>Coffe</option>
                                <option value="1"
                                    @if ($edit) @if ($alternative->category !== null && $alternative->category == 1) selected @endif
                                    @endif>Non-Coffe</option>
                            </select>
                        </div>
                        <div class="d-flex align-items-center my-4">
                            <hr class="flex-grow-1">
                            <span class="px-3 text-muted fw-bold">Persentase Kandungan Bahan</span>
                            <hr class="flex-grow-1">
                        </div>
                        @foreach ($criterias as $criteria)
                            <div class="mb-3">
                                <label for="criteria_{{ $criteria->id }}" class="form-label">Kandungan
                                    {{ trim(str_ireplace('kandungan', '', $criteria->name)) }}<span
                                        style="color: red">*</span></label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" name="criteria[{{ $criteria->id }}]"
                                        id="criteria_{{ $criteria->id }}" placeholder="Masukkan persentase kandungan bahan"
                                        @if ($edit) value="{{ $alternative_critera->where('criteria_id', $criteria->id)->value('value') }}" @endif>
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        @endforeach
                        <div class="text-end">
                            <a href="{{ route('admin.alternatif.index') }}" class="btn btn-outline-danger"
                                id="cancel">Kembali</a>
                            <button type="submit" class="btn btn-primary me-2" id="SubmitForm">
                                @if ($edit)
                                    Edit
                                @else
                                    Tambah
                                @endif
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
    <style>
    </style>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#SubmitForm').on('click', function() {
                $(this).prop('disabled', true).html('Proses...');
                $('#cancel').hide();
                const form = $('#TambahAlternatif')[0];

                if (form.reportValidity()) {
                    $(form).submit();
                } else {
                    @if ($edit)
                        $(this).prop('disabled', false).html('Edit');
                    @else
                        $(this).prop('disabled', false).html('Tambah');
                    @endif
                    $('#cancel').show();
                }
            })
        })
    </script>
@endsection
