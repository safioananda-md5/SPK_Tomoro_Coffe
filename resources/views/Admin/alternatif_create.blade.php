@extends('layouts.main')
@push('title')
    Tambah Alternatif
@endpush
@section('content')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb border-0">
            <li class="breadcrumb-item"><a href="{{ route(Auth::user()->role . '.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.alternatif.index') }}">Alternatif</a></li>
            <li class="breadcrumb-item active" aria-current="page">Tambah Alternatif</li>
        </ol>
    </nav>
    <div class="row mt-3">
        <div class="col-sm-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="fw-bold">Form Tambah Alternatif</h4>
                    <small><em><span style="color:red">*</span> Menandakan kolom wajib diisi atau dipilih.</em></small>
                    <hr>
                    <form action="{{ route('admin.alternatif.store') }}" method="POST" id="TambahAlternatif"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>File upload<span style="color:red">*</span> <small>(.xlxs)</small></label>
                            <input type="hidden" id="fileName">
                            <input type="file" name="file" class="file-upload-default"
                                accept=".xls, .xlsx, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet">
                            <div class="input-group col-xs-12">
                                <input type="text" class="form-control file-upload-info" disabled=""
                                    placeholder="Upload File Excel">
                                <span class="input-group-append">
                                    <button class="file-upload-browse btn btn-inverse-dark" type="button">Pilih
                                        File</button>
                                </span>
                            </div>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.alternatif.index') }}" class="btn btn-outline-danger"
                                id="cancel">Batal</a>
                            <button type="button" id="SubmitForm" class="btn btn-primary me-2" disabled="true">Pilih file
                                dahulu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Loader --}}
    <div class="loader hidden">
        <div class="custom-loader d-flex flex-column justify-content-center align-items-center">
            <div class="spinner-border text-primary" style="width: 4rem; height: 4rem; border-width: 0.5rem;"
                role="status">
            </div>
            <h3 class="mt-5 text-primary">Sedang Mengunggah...</h3>
        </div>
    </div>
@endsection
@section('css')
    <style>
        .file-upload-browse {
            border-top-left-radius: 0;
            border-top-right-radius: 5px;
            border-bottom-right-radius: 5px;
            border-bottom-left-radius: 0;
        }
    </style>
@endsection
@section('scripts')
    <script>
        (function($) {
            'use strict';
            $(function() {
                $('.file-upload-browse').on('click', function() {
                    var file = $(this).parent().parent().parent().find('.file-upload-default');
                    file.trigger('click');
                });
                $('.file-upload-default').on('change', function() {
                    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i,
                        ''));
                    $('#fileName').val($(this).val().replace(/C:\\fakepath\\/i, '')).trigger('change');
                });
            });
        })(jQuery);

        $(function() {
            $('#fileName').on('change', function() {
                var fileName = $(this).val();
                console.log('Namafile ' + fileName);
                if (fileName) {
                    $('#SubmitForm').prop('disabled', false).html('Tambah');
                    $('#cancel').show();
                } else {
                    $('#SubmitForm').prop('disabled', true).html('Pilih file dahulu');
                    $('#cancel').show();
                }
            });

            $('#SubmitForm').on('click', function() {
                $(this).prop('disabled', true).html('Proses...');
                $('#cancel').hide();
                const form = $('#TambahAlternatif')[0];
                var fileName = $('#fileName').val();
                if (fileName) {
                    $('.loader').removeClass('hidden');
                    $(form).submit();
                } else {
                    $(this).prop('disabled', true).html('Pilih file dahulu');
                    $('#cancel').show();
                }
            })
        });
    </script>
@endsection
