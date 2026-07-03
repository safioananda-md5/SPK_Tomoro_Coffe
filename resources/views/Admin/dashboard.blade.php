@extends('layouts.main')
@push('title')
    Dashboard
@endpush
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <h5 class="text-muted">Good Morning, <span class="text-black fw-bold">{{ Auth::user()->name }}</span></h5>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="home-tab">
                <div class="tab-content tab-content-basic">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="statistics-details d-flex align-items-center justify-content-start gap-5">
                                    <div>
                                        <p class="statistics-title">Jumlah Menu Total</p>
                                        <h3 class="rate-percentage">{{ $CountAlternative ?? 0 }}</h3>
                                    </div>
                                    <div>
                                        <p class="statistics-title">Jumlah Kriteria Total</p>
                                        <h3 class="rate-percentage">{{ $CountCriteria ?? 0 }}</h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 d-flex flex-column">
                                <div class="row flex-grow">
                                    <div class="col-12 grid-margin stretch-card">
                                        <div class="card card-rounded">
                                            <div class="card-body">
                                                <div class="d-sm-flex justify-content-between align-items-start">
                                                    <div>
                                                        <h4 class="card-title card-title-dash">Ranking Menu
                                                        </h4>
                                                        <p class="card-subtitle card-subtitle-dash">Daftar menu berdasarkan
                                                            ranking.</p>
                                                    </div>
                                                </div>
                                                <div class="table-responsive  mt-1">
                                                    <table class="table select-table">
                                                        <thead>
                                                            <tr>
                                                                <th class="text-center">
                                                                    <h6>Rank</h6>
                                                                </th>
                                                                <th>
                                                                    <h6>Nama Menu</h6>
                                                                </th>
                                                                <th>
                                                                    <h6>Kategori</h6>
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="body-overview">
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        var category = 'all';
        $(document).ready(function() {
            let url_nilaiasli = "{{ route('admin.perangkingan.nilai_asli', ':type') }}";
            url_nilaiasli = url_nilaiasli.replace(':type', category);

            $.ajax({
                url: url_nilaiasli,
                type: 'GET',
                dataType: 'json',
                data: {},
                success: function(response) {
                    var cmin = {};
                    var cmax = {};

                    $.each(response.alterantives, function(key, item) {
                        $.each(response.headers, function(indexHeader,
                            itemHeader) {
                            if (indexHeader === 0)
                                return;

                            let kriteriaData = item
                                .alterantive_criterias ? item
                                .alterantive_criterias[
                                    indexHeader - 1] : null;
                            let nilaiSkala =
                                0.2;

                            if (kriteriaData && kriteriaData
                                .value !== undefined &&
                                kriteriaData.value !== null) {
                                var rawValue = parseFloat(
                                    kriteriaData.value);
                                if (rawValue >= 60) nilaiSkala =
                                    1;
                                else if (rawValue >= 50 &&
                                    rawValue <= 59) nilaiSkala =
                                    0.8;
                                else if (rawValue >= 30 &&
                                    rawValue <= 49) nilaiSkala =
                                    0.6;
                                else if (rawValue >= 10 &&
                                    rawValue <= 29) nilaiSkala =
                                    0.4;
                            }

                            let currentKey = 'kriteria_' +
                                indexHeader;

                            if (cmin[currentKey] ===
                                undefined) {
                                cmin[currentKey] = nilaiSkala;
                                cmax[currentKey] = nilaiSkala;
                            } else {
                                if (nilaiSkala < cmin[
                                        currentKey]) cmin[
                                    currentKey] = nilaiSkala;
                                if (nilaiSkala > cmax[
                                        currentKey]) cmax[
                                    currentKey] = nilaiSkala;
                            }
                        });
                    });

                    var listAlternatifSelesai = [];

                    $.each(response.alterantives, function(key, item) {
                        var ArrayStringRumus = [];
                        var TotalNilaiAkhir = 0;

                        $.each(response.headers, function(indexHeader,
                            itemHeader) {
                            if (indexHeader === 0)
                                return;

                            let kriteriaData = item
                                .alterantive_criterias ? item
                                .alterantive_criterias[
                                    indexHeader - 1] : null;
                            let nilaiini =
                                0.2;
                            let normalisasiBobot = 0;

                            if (kriteriaData && kriteriaData
                                .value !== undefined &&
                                kriteriaData.value !== null) {
                                var rawValue = parseFloat(
                                    kriteriaData.value);
                                if (rawValue >= 60) nilaiini =
                                    1;
                                else if (rawValue >= 50 &&
                                    rawValue <= 59) nilaiini =
                                    0.8;
                                else if (rawValue >= 30 &&
                                    rawValue <= 49) nilaiini =
                                    0.6;
                                else if (rawValue >= 10 &&
                                    rawValue <= 29) nilaiini =
                                    0.4;

                                normalisasiBobot = kriteriaData
                                    .normalisasi ? parseFloat(
                                        kriteriaData.normalisasi
                                    ) : 0;
                            } else {
                                if (response.alterantives[0] &&
                                    response.alterantives[0]
                                    .alterantive_criterias[
                                        indexHeader - 1]) {
                                    normalisasiBobot =
                                        parseFloat(response
                                            .alterantives[0]
                                            .alterantive_criterias[
                                                indexHeader - 1]
                                            .normalisasi || 0);
                                }
                            }

                            let currentKey = 'kriteria_' +
                                indexHeader;
                            let maxVal = cmax[currentKey] !==
                                undefined ? cmax[currentKey] :
                                0.2;
                            let minVal = cmin[currentKey] !==
                                undefined ? cmin[currentKey] :
                                0.2;

                            var nilaiUtility = 0;
                            var pembagi = maxVal - minVal;

                            if (pembagi === 0) {
                                nilaiUtility = 1;
                            } else {
                                nilaiUtility = (nilaiini -
                                    minVal) / pembagi;
                            }

                            var hasilPerkalianBobot =
                                nilaiUtility * normalisasiBobot;

                            ArrayStringRumus.push(
                                hasilPerkalianBobot.toFixed(
                                    4));
                            TotalNilaiAkhir +=
                                hasilPerkalianBobot;
                        });

                        var stringNilai = ArrayStringRumus.join(' + ');

                        listAlternatifSelesai.push({
                            name: item.name,
                            category: item.category,
                            total: TotalNilaiAkhir,
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
                        var rankSekarang = index + 1;

                        body_ranking += `
                            <tr>
                                <tr>
                                    <td class="py-3 px-4 text-dark fw-bold text-center">${rankSekarang}</td>
                                    <td class="py-3 px-4 text-dark">${alternatif.name}</td>
                                    <td class="py-3 px-4 mono-font fw-bold text-indigo-custom bg-indigo-light">${alternatif.category == 0 ? 'Coffe' : 'Non-Coffe'}</td>
                                </tr>
                            </tr>
                        `;
                    });

                    $('#body-overview').html(body_ranking);
                },
                error: function(xhr, status, error) {
                    console.error('Terjadi kesalahan:', error);
                }
            });
        });
    </script>
@endsection
