@extends('layouts.landing')
@push('title')
    Nilai Utility
@endpush
@section('content')
    <div class="card card-body mx-3 mx-md-4 mt-n3 z-index-1 position-relative">
        <section class="pt-3 pb-4" id="stats">
            <div class="container">
                <div class="row" style="margin-top: 120px; margin-bottom: 120px;">
                    <div class="col-sm-12 mb-3">
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" disabled>Nilai Utility</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('bobotutility', $type) }}">Bobot
                                    Utility</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('nilaiakhir', $type) }}">Nilai
                                    Akhir</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('ranking', $type) }}">Ranking</a>
                            </li>
                        </ul>
                    </div>
                    <div class="col-sm-12 grid-margin stretch-card">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between">
                                    <h4 style="fw-bold">Perhitungan Nilai Utility</h4>
                                </div>
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped" id="rankingTable" width="100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Keterangan</th>
                                                @foreach ($criterias as $criteria)
                                                    <th>
                                                        <div class="d-flex flex-column gap-1">
                                                            <div class="text-center">{{ $criteria->name }}</div>
                                                            <div class="text-center"
                                                                style="font-weight: normal; font-size: 12px">
                                                                {{ Str::title($criteria->type) }}</div>
                                                        </div>
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">C max</td>
                                                @foreach ($criterias as $criteria)
                                                    <td class="text-center">{{ $utilityMax[$criteria->id] }}</td>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                <td class="text-center">C min</td>
                                                @foreach ($criterias as $criteria)
                                                    <td class="text-center">{{ $utilityMin[$criteria->id] }}</td>
                                                @endforeach
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="container mt-n5">
        <section class="py-5 bg-dark-blue border-radius-xl position-relative overflow-hidden z-index-1">
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
