@extends('layouts.landing')
@push('title')
    SPK Menu @if ($type == 0)
        Coffe
    @else
        Non-Coffe
    @endif
@endpush
@section('content')
    <div class="card card-body mx-3 mx-md-4" style="margin-top: 120px; margin-bottom: 120px;">
        <section class="pt-3 pb-4" id="technologies">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <h2 class="mb-3">Menu @if ($type == 0)
                                Coffe
                            @else
                                Non-Coffe
                            @endif
                        </h2>
                    </div>
                </div>
                <div class="row">
                    <ul class="nav nav-tabs pt-3" id="spmSmartTab" role="tablist">
                        <li class="nav-item" id="nilai-asli-nav" role="presentation">
                            <button class="nav-link active fw-semibold" id="nilai-asli-tab" data-bs-toggle="tab"
                                data-bs-target="#nilai-asli" type="button" role="tab" aria-controls="nilai-asli-pane"
                                aria-selected="true">
                                <i class="ri-list-check-2 me-1"></i> Nilai Asli
                            </button>
                        </li>
                        <li class="nav-item" id="nilai-skalar-nav" role="presentation">
                            <button class="nav-link fw-semibold" id="nilai-skalar-tab" data-bs-toggle="tab"
                                data-bs-target="#nilai-skalar" type="button" role="tab"
                                aria-controls="nilai-skalar-pane" aria-selected="true">
                                <i class="ri-list-check-2 me-1"></i> Nilai Skalar
                            </button>
                        </li>
                        <li class="nav-item" id="nilai-utility-nav" role="presentation">
                            <button class="nav-link fw-semibold" id="nilai-utility-tab" data-bs-toggle="tab"
                                data-bs-target="#nilai-utility" type="button" role="tab"
                                aria-controls="nilai-utility-pane" aria-selected="true">
                                <i class="ri-list-check-2 me-1"></i> Nilai Utility
                            </button>
                        </li>
                        <li class="nav-item" id="bobot-utility-nav" role="presentation">
                            <button class="nav-link fw-semibold" id="bobot-utility-tab" data-bs-toggle="tab"
                                data-bs-target="#bobot-utility" type="button" role="tab"
                                aria-controls="bobot-utility-pane" aria-selected="false">
                                <i class="ri-cup-line me-1"></i> Bobot Utility
                            </button>
                        </li>
                        <li class="nav-item" id="nilai-akhir-nav" role="presentation">
                            <button class="nav-link fw-semibold" id="nilai-akhir-tab" data-bs-toggle="tab"
                                data-bs-target="#nilai-akhir" type="button" role="tab" aria-controls="nilai-akhir-pane"
                                aria-selected="false">
                                <i class="ri-calculator-line me-1"></i> Nilai Akhir
                            </button>
                        </li>
                        <li class="nav-item" id="rangking-nav" role="presentation">
                            <button class="nav-link fw-semibold" id="rangking-tab" data-bs-toggle="tab"
                                data-bs-target="#rangking" type="button" role="tab" aria-controls="rangking-pane"
                                aria-selected="false">
                                <i class="ri-calculator-line me-1"></i> Perangkingan
                            </button>
                        </li>
                    </ul>
                    <div class="tab-content" id="spmSmartTabContent">
                        <div class="tab-pane fade show active p-4" id="nilai-asli" role="tabpanel"
                            aria-labelledby="nilai-asli-tab" tabindex="0">
                        </div>
                        <div class="tab-pane fade p-4" id="nilai-skalar" role="tabpanel" aria-labelledby="nilai-skalar-tab"
                            tabindex="0">
                        </div>
                        <div class="tab-pane fade p-4" id="nilai-utility" role="tabpanel"
                            aria-labelledby="nilai-utility-tab" tabindex="0">
                        </div>
                        <div class="tab-pane fade p-4" id="bobot-utility" role="tabpanel"
                            aria-labelledby="bobot-utility-tab" tabindex="0">
                        </div>
                        <div class="tab-pane fade p-4" id="nilai-akhir" role="tabpanel"
                            aria-labelledby="nilai-akhir-tab" tabindex="0">
                        </div>
                        <div class="tab-pane fade p-4" id="rangking" role="tabpanel" aria-labelledby="ranking-tab"
                            tabindex="0">
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('scripts')
    <script>
        var category = null;
        var cmin = [];
        var cmax = [];

        const loaderContent = `
            <div class="card border-0 shadow-sm text-center py-5"
                style="max-width: 450px; margin: 20px auto; border-radius: 12px; background: #ffffff;"
                id="loader">
                <div class="card-body d-flex flex-column align-items-center justify-content-center">

                    <div class="spinner-border text-info mb-4" role="status"
                        style="width: 3.5rem; height: 3.5rem; border-width: 0.25em;">
                        <span class="visually-hidden">Loading...</span>
                    </div>

                    <h5 class="card-title fw-bold text-dark mb-2 text-pulse">
                        Sistem Sedang Menampilkan Perhitungan
                    </h5>

                </div>
            </div>
        `;

        $(document).ready(function() {
            $('#nilai-asli-nav').on('click', function() {
                $('#nilai-asli').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        data: {},
                        success: function(response) {
                            $('#loader').remove();

                            const table_nilai_asli = `
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr id="header_nilai_asli"></tr>
                                        </thead>
                                        <tbody id="body_nilai_asli"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#nilai-asli').html(table_nilai_asli);

                            let c = 0;
                            $.each(response.headers, function(key, item) {
                                if (key !== 0) {
                                    $('#header_nilai_asli').append(
                                        `<th>C${++c} - ${item}</th>`);
                                } else {
                                    $('#header_nilai_asli').append(
                                        `<th>${item}</th>`);
                                }
                            });

                            var body_nilai_asli = "";
                            $.each(response.alterantives, function(key, item) {
                                body_nilai_asli += `<tr><td>${item.name}</td>`;
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    body_nilai_asli +=
                                        `<td>${item2.value}%</td>`;
                                });
                                body_nilai_asli += '</tr>';
                            });

                            $('#body_nilai_asli').html(body_nilai_asli);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            })

            $('#nilai-skalar-nav').on('click', function() {
                $('#nilai-skalar').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        data: {},
                        success: function(response) {
                            $('#loader').remove();

                            const table_nilai_skalar = `
                                <table class="table table-sm table-bordered table-striped m-0" style="font-size: 12px;">
                                    <thead>
                                        <tr class="bg-light">
                                            <th>Nilai Asli</th>
                                            <th class="text-center" style="width: 80px;">Nilai Skalar</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>&ge; 60%</td>
                                            <td>1</td>
                                        </tr>
                                        <tr>
                                            <td>50% - 59%</td>
                                            <td>0.8</td>
                                        </tr>
                                        <tr>
                                            <td>30% - 49%</td>
                                            <td>0.6</td>
                                        </tr>
                                        <tr>
                                            <td>10% - 29%</td>
                                            <td>0.4</td>
                                        </tr>
                                        <tr>
                                            <td>0% - 9%</td>
                                            <td>0.2</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="table-responsive mt-3">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr id="header_nilai_skalar"></tr>
                                        </thead>
                                        <tbody id="body_nilai_skalar"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#nilai-skalar').html(table_nilai_skalar);

                            let c = 0;
                            $.each(response.headers, function(key, item) {
                                if (key !== 0) {
                                    $('#header_nilai_skalar').append(
                                        `<th>C${++c} - ${item}</th>`);
                                } else {
                                    $('#header_nilai_skalar').append(
                                        `<th>${item}</th>`);
                                }
                            });

                            var body_nilai_skalar = "";
                            $.each(response.alterantives, function(key, item) {
                                body_nilai_skalar +=
                                    `<tr><td>${item.name}</td>`;
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    if (item2.value >= 60) {
                                        body_nilai_skalar +=
                                            `<td>1</td>`;
                                    } else if (item2.value >= 50 &&
                                        item2.value <= 59) {
                                        body_nilai_skalar +=
                                            `<td>0.8</td>`;
                                    } else if (item2.value >= 30 &&
                                        item2.value <= 49) {
                                        body_nilai_skalar +=
                                            `<td>0.6</td>`;
                                    } else if (item2.value >= 10 &&
                                        item2.value <= 29) {
                                        body_nilai_skalar +=
                                            `<td>0.4</td>`;
                                    } else if (item2.value <= 9) {
                                        body_nilai_skalar +=
                                            `<td>0.2</td>`;
                                    }
                                });
                                body_nilai_skalar += '</tr>';
                            });

                            $('#body_nilai_skalar').html(body_nilai_skalar);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            })

            $('#nilai-utility-nav').on('click', function() {
                $('#nilai-utility').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        data: {},
                        success: function(response) {
                            $('#loader').remove();

                            const table_nilai_utility = `
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr id="header_nilai_utility"></tr>
                                        </thead>
                                        <tbody id="body_nilai_utility"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#nilai-utility').html(table_nilai_utility);

                            let c = 0;
                            $('#header_nilai_utility').append(`<th>Keterangan</th>`);
                            $.each(response.headers, function(key, item) {
                                if (key !== 0) {
                                    $('#header_nilai_utility').append(
                                        `<th>C${++c} - ${item}</th>`);
                                }
                            });

                            var body_nilai_utility = "";

                            $.each(response.alterantives, function(key, item) {
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var nilaiSekarang = parseFloat(item2
                                        .value);
                                    if (cmin[key2] === undefined) {
                                        cmin[key2] = nilaiSekarang;
                                        cmax[key2] = nilaiSekarang;
                                    } else {
                                        if (nilaiSekarang < cmin[
                                                key2]) {
                                            cmin[key2] =
                                                nilaiSekarang;
                                        }
                                        if (nilaiSekarang > cmax[
                                                key2]) {
                                            cmax[key2] =
                                                nilaiSekarang;
                                        }
                                    }
                                });
                            });

                            body_nilai_utility += `
                                <tr>
                                    <td>C max</td>
                            `;

                            $.each(cmax, function(key3, item3) {
                                if (item3 >= 60) {
                                    body_nilai_utility +=
                                        `<td>1</td>`;
                                } else if (item3 >= 50 &&
                                    item3 <= 59) {
                                    body_nilai_utility +=
                                        `<td>0.8</td>`;
                                } else if (item3 >= 30 &&
                                    item3 <= 49) {
                                    body_nilai_utility +=
                                        `<td>0.6</td>`;
                                } else if (item3 >= 10 &&
                                    item3 <= 29) {
                                    body_nilai_utility +=
                                        `<td>0.4</td>`;
                                } else if (item3 <= 9) {
                                    body_nilai_utility +=
                                        `<td>0.2</td>`;
                                }
                            });

                            body_nilai_utility += `
                                </tr>
                            `;

                            body_nilai_utility += `
                                <tr>
                                    <td>C min</td>
                            `;

                            $.each(cmin, function(key4, item4) {
                                if (item4 >= 60) {
                                    body_nilai_utility +=
                                        `<td>1</td>`;
                                } else if (item4 >= 50 &&
                                    item4 <= 59) {
                                    body_nilai_utility +=
                                        `<td>0.8</td>`;
                                } else if (item4 >= 30 &&
                                    item4 <= 49) {
                                    body_nilai_utility +=
                                        `<td>0.6</td>`;
                                } else if (item4 >= 10 &&
                                    item4 <= 29) {
                                    body_nilai_utility +=
                                        `<td>0.4</td>`;
                                } else if (item4 <= 9) {
                                    body_nilai_utility +=
                                        `<td>0.2</td>`;
                                }
                            });

                            body_nilai_utility += `
                                </tr>
                            `;

                            $('#body_nilai_utility').html(body_nilai_utility);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            })

            $('#bobot-utility-nav').on('click', function() {
                $('#bobot-utility').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $('#loader').remove();

                            const table_bobot_utility = `
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr id="header_bobot_utility"></tr>
                                        </thead>
                                        <tbody id="body_bobot_utility"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#bobot-utility').html(table_bobot_utility);

                            let c = 0;
                            $.each(response.headers, function(key, item) {
                                if (key !== 0) {
                                    $('#header_bobot_utility').append(
                                        `<th>C${++c} - ${item}</th>`);
                                } else {
                                    $('#header_bobot_utility').append(
                                        `<th>${item}</th>`);
                                }
                            });

                            cmin = [];
                            cmax = [];

                            $.each(response.alterantives, function(key, item) {
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiSkala = 0;

                                    if (rawValue >= 60) {
                                        nilaiSkala = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiSkala = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiSkala = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiSkala = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiSkala = 0.2;
                                    }

                                    if (cmin[key2] === undefined) {
                                        cmin[key2] = nilaiSkala;
                                        cmax[key2] = nilaiSkala;
                                    } else {
                                        if (nilaiSkala < cmin[key2])
                                            cmin[key2] = nilaiSkala;
                                        if (nilaiSkala > cmax[key2])
                                            cmax[key2] = nilaiSkala;
                                    }
                                });
                            });

                            var body_bobot_utility = "";

                            $.each(response.alterantives, function(key, item) {
                                body_bobot_utility +=
                                    `<tr><td>${item.name}</td>`;

                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiini = 0;

                                    if (rawValue >= 60) {
                                        nilaiini = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiini = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiini = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiini = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiini = 0.2;
                                    }

                                    var nilaiUtility = 0;
                                    var pembagi = cmax[key2] - cmin[
                                        key2];

                                    if (pembagi === 0) {
                                        nilaiUtility =
                                            1;
                                    } else {
                                        nilaiUtility = (nilaiini - cmin[
                                            key2]) / pembagi;
                                    }
                                    var nilaiUtilityFormatted =
                                        nilaiUtility.toFixed(4);

                                    body_bobot_utility +=
                                        `<td>
                                            <div class="d-flex flex-column gap-2">
                                                <div><small>(${nilaiini} - ${cmin[key2]}) / (${cmax[key2]} - ${cmin[key2]})</small></div>
                                                <div><strong>${nilaiUtilityFormatted}</strong></div>
                                            </div>
                                        </td>`;
                                });

                                body_bobot_utility += '</tr>';
                            });

                            $('#body_bobot_utility').html(body_bobot_utility);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            });

            $('#nilai-akhir-nav').on('click', function() {
                $('#nilai-akhir').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $('#loader').remove();

                            const table_nilai_akhir = `
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr id="header_nilai_akhir"></tr>
                                        </thead>
                                        <tbody id="body_nilai_akhir"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#nilai-akhir').html(table_nilai_akhir);

                            let c = 0;
                            $.each(response.headers, function(key, item) {
                                if (key !== 0) {
                                    $('#header_nilai_akhir').append(
                                        `<th>C${++c} - ${item}</th>`);
                                } else {
                                    $('#header_nilai_akhir').append(
                                        `<th>${item}</th>`);
                                }
                            });
                            $('#header_nilai_akhir').append(
                                `<th>Nilai Akhir</th>`);
                            cmin = [];
                            cmax = [];
                            $.each(response.alterantives, function(key, item) {
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiSkala = 0;
                                    if (rawValue >= 60) {
                                        nilaiSkala = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiSkala = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiSkala = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiSkala = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiSkala = 0.2;
                                    }
                                    if (cmin[key2] === undefined) {
                                        cmin[key2] = nilaiSkala;
                                        cmax[key2] = nilaiSkala;
                                    } else {
                                        if (nilaiSkala < cmin[key2])
                                            cmin[key2] = nilaiSkala;
                                        if (nilaiSkala > cmax[key2])
                                            cmax[key2] = nilaiSkala;
                                    }
                                });
                            });
                            var body_nilai_akhir = "";
                            var ArrayNilai = [];
                            var TotalNilai = 0;
                            $.each(response.alterantives, function(key, item) {
                                body_nilai_akhir +=
                                    `<tr><td>${item.name}</td>`;
                                ArrayNilai = [];
                                TotalNilai = 0;
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiini = 0;

                                    if (rawValue >= 60) {
                                        nilaiini = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiini = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiini = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiini = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiini = 0.2;
                                    }

                                    var nilaiUtility = 0;
                                    var pembagi = cmax[key2] - cmin[
                                        key2];

                                    if (pembagi === 0) {
                                        nilaiUtility =
                                            1;
                                    } else {
                                        nilaiUtility = (nilaiini - cmin[
                                            key2]) / pembagi;
                                    }

                                    var nilaiUtilityFormatted =
                                        nilaiUtility.toFixed(4);

                                    body_nilai_akhir +=
                                        `<td>
                                            <div class="d-flex flex-column gap-2">
                                                <div><small>(${nilaiUtilityFormatted} × ${item2.normalisasi})</small></div>
                                                <div><strong>${nilaiUtilityFormatted * item2.normalisasi}</strong></div>
                                            </div>
                                        </td>`;
                                    ArrayNilai[key2] =
                                        nilaiUtilityFormatted;
                                    TotalNilai += nilaiUtility;
                                });
                                var stringNilai = Object.values(ArrayNilai)
                                    .join(' + ');

                                body_nilai_akhir +=
                                    `<td>
                                        <div class="d-flex flex-column gap-2">
                                            <div><small>(${stringNilai})</small></div>
                                            <div><strong>${TotalNilai.toFixed(4)}</strong></div>
                                        </div>
                                    </td>`;
                                body_nilai_akhir += '</tr>';
                            });

                            $('#body_nilai_akhir').html(body_nilai_akhir);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            });

            $('#rangking-nav').on('click', function() {
                $('#rangking').html(loaderContent);
                let url_nilaiasli = "{{ route('nilai_asli', ':type') }}";
                url_nilaiasli = url_nilaiasli.replace(':type', category);

                setTimeout(function() {
                    $.ajax({
                        url: url_nilaiasli,
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            $('#loader').remove();

                            const table_rangking = `
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered align-middle">
                                        <thead>
                                            <tr id="header_ranking">
                                                <th>Rank</th>
                                                <th>Nama Alternatif</th>
                                                <th>Nilai Akhir (Proses)</th>
                                            </tr>
                                        </thead>
                                        <tbody id="body_ranking"></tbody>
                                    </table>
                                </div>
                            `;

                            $('#rangking').html(table_rangking);

                            cmin = [];
                            cmax = [];
                            $.each(response.alterantives, function(key, item) {
                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiSkala = 0;

                                    if (rawValue >= 60) {
                                        nilaiSkala = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiSkala = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiSkala = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiSkala = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiSkala = 0.2;
                                    }

                                    if (cmin[key2] === undefined) {
                                        cmin[key2] = nilaiSkala;
                                        cmax[key2] = nilaiSkala;
                                    } else {
                                        if (nilaiSkala < cmin[key2])
                                            cmin[key2] = nilaiSkala;
                                        if (nilaiSkala > cmax[key2])
                                            cmax[key2] = nilaiSkala;
                                    }
                                });
                            });

                            var listAlternatifSelesai = [];

                            $.each(response.alterantives, function(key, item) {
                                var ArrayNilai = [];
                                var TotalNilai = 0;

                                $.each(item.alterantive_criterias, function(
                                    key2, item2) {
                                    var rawValue = parseFloat(item2
                                        .value);
                                    var nilaiini = 0;

                                    if (rawValue >= 60) {
                                        nilaiini = 1;
                                    } else if (rawValue >= 50 &&
                                        rawValue <= 59) {
                                        nilaiini = 0.8;
                                    } else if (rawValue >= 30 &&
                                        rawValue <= 49) {
                                        nilaiini = 0.6;
                                    } else if (rawValue >= 10 &&
                                        rawValue <= 29) {
                                        nilaiini = 0.4;
                                    } else if (rawValue <= 9) {
                                        nilaiini = 0.2;
                                    }

                                    var nilaiUtility = 0;
                                    var pembagi = cmax[key2] - cmin[
                                        key2];

                                    if (pembagi === 0) {
                                        nilaiUtility = 1;
                                    } else {
                                        nilaiUtility = (nilaiini - cmin[
                                            key2]) / pembagi;
                                    }

                                    var nilaiUtilityFormatted =
                                        nilaiUtility.toFixed(4);
                                    ArrayNilai[key2] =
                                        nilaiUtilityFormatted;
                                    TotalNilai += nilaiUtility;
                                });

                                var stringNilai = Object.values(ArrayNilai)
                                    .join(' + ');

                                listAlternatifSelesai.push({
                                    name: item.name,
                                    total: TotalNilai,
                                    prosesString: stringNilai
                                });
                            });

                            listAlternatifSelesai.sort(function(a, b) {
                                var selisihTotal = b.total - a.total;
                                if (selisihTotal !== 0) {
                                    return selisihTotal;
                                }

                                return a.name.localeCompare(b.name);
                            });

                            var body_ranking = "";

                            $.each(listAlternatifSelesai, function(index, alternatif) {
                                var rankSekarang = index +
                                    1;

                                body_ranking += `
                                    <tr>
                                        <td class="text-center font-weight-bold" style="width: 80px;">
                                            ${rankSekarang}
                                        </td>
                                        <td><strong>${alternatif.name}</strong></td>
                                        <td>
                                            <div class="d-flex flex-column gap-1">
                                                <div class="text-muted"><small>(${alternatif.prosesString})</small></div>
                                                <div class="text-primary" style="font-size: 15px;"><strong>${alternatif.total.toFixed(4)}</strong></div>
                                            </div>
                                        </td>
                                    </tr>
                                `;
                            });

                            $('#body_ranking').html(body_ranking);
                        },
                        error: function(xhr, status, error) {
                            console.error('Terjadi kesalahan:', error);
                        }
                    });
                }, 1500);
            });


            category = @json($type);
            if (category == 0) {
                $('#title_menu').text('Coffe');
            } else {
                $('#title_menu').text('Non-Coffe');
            }
            $('#nilai-asli-nav').trigger('click');
        });
    </script>
@endsection
