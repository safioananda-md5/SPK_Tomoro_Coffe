@extends('layouts.main')
@push('title')
    Tambah Alternatif
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Settings Halaman Landing</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold">Form Settings</h4>
                    <small><em><span style="color:red">*</span> Menandakan kolom wajib diisi atau dipilih.</em></small><br>
                    <hr>
                    <form action="{{ route('admin.settings.store') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Judul Utama<span style="color:red">*</span></label>
                            <input type="text" name="main_title" id="main_title" value="{{ $Setting->main_title ?? '' }}"
                                class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Utama 1<span style="color:red">*</span></label>
                            <textarea class="form-control" name="main_desc_1" id="main_desc_1" rows="3">{{ $Setting->main_desc_1 ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Utama 2<span style="color:red">*</span></label>
                            <textarea class="form-control" name="main_desc_2" id="main_desc_2" rows="3">{{ $Setting->main_desc_2 ?? '' }}</textarea>
                        </div>
                        <div class="form-group">
                            <label>Judul Kedua<span style="color:red">*</span></label>
                            <input type="text" name="second_title" id="second_title"
                                value="{{ $Setting->second_title ?? '' }}" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Deskripsi Kedua<span style="color:red">*</span></label>
                            <textarea class="form-control" name="second_desc" id="second_desc" rows="3">{{ $Setting->second_desc ?? '' }}</textarea>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-danger" id="cancel">Batal</a>
                            <button type="submit" class="btn btn-primary me-2">Update</button>
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
@endsection
