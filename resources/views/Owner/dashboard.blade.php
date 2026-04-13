@extends('layouts.landing')
@push('title')
    Home
@endpush
@section('content')
    <header>
        <div class="page-header min-vh-75">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 mt-8 position-relative z-index-1">
                        <h1>Rekomendasi Terbaik</h1>
                        <p class="text-lg mt-3">
                            Eksplorasi rasa kopi kelas dunia dengan harga yang bersahabat. Pilih menu favoritmu sekarang dan
                            rasakan standar baru dalam menikmati kopi berkualitas tinggi.
                        </p>
                        <div class="d-flex align-items-center mb-4">
                            <p class="mb-0">Dinikmati lebih dari <b>40 juta</b> penikmat Tomoro Coffee di Indonesia
                                sampai detik ini</p>
                        </div>
                        <div class="d-block d-md-flex">
                            <a href="https://www.tomoro-coffee.id/home" target="_blank"
                                class="btn w-100 w-md-auto text-white btn-warning">Beli Sekarang</a>
                        </div>
                    </div>
                    <svg class="position-absolute top-0" width="1231" height="1421" viewBox="0 0 1231 1421"
                        fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.12786" filter="url(#filter0_f_31_15)">
                            <ellipse cx="811.5" cy="602.5" rx="675.5" ry="682.5"
                                fill="url(#paint0_linear_31_15)" />
                        </g>
                        <defs>
                            <filter id="filter0_f_31_15" x="0.085907" y="-215.914" width="1622.83" height="1636.83"
                                filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                <feBlend mode="normal" in="SourceGraphic" in2="BackgroundImageFix" result="shape" />
                                <feGaussianBlur stdDeviation="67.957" result="effect1_foregroundBlur_31_15" />
                            </filter>
                            <linearGradient id="paint0_linear_31_15" x1="804.405" y1="-136.203" x2="160.281"
                                y2="643.776" gradientUnits="userSpaceOnUse">
                                <stop stop-color="#F26522" />
                                <stop offset="0.469471" stop-color="#FF8C52" />
                                <stop offset="1" stop-color="white" />
                            </linearGradient>
                        </defs>
                    </svg>
                    <img class="position-absolute top-0 mt-n7 me-n12 end-0 w-70 z-index-3"
                        src="{{ asset('assets/material/img/perspective.png') }}" alt="header-image">
                </div>
            </div>
        </div>
    </header>
    <div class="card card-body mx-3 mx-md-4 mt-n6 z-index-1 position-relative">
        <section class="pt-3 pb-4" id="stats">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 z-index-2 border-radius-xl mx-auto py-3">
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6 col-lg-3 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-warning"><span id="stats1"
                                            countTo="{{ count($alternatives) }}">0</span>
                                    </h1>
                                    <h5 class="mt-3">Menu Tersedia</h5>
                                </div>
                                <hr class="vertical dark">
                            </div>
                            <div class="col-md-6 col-lg-3 position-relative">
                                <div class="p-3 text-center">
                                    <h1 class="text-gradient text-warning"> <span id="stats2" countTo="3000">0</span>+
                                    </h1>
                                    <h5 class="mt-3">Gerai di Indonesia</h5>
                                </div>
                                <hr class="vertical dark">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="mt-5 pb-4" id="technologies">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <h2 class="mb-3">Rekomendasi Menu Terbaik</h2>
                        <p>
                            Pilihan terbaik yang telah teruji. Kami menyeleksi setiap rasa dengan kriteria yang ketat untuk
                            memastikan hanya rasa yang paling dicintai pelanggan yang sampai ke tangan Anda. Dijamin
                            ketagihan!
                        </p>
                    </div>
                </div>
                <div class="row d-flex align-items-stretch">
                    @if ($sorted)
                        @php
                            $i = 1;
                        @endphp
                        @foreach ($sorted as $name => $item)
                            <div class="col-md-4 col-lg-2 mb-5 d-flex"> <a
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

                                        <h5 class="p-2 text-lg mb-0">{{ $name }}</h5>

                                        <div class="mt-auto pb-3">
                                            <small class="text-muted font-weight-bold">(Nilai akhir:
                                                {{ $item }})</small>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @php
                                $i++;
                            @endphp
                        @endforeach
                    @else
                    @endif
                </div>
            </div>
        </section>
        <section class="mb-8" id="products">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-gradient-light h-100">
                            <div class="card-body">
                                <h3 class="text-dark">Kriteria</h3>
                                <small class="text-muted">Kami percaya bahwa rasa yang sempurna lahir dari presisi. Setiap
                                    menu yang kami
                                    sajikan telah melalui proses penilaian ketat berdasarkan 7 Kriteria Utama. Setiap
                                    kriteria memiliki bobot khusus yang dirancang untuk menciptakan harmoni rasa—memastikan
                                    tidak ada elemen yang terlalu dominan, namun tetap memberikan karakter yang
                                    kuat.</small>
                                <div class="mt-3">
                                    @foreach ($criteriasOrder as $criteria)
                                        <p class="mb-0 text-dark">
                                            <span
                                                class="fw-bold">{{ $loop->iteration . '. ' . $criteria->name }}</span><span
                                                class="ms-3 text-muted">Memiliki bobot pengaruh
                                                sebesar
                                                {{ $criteria->weight }}%</span>
                                        </p>
                                        <hr class="bg-dark my-2">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="container mt-n7">
        <section class="py-5 bg-dark-blue border-radius-xl position-relative overflow-hidden z-index-1">
            <img src="{{ asset('assets/material/img/campaign/wave.png') }}" alt="pattern-lines"
                class="position-absolute start-0 top-0 w-100 opacity-1">
            <div class="container position-relative z-index-2">
                <div class="row">
                    <div class="col-lg-5 col-md-8 m-auto text-start">
                        <h2 class="text-white">Sign up for our newsletter</h2>
                        <p class="text-white mb-lg-0 mb-5">
                            Daftar newsletter kami untuk update mingguan yang seru! Tenang saja, kami tidak akan memenuhi
                            inbox-mu dengan spam yang mengganggu.
                        </p>
                    </div>
                    <div class="col-lg-6 m-auto">
                        <!-- Begin Mailchimp Signup Form -->
                        <div id="mc_embed_signup">
                            <form
                                action="https://creative-tim.us3.list-manage.com/subscribe/post?u=ff98cdcf2e6a63f872c65dbfb&amp;id=3ad01d6373&amp;f_id=0092c2e1f0"
                                method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form"
                                class="validate" target="_self">
                                <div class="row">
                                    <div class="col-sm-4 col-6 ps-sm-0 ms-auto">
                                        <div id="mc_embed_signup_scroll">
                                            <div class="mc-field-group">
                                                <div class="input-group input-group-outline">
                                                    <label class="form-label">Enter your email</label>
                                                    <input class="form-control text-white" name="EMAIL" type="email"
                                                        id="mce-EMAIL" disabled />
                                                    <span id="mce-EMAIL-HELPERTEXT" class="helper_text"></span>
                                                </div>
                                            </div>
                                            <div id="mce-responses" class="clear">
                                                <div class="response" id="mce-error-response" style="display:none">
                                                </div>
                                                <div class="response" id="mce-success-response" style="display:none">
                                                </div>
                                            </div>
                                            <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                            <div style="position: absolute; left: -5000px;" aria-hidden="true">
                                                <input type="text" name="b_ff98cdcf2e6a63f872c65dbfb_3ad01d6373"
                                                    tabindex="-1" value="" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-6 ps-sm-0 me-lg-0 me-auto">
                                        <input type="submit"
                                            class="btn btn-info mb-0 ms-lg-2 ms-sm-2 mb-sm-0 mb-2 me-auto w-100 d-block"
                                            id="mc-embedded-subscribe" name="subscribe" disabled />
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--End mc_embed_signup-->
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
@endsection
