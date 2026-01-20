@extends('layouts.main')
@push('title')
    @if ($edit)
        Edit Kriteria
    @else
        Tambah Kriteria
    @endif
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.kriteria.index') }}">Kriteria</a></li>
            @if ($edit)
                <li class="breadcrumb-item active" aria-current="page">Edit Kriteria</li>
            @else
                <li class="breadcrumb-item active" aria-current="page">Tambah Kriteria</li>
            @endif
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($edit)
                        <h4 class="fw-bold">Form Edit Kriteria</h4>
                    @else
                        <h4 class="fw-bold">Form Tambah Kriteria</h4>
                    @endif

                    <small><em><span style="color:red">*</span> Menandakan kolom wajib diisi atau dipilih.</em></small>
                    <hr>
                    @if ($edit)
                        <form class="mt-3" action="{{ route('admin.kriteria.update', Crypt::encrypt($criteria->id)) }}"
                            method="POST" id="TambahKriteria">
                            @method('PUT')
                        @else
                            <form class="mt-3" action="{{ route('admin.kriteria.store') }}" method="POST"
                                id="TambahKriteria">
                    @endif
                    @csrf
                    <div class="form-group">
                        <label for="name">Nama Kriteria<span style="color:red">*</span></label>
                        <input type="text" name="name" class="form-control" id="name"
                            placeholder="Masukkan Nama Kriteria"
                            @if ($edit) value="{{ $criteria->name }}" @endif required>
                    </div>
                    <div class="form-group">
                        <label for="type">Jenis Keriteria<span style="color:red">*</span></label>
                        <select class="form-select" name="type" id="type" required>
                            <option value="">-- Pilih Jenis Kriteria --</option>
                            <option value="benefit"
                                @if ($edit) @if ($criteria->type == 'benefit')
                                        selected @endif
                                @endif>Benefit</option>
                            <option value="cost"
                                @if ($edit) @if ($criteria->type == 'cost')
                                        selected @endif
                                @endif>Cost</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="weight">Bobot Kriteria<span style="color:red">*</span></label>
                        <div class="input-group">
                            <input type="number" name="weight" class="form-control" id="weight"
                                placeholder="Masukkan Bobot Kriteria"
                                @if ($edit) value="{{ $criteria->weight }}" @endif required>
                            <span class="input-group-text">%</span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description">Keterangan Kriteria <small>(Opsional)</small></label>
                        @if ($edit)
                            <textarea class="form-control" id="description" rows="4">{{ $criteria->description }}</textarea>
                        @else
                            <textarea class="form-control" id="description" rows="4"></textarea>
                        @endif

                    </div>
                    <div class="text-end">
                        <a href="{{ route('admin.kriteria.index') }}" class="btn btn-outline-danger"
                            id="cancel">Batal</a>
                        @if ($edit)
                            <button type="button" id="SubmitForm" class="btn btn-primary me-2">Edit</button>
                        @else
                            <button type="button" id="SubmitForm" class="btn btn-primary me-2">Tambah</button>
                        @endif
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('css')
@endsection
@section('scripts')
    <script>
        @if ($edit)
            $(function() {
                $('#SubmitForm').on('click', function() {
                    $(this).prop('disabled', true).html('Proses...');
                    $('#cancel').hide();
                    const form = $('#TambahKriteria')[0];

                    if (form.reportValidity()) {
                        $(form).submit();
                    } else {
                        $(this).prop('disabled', false).html('Edit');
                        $('#cancel').show();
                    }
                })
            });
        @else
            $(function() {
                $('#SubmitForm').on('click', function() {
                    $(this).prop('disabled', true).html('Proses...');
                    $('#cancel').hide();
                    const form = $('#TambahKriteria')[0];

                    if (form.reportValidity()) {
                        $(form).submit();
                    } else {
                        $(this).prop('disabled', false).html('Tambah');
                        $('#cancel').show();
                    }
                })
            });
        @endif
    </script>
@endsection
