@extends('layouts.landing')
@push('title')
    SPK Menu Coffe
@endpush
@section('content')
    <div class="card card-body mx-3 mx-md-4" style="margin-top: 120px; margin-bottom: 120px;">
        <section class="pt-3 pb-4" id="technologies">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <h2 class="mb-3">SPK Menu Coffe</h2>
                    </div>
                </div>
                <div class="row">
                    @php
                        $i = 1;
                    @endphp
                    @if ($sorted)
                        @forelse ($sorted as $name => $item)
                            @if ($i <= 10)
                                <div class="col-md-4 col-lg-2 mb-5 d-flex sepuluh"> <a
                                        class="card h-100 w-100 shadow-none border border-radius-lg text-center d-flex flex-column"
                                        href="#" style="width: 200px; text-decoration: none; color: inherit;">
                                        <div class="avatar rounded-circle bg-white shadow mx-auto mt-n4 mb-3"
                                            style="min-height: 50px;">
                                            <div class="d-flex align-items-center justify-content-center bg-secondary"
                                                style="width: 50px; height: 50px; border-radius: 50%;">
                                                <span class="text-white h4 mb-0">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column">
                                            <img class="w-100 px-2" alt="Image placeholder"
                                                src="{{ asset('assets/material/img/coffee.jpg') }}"
                                                style="border-radius: 15px !important; display: block;">

                                            <h6 class="font-weight-bold mt-3">{{ $item[1] }}</h6>
                                            <h5 class="p-2 text-lg mb-0">{{ $name }}</h5>

                                            <div class="mt-auto pb-3 d-flex flex-column">
                                                <small class="text-muted font-weight-bold">Kombinasi
                                                    {{ $item[2] }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                <div class="col-md-4 col-lg-2 mb-5 d-flex selengkapnya"> <a
                                        class="card h-100 w-100 shadow-none border border-radius-lg text-center d-flex flex-column"
                                        href="#" style="width: 200px; text-decoration: none; color: inherit;">
                                        <div class="avatar rounded-circle bg-white shadow mx-auto mt-n4 mb-3"
                                            style="min-height: 50px;">
                                            <div class="d-flex align-items-center justify-content-center bg-secondary"
                                                style="width: 50px; height: 50px; border-radius: 50%;">
                                                <span class="text-white h4 mb-0">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column">
                                            <img class="w-100 px-2" alt="Image placeholder"
                                                src="{{ asset('assets/material/img/coffee.jpg') }}"
                                                style="border-radius: 15px !important; display: block;">

                                            <h6 class="font-weight-bold mt-3">{{ $item[1] }}</h6>
                                            <h5 class="p-2 text-lg mb-0">{{ $name }}</h5>

                                            <div class="mt-auto pb-3 d-flex flex-column">
                                                <small class="text-muted font-weight-bold">Kombinasi
                                                    {{ $item[2] }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @else
                                <div class="col-md-4 col-lg-2 mb-5 d-flex selengkapnya"> <a
                                        class="card h-100 w-100 shadow-none border border-radius-lg text-center d-flex flex-column"
                                        href="#" style="width: 200px; text-decoration: none; color: inherit;">
                                        <div class="avatar rounded-circle bg-white shadow mx-auto mt-n4 mb-3"
                                            style="min-height: 50px;">
                                            <div class="d-flex align-items-center justify-content-center bg-secondary"
                                                style="width: 50px; height: 50px; border-radius: 50%;">
                                                <span class="text-white h4 mb-0">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 d-flex flex-column">
                                            <img class="w-100 px-2" alt="Image placeholder"
                                                src="{{ asset('assets/material/img/coffee.jpg') }}"
                                                style="border-radius: 15px !important; display: block;">

                                            <h6 class="font-weight-bold mt-3">{{ $item[1] }}</h6>
                                            <h5 class="p-2 text-lg mb-0">{{ $name }}</h5>

                                            <div class="mt-auto pb-3 d-flex flex-column">
                                                <small class="text-muted font-weight-bold">Kombinasi
                                                    {{ $item[2] }}</small>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endif
                            @php
                                $i++;
                            @endphp
                        @empty
                            Tidak dapat menampilkan rekomendasi produk.
                        @endforelse
                    @else
                        <p class="text-center">Tidak dapat menampilkan rekomendasi produk.</p>
                    @endif
                </div>
                @if ($i > 10)
                    <div class="row">
                        <div class="col-12">
                            <a href="javascript:void(0)" class="link-info" id="sedikit">Tampilkan lebih sedikit</a>
                            <a href="javascript:void(0)" class="link-info" id="selengkapnya">Tampilkan menu
                                lainnya...</a>
                        </div>
                    </div>
                @endif
                @if ($sorted)
                    <div class="row mt-5">
                        <div class="col-12">
                            Hasil rekomendasi peringkat ini disusun berdasarkan analisis Sistem Pendukung Keputusan
                            menggunakan
                            Metode SMART <i>(Simple Multi-Attribute Rating Technique)</i>. Metode ini mengevaluasi setiap
                            alternatif
                            berdasarkan pembobotan nilai kriteria secara linier untuk menghasilkan keputusan yang transparan
                            dan
                            akurat. Detail proses dan tahapan perhitungan dapat Anda lihat <a
                                href="{{ route('perhitungan', 0) }}" target="_blank">di
                                sini</a>.
                        </div>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('.selengkapnya').addClass('d-none');
            $('#sedikit').addClass('d-none');

            $('#selengkapnya').on('click', function() {
                $('.sepuluh').addClass('d-none');
                $('.selengkapnya').removeClass('d-none');
                $(this).addClass('d-none');
                $('#sedikit').removeClass('d-none');
            });

            $('#sedikit').on('click', function() {
                $('.sepuluh').removeClass('d-none');
                $('.selengkapnya').addClass('d-none');
                $(this).addClass('d-none');
                $('#selengkapnya').removeClass('d-none');
            });
        });
    </script>
@endsection
