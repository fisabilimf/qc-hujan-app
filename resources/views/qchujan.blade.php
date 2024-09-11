<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Curah Hujan Harian</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        input {
            width: 100%;
            box-sizing: border-box;
            padding: 5px;
        }
    </style>
    <!-- External Scripts for XLSX, PapaParse, and jQuery -->
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js') }}"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/PapaParse/5.3.0/papaparse.min.js') }}"></script>    
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js') }}"></script>

    favicon
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

</head>

@extends('layouts/index')

@section('body')
    @if (Session::has('message'))
        <div class="alert {{ Session::get('alert') }} fade in">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <strong>{{ Session::get('message') }}</strong>
        </div>
    @endif

    <div class="dr"><span></span></div>

    <div class="row-fluid">
        <div class="head clearfix">
            <div class="isw-zoom"></div>
            <h1>Laporan Tahunan {{$judul}}</h1>
        </div>
        <div class="row-form clearfix" style="background:#fff">
            
            <div>
                <input type="file" id="fileInput" accept=".csv, .xlsx, .xls" />
                <button onclick="populateData()">Populate Data</button>

            </div>

            <form method="post" action="{{ url('/') }}">
                @csrf

                <div>
                    <h2>DATA CURAH HUJAN HARIAN</h2>
                    
                </div>

                <div>
                    <p>Tahun : <input type="text" name="tahun" value="{{ old('tahun', '[Tahun]') }}" /></p>

                </div>

                <div>
                    <table>
                        <tr>
                            <th>NAMA STASIUN</th>
                            <td><input type="text" name="nama_stasiun" value="{{ old('nama_stasiun', '[Nama Stasiun]') }}" /></td>
                            <th>Wilayah Sungai</th>
                            <td><input type="text" name="wilayah_sungai" value="{{ old('wilayah_sungai', '[Wilayah Sungai]') }}" /></td>
                            <th>Kode Database</th>
                            <td><input type="text" name="kode_database" value="{{ old('kode_database', '[Kode Database]') }}" /></td>
                        </tr>
                        <tr>
                            <th>Kode Stasiun</th>
                            <td><input type="text" name="kode_stasiun" value="{{ old('kode_stasiun', '[Kode Stasiun]') }}" /></td>
                            <th>Kelurahan/Desa</th>
                            <td><input type="text" name="kelurahan" value="{{ old('kelurahan', '[Kelurahan/Desa]') }}" /></td>
                            <th>Tahun Pendirian</th>
                            <td><input type="text" name="tahun_pendirian" value="{{ old('tahun_pendirian', '[Tahun Pendirian]') }}" /></td>
                        </tr>
                        <tr>
                            <th>Lintang Selatan</th>
                            <td><input type="text" name="longitude" value="{{ old('longitude', '[Longitude]') }}" /></td>
                            <th>Kecamatan</th>
                            <td><input type="text" name="kecamatan" value="{{ old('kecamatan', '[Kecamatan]') }}" /></td>
                            <th>Tipe Alat</th>
                            <td><input type="text" name="tipe_alat" value="{{ old('tipe_alat', '[Tipe Alat]') }}" /></td>
                        </tr>
                        <tr>
                            <th>Bujur Timur</th>
                            <td><input type="text" name="latitude" value="{{ old('latitude', '[Latitude]') }}" /></td>
                            <th>Kab/Kota</th>
                            <td><input type="text" name="kabupaten" value="{{ old('kabupaten', '[Kabupaten]') }}" /></td>
                            <th>Pengelola</th>
                            <td><input type="text" name="pengelola" value="{{ old('pengelola', '[Pengelola]') }}" /></td>
                        </tr>
                        <tr>
                            <th>Elevasi</th>
                            <td><input type="text" name="elevation" value="{{ old('elevation', '[Elevation]') }}" /></td>
                            <td colspan="4"></td>
                        </tr>
                    </table>

                </div>
                <br>
                <div>
                    <table>
                        <tr>
                            <th rowspan="2">Tanggal</th>
                            <th colspan="12">Bulan</th>
                        </tr>
                        <tr>
                            @for ($month = 1; $month <= 12; $month++)
                                <th>{{ $month }}</th>
                            @endfor
                        </tr>
                        <!-- Rows for the days -->
                        @for ($day = 1; $day <= 31; $day++)
                        <tr>
                            <td>{{ $day }}</td>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" name="day{{ $day }}_month{{ $month }}" value="0" oninput="calculateTotals({{ $month }})"></td>
                            @endfor
                        </tr>
                        @endfor
                        <tr>
                            <th>Total</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="total_month{{ $month }}" name="total_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Periode1</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="periode1_month{{ $month }}" name="periode1_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Periode2</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="periode2_month{{ $month }}" name="periode2_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Periode3</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="periode3_month{{ $month }}" name="periode3_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Maksimum</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="maksimum_month{{ $month }}" name="maksimum_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                        <tr>
                            <th>Data Hujan</th>
                            @for ($month = 1; $month <= 12; $month++)
                            <td><input type="text" id="datahujan_month{{ $month }}" name="datahujan_month{{ $month }}" value="0" readonly></td>
                            @endfor
                        </tr>
                    </table>

                </div>
                <br>
                <div>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Curah Hujan</th>
                            <th>Sk*</th>
                            <th>[Sk*]</th>
                            <th>Dy<sup>2</sup></th>
                            <th>Dy</th>
                            <th>Sk**</th>
                            <th>[Sk**]</th>
                        </tr>
                        @php
                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        @endphp
                        @for ($i = 0; $i < 12; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $months[$i] }}</td>
                            <td><input type="text" id="curah_hujan_{{ $i }}" name="curah_hujan_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="sk_{{ $i }}" id="sk_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="sk_brackets_{{ $i }}" id="sk_brackets_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="dy2_{{ $i }}" id="dy2_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="dy_{{ $i }}" id="dy_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="sk_star_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="sk_star_brackets_{{ $i }}" value="0" readonly></td>
                        </tr>
                        @endfor
                        <tr>
                            <td colspan="2">Rerata</td>
                            <td><input type="text" name="rerata_curah_hujan" id="rerata_curah_hujan" value="0" readonly></td>
                            <td colspan="1"></td>
                            <td><input type="text" name="rerata_sk_brackets" id="rerata_sk_brackets" value="0" readonly></td>
                            <td colspan="4"></td>
                        </tr>
                        <tr>
                            <td colspan="2">Jumlah</td>
                            <td><input type="text" name="jumlah_curah_hujan" id="jumlah_curah_hujan" value="0" readonly></td>
                            <td colspan="2"></td>
                            <td><input type="text" name="jumlah_dy2" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Maks</td>
                            <td><input type="text" name="maks_curah_hujan" id="maks_curah_hujan" value="0" readonly></td>
                            <td colspan="4"></td>
                            <td><input type="text" name="maks_sk" value="0" readonly></td>
                            <td><input type="text" name="maks_sk_brackets" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Min</td>
                            <td><input type="text" name="min_curah_hujan" id="min_curah_hujan" value="0" readonly></td>
                            <td colspan="4"></td>
                            <td><input type="text" name="min_sk" value="0" readonly></td>
                            <td><input type="text" name="min_sk_brackets" value="0" readonly></td>
                        </tr>

                        <!-- Additional rows and calculations here -->
                    </table>

                </div>
                <br>
                <div>
                    <h2>Hasil Analisis</h2>
                    <table>
                        <tr>
                            <td>n</td>
                            <td><input type="text" name="n_value" id="n_value" value="12"></td>
                        </tr>
                        <tr>
                            <td>Sk**mak</td>
                            <td><input type="text" name="sk_mak" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>Sk**min</td>
                            <td><input type="text" name="sk_min" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>Q = Sk**mak</td>
                            <td><input type="text" name="q_sk_mak" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>R = Sk**mak - Sk**min</td>
                            <td><input type="text" name="r_sk_diff" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td>Q/n<sup>0.5</sup></td>
                            <td><input type="text" name="q_over_n" id="q_over_n" value="0" readonly></td>
                            <td> dengan probabilitas <input type="text" name="q_p" id="q_p" value="95">% dari tabel</td>
                            <td><input type="text" name="q_value" id="q_value" value="1.20" readonly></td>
                            <td id="q_over_n_status" style="background-color: yellow;">
                                <input name="q_over_n_status_text" id="q_over_n_status_text" type="text" style="background-color: yellow;" value="-" readonly>
                            </td>
                        </tr>
                        <tr>
                            <td>R/n<sup>0.5</sup></td>
                            <td><input type="text" name="r_over_n" id="r_over_n" value="0" readonly></td>
                            <td> dengan probabilitas <input type="text" name="r_p" id="r_p" value="95">% dari tabel</td>
                            <td><input type="text" name="r_value" id="r_value" value="1.39" readonly></td>
                            <td id="r_over_n_status" style="background-color: yellow;">
                                <input name="r_over_n_status_text" id="r_over_n_status_text" type="text" style="background-color: yellow;" value="-" readonly>
                            </td>
                        </tr>
                    </table>

                </div>
                <br>
                <div>
                    <h2>Uji Abnormalitas Data</h2>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Bulan</th>
                            <th>Curah Hujan (mm)</th>
                            <th>Log X</th>
                        </tr>
                        @php
                            $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                        @endphp
                        @for ($i = 0; $i < 12; $i++)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $months[$i] }}</td>
                            <td><input type="text" id="curah_hujan_x_{{ $i }}" name="curah_hujan_x_{{ $i }}" value="0" readonly></td>
                            <td><input type="text" name="logx_{{ $i }}" id="logx_{{ $i }}" value="0" readonly></td>
                        </tr>
                        @endfor
                        <tr>
                            <td colspan="2">Stdev</td>
                            <td><input type="text" name="stdev" id="stdev" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Mean</td>
                            <td><input type="text" name="xmean" id="xmean" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Kn</td>
                            <td><input type="text" name="kn" id="kn" value="2.13" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Nilai Ambang Atas</td>
                        </tr>
                        <tr>
                            <td colspan="2">Xh=</td>
                            <td><input type="text" name="Xh" id="Xh" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2">Nilai Ambang Bawah</td>
                        </tr>
                        <tr>
                            <td colspan="2">Xi=</td>
                            <td><input type="text" name="Xi" id="Xi" value="0" readonly></td>
                        </tr>
                        <tr>
                            <td colspan="2"><input type="text" name="status_uji" id="status_uji" value="-" readonly></td>
                        </tr>
                    </table>
                </div>


                <div>

                </div>

                <!-- Additional forms and tables here -->


                <br>
                <input type="submit" value="Submit">
            </form>


        </div>
    </div>
@endsection

@section('java')
<script>
    $('.tahun').Zebra_DatePicker({
        format: 'Y',
        view: 'years',
        months: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
        days: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu'],
        days_abbr: ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jum\'at', 'Sabtu']
    });

    function excel_data() {
        var pos = $('#s2_1').val();
        var model = $('#model').val();
        var tahun1 = $('#tahun1').val();
        var tahun2 = $('#tahun2').val();

        window.location.href = "excel_data/" + pos + "_" + model + "_" + tahun1 + "_" + tahun2;
    }
</script>
@endsection

<body>


    <!-- JavaScript remains unchanged -->
    <script>
        // JavaScript code here...
    </script>

<script>
        let excelData = {};

        document.getElementById('fileInput').addEventListener('change', handleFileSelect, false);

        function handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const data = e.target.result;
                    const workbook = XLSX.read(data, { type: 'binary' });
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    excelData = XLSX.utils.sheet_to_json(worksheet, { header: 1 });
                    console.log(excelData);
                };
                reader.readAsBinaryString(file);
            }
        }

        function populateData() {
            if (!excelData || excelData.length === 0) {
                alert("Please upload and select a file first.");
                return;
            }

            // Assuming the data you want starts at row 2 (row 1 being headers)
            document.querySelector('input[name="tahun"]').value = excelData[1][0].toString().match(/\d+/)?.[0] || '';
            document.querySelector('input[name="nama_stasiun"]').value = excelData[3][2];
            document.querySelector('input[name="kode_stasiun"]').value = excelData[4][2];
            document.querySelector('input[name="longitude"]').value = excelData[5][2];
            document.querySelector('input[name="latitude"]').value = excelData[6][2];
            document.querySelector('input[name="elevation"]').value = excelData[7][2];
            document.querySelector('input[name="wilayah_sungai"]').value = excelData[4][7];
            document.querySelector('input[name="kelurahan"]').value = excelData[5][7];
            document.querySelector('input[name="kecamatan"]').value = excelData[6][7];
            document.querySelector('input[name="kabupaten"]').value = excelData[7][7];
            document.querySelector('input[name="kode_database"]').value = excelData[4][11];
            document.querySelector('input[name="tahun_pendirian"]').value = excelData[5][11];
            document.querySelector('input[name="tipe_alat"]').value = excelData[6][11];
            document.querySelector('input[name="pengelola"]').value = excelData[7][11];

            // Loop to populate the daily data
            for (let day = 1; day <= 31; day++) {
                for (let month = 1; month <= 12; month++) {
                    const value = excelData[day + 10][month];
                    document.querySelector(`input[name="day${day}_month${month}"]`).value = value || 0;
                }
            }
            for (let month = 1; month <= 12; month++) {
                calculateTotals(month); // Calculate totals for each month
            }
            calculateCurahHujanTotal();
        }
    </script>

    <script>
        function calculateTotals(month) {
            let total = 0;
            let max = 0;
            let periode1 = 0;
            let periode2 = 0;
            let periode3 = 0;
            let nonZeroCount = 0;
    
            for (let day = 1; day <= 31; day++) {
                const input = document.querySelector(`input[name="day${day}_month${month}"]`);
                if (input && !isNaN(parseFloat(input.value))) {
                    const value = parseFloat(input.value) || 0;
                    total += value;
                    if (value > 0) {
                        nonZeroCount++;
                    }
                    if (value > max) {
                        max = value;
                    }
                    if (day <= 10) {
                        periode1 += value;
                    } else if (day <= 20) {
                        periode2 += value;
                    } else {
                        periode3 += value;
                    }
                }
            }
            document.getElementById(`total_month${month}`).value = total.toFixed(2);
            document.getElementById(`maksimum_month${month}`).value = max.toFixed(2);
            document.getElementById(`periode1_month${month}`).value = periode1.toFixed(2);
            document.getElementById(`periode2_month${month}`).value = periode2.toFixed(2);
            document.getElementById(`periode3_month${month}`).value = periode3.toFixed(2);
    
            document.getElementById(`curah_hujan_${month - 1}`).value = total.toFixed(2);
            document.getElementById(`curah_hujan_x_${month - 1}`).value = total.toFixed(2);
    
            document.getElementsByName(`datahujan_month${month}`)[0].value = nonZeroCount;
    
            calculateCurahHujanTotal();
            calculateLogXValues();
            calculateStandardDeviation();
            calculateMean();
            calculateXh();
            calculateXi();
            checkDataEligibility();
            updateStatusQ();
            updateStatusR();
        }
    
        function calculateCurahHujanTotal() {
            let jumlahCurahHujan = 0;
            let count = 0;
            let maxCurahHujan = 0;
            let minCurahHujan = Infinity;
    
            for (let i = 0; i < 12; i++) {
                const curahHujanValue = parseFloat(document.getElementById(`curah_hujan_${i}`).value) || 0;
                jumlahCurahHujan += curahHujanValue;
                if (curahHujanValue > 0) {
                    count++;
                }
                if (curahHujanValue > maxCurahHujan) {
                    maxCurahHujan = curahHujanValue;
                }
                if (curahHujanValue < minCurahHujan && curahHujanValue > 0) {
                    minCurahHujan = curahHujanValue;
                }
            }
    
            const rerataCurahHujan = count > 0 ? (jumlahCurahHujan / count).toFixed(2) : 0;
    
            if (minCurahHujan === Infinity) {
                minCurahHujan = 0;
            }
    
            document.getElementById('jumlah_curah_hujan').value = jumlahCurahHujan.toFixed(2);
            document.getElementById('rerata_curah_hujan').value = rerataCurahHujan;
            document.getElementById('maks_curah_hujan').value = maxCurahHujan.toFixed(2);
            document.getElementById('min_curah_hujan').value = minCurahHujan.toFixed(2);
    
            calculateSk(rerataCurahHujan);
            calculateAllSkStars();
        }
    
        function calculateSk(rerata) {
            let totalSkBrackets = 0;
            let count = 0;
    
            for (let i = 0; i < 12; i++) {
                const curahHujanValue = parseFloat(document.getElementById(`curah_hujan_${i}`).value) || 0;
                const skValue = (curahHujanValue - rerata).toFixed(2);
                document.getElementById(`sk_${i}`).value = skValue;
    
                const skBracketsValue = Math.abs(skValue).toFixed(2);
                document.getElementById(`sk_brackets_${i}`).value = skBracketsValue;
    
                const nValue = parseFloat(document.getElementById('n_value').value) || 12;
                const dy2Value = ((skBracketsValue ** 2) / nValue).toFixed(2);
                document.getElementById(`dy2_${i}`).value = dy2Value;
    
                totalSkBrackets += parseFloat(skBracketsValue);
                count++;
            }
    
            const rerataSkBrackets = count > 0 ? (totalSkBrackets / count).toFixed(2) : 0;
            document.getElementById('rerata_sk_brackets').value = rerataSkBrackets;
    
            calculateDy2Sum();
        }
    
        function calculateSkStar(i) {
            const skBracketsValue = parseFloat(document.getElementById(`sk_brackets_${i}`).value) || 0;
            const dyValue = parseFloat(document.getElementById(`dy_${i}`).value) || 1;
    
            const skStarValue = (dyValue !== 0) ? (skBracketsValue / dyValue).toFixed(2) : 0;
            document.getElementsByName(`sk_star_${i}`)[0].value = skStarValue;
    
            const skStarBracketsValue = Math.abs(skStarValue).toFixed(2);
            document.getElementsByName(`sk_star_brackets_${i}`)[0].value = skStarBracketsValue;
        }
    
        function calculateAllSkStars() {
            for (let i = 0; i < 12; i++) {
                calculateSkStar(i);
            }
    
            calculateMaxMinSk();
            calculateMaxMinSkBrackets();
        }
    
        function calculateDy2Sum() {
            let sumDy2 = 0;
    
            for (let i = 0; i < 12; i++) {
                const dy2Value = parseFloat(document.getElementById(`dy2_${i}`).value) || 0;
                sumDy2 += dy2Value;
            }
    
            document.getElementsByName('jumlah_dy2')[0].value = sumDy2.toFixed(2);
    
            updateDyFields();
        }
    
        function updateDyFields() {
            const jumlahDy2Value = parseFloat(document.getElementsByName('jumlah_dy2')[0].value) || 0;
            const sqrtValue = Math.sqrt(jumlahDy2Value).toFixed(2);
    
            for (let i = 0; i < 12; i++) {
                document.getElementById(`dy_${i}`).value = sqrtValue;
            }
        }
    
        function calculateMaxMinSk() {
            let maxSk = -Infinity;
            let minSk = Infinity;
    
            for (let i = 0; i < 12; i++) {
                const skStarValue = parseFloat(document.getElementsByName(`sk_star_${i}`)[0].value) || 0;
    
                if (skStarValue > maxSk) {
                    maxSk = skStarValue;
                }
    
                if (skStarValue < minSk) {
                    minSk = skStarValue;
                }
            }
    
            document.getElementsByName('maks_sk')[0].value = maxSk.toFixed(2);
            document.getElementsByName('min_sk')[0].value = minSk.toFixed(2);
    
            setSkMak();
            setSkMin();
        }
    
        function calculateMaxMinSkBrackets() {
            let maxSkBrackets = -Infinity;
            let minSkBrackets = Infinity;
    
            for (let i = 0; i < 12; i++) {
                const skStarBracketsValue = parseFloat(document.getElementsByName(`sk_star_brackets_${i}`)[0].value) || 0;
    
                if (skStarBracketsValue > maxSkBrackets) {
                    maxSkBrackets = skStarBracketsValue;
                }
    
                if (skStarBracketsValue < minSkBrackets) {
                    minSkBrackets = skStarBracketsValue;
                }
            }
    
            document.getElementsByName('maks_sk_brackets')[0].value = maxSkBrackets.toFixed(2);
            document.getElementsByName('min_sk_brackets')[0].value = minSkBrackets.toFixed(2);
        }
    
        function setSkMak() {
            const maksSkValue = document.getElementsByName('maks_sk')[0].value;
            document.getElementsByName('sk_mak')[0].value = maksSkValue;
    
            setQSkMak();
            calculateRSkDiff();
        }
    
        function setSkMin() {
            const minSkValue = document.getElementsByName('min_sk')[0].value;
            document.getElementsByName('sk_min')[0].value = minSkValue;
    
            calculateRSkDiff();
        }
    
        function setQSkMak() {
            const skMakValue = document.getElementsByName('sk_mak')[0].value;
            document.getElementsByName('q_sk_mak')[0].value = skMakValue;
    
            calculateQOverN();
        }
    
        function calculateRSkDiff() {
            const skMakValue = parseFloat(document.getElementsByName('sk_mak')[0].value) || 0;
            const skMinValue = parseFloat(document.getElementsByName('sk_min')[0].value) || 0;
            const rSkDiffValue = (skMakValue - skMinValue).toFixed(2);
    
            document.getElementsByName('r_sk_diff')[0].value = rSkDiffValue;
    
            calculateROverN();
        }
    
        function calculateQOverN() {
            const qSkMakValue = parseFloat(document.getElementsByName('q_sk_mak')[0].value) || 0;
            const nValue = parseFloat(document.getElementById('n_value').value) || 1;
    
            const qOverNValue = (qSkMakValue / Math.sqrt(nValue)).toFixed(2);
            document.getElementsByName('q_over_n')[0].value = qOverNValue;
        }
    
        function calculateROverN() {
            const rSkDiffValue = parseFloat(document.getElementsByName('r_sk_diff')[0].value) || 0;
            const nValue = parseFloat(document.getElementById('n_value').value) || 1;
    
            const rOverNValue = (rSkDiffValue / Math.sqrt(nValue)).toFixed(2);
            document.getElementsByName('r_over_n')[0].value = rOverNValue;
        }
    </script>   
    
    <script>
        function calculateLogXValues() {
            for (let i = 0; i < 12; i++) {
                const curahHujanValue = parseFloat(document.getElementById(`curah_hujan_x_${i}`).value) || 0;
                const logXValue = curahHujanValue > 0 ? Math.log10(curahHujanValue).toFixed(2) : 0; // Calculate log base 10
                document.getElementById(`logx_${i}`).value = logXValue;
            }
        }
        
    </script>

    <script>
        function calculateStandardDeviation() {
            let values = [];
            let total = 0;
            let count = 0;
        
            for (let i = 0; i < 12; i++) {
                const value = parseFloat(document.getElementById(`logx_${i}`).value) || 0;
                if (value > 0) { // Consider only non-zero values
                    values.push(value);
                    total += value;
                    count++;
                }
            }
        
            if (count === 0) {
                document.getElementById('stdev').value = 0; // No values to compute stdev
                return;
            }
        
            // Calculate the mean
            const mean = total / count;
        
            // Compute variance
            let varianceSum = 0;
            for (let i = 0; i < values.length; i++) {
                varianceSum += Math.pow(values[i] - mean, 2);
            }
            const variance = varianceSum / count;
        
            // Calculate standard deviation
            const stdev = Math.sqrt(variance).toFixed(2);
        
            // Set the standard deviation value in the input field
            document.getElementById('stdev').value = stdev;
        }

    </script>

    <script>
        function calculateMean() {
            let total = 0;
            let count = 0;
        
            for (let i = 0; i < 12; i++) {
                const value = parseFloat(document.getElementById(`logx_${i}`).value) || 0;
                if (value > 0) { // Consider only non-zero values
                    total += value;
                    count++;
                }
            }
        
            // Calculate the mean
            const mean = count > 0 ? (total / count).toFixed(2) : 0;
        
            // Set the mean value in the xmean input field
            document.getElementById('xmean').value = mean;
        }
    </script>

    <script>
        function calculateXh() {
            // Retrieve the values of xmean, kn, and stdev
            const xmean = parseFloat(document.getElementById('xmean').value) || 0;
            const kn = parseFloat(document.getElementById('kn').value) || 0;
            const stdev = parseFloat(document.getElementById('stdev').value) || 0;
        
            // Calculate Xh using the formula: Xh = 10^(xmean + (kn * stdev))
            const XhValue = Math.pow(10, xmean + (kn * stdev)).toFixed(2);
        
            // Update the Xh input field with the calculated value
            document.getElementById('Xh').value = XhValue;
        }

        function calculateXi() {
            // Retrieve the values of xmean, kn, and stdev
            const xmean = parseFloat(document.getElementById('xmean').value) || 0;
            const kn = parseFloat(document.getElementById('kn').value) || 0;
            const stdev = parseFloat(document.getElementById('stdev').value) || 0;

            // Calculate Xi using the formula: Xi = 10^(xmean - (kn * stdev))
            const XiValue = Math.pow(10, xmean - (kn * stdev)).toFixed(2);

            // Update the Xi input field with the calculated value
            document.getElementById('Xi').value = XiValue;
        }
    
    </script>

    <script>
        function checkDataEligibility() {
            // Retrieve the values of Xi and Xh
            const Xi = parseFloat(document.getElementById('Xi').value) || 0;
            const Xh = parseFloat(document.getElementById('Xh').value) || 0;
        
            // Initialize variables to keep track of the min and max curah_hujan_x values
            let minValue = Infinity;
            let maxValue = -Infinity;
        
            // Loop through all curah_hujan_x inputs to find the min and max values
            for (let i = 0; i < 12; i++) {
                const value = parseFloat(document.getElementById(`curah_hujan_x_${i}`).value) || 0;
        
                // Update min and max values
                if (value < minValue) {
                    minValue = value;
                }
                if (value > maxValue) {
                    maxValue = value;
                }
            }
        
            // Check if all values are within the range [Xi, Xh]
            const result = (minValue >= Xi && maxValue <= Xh)
                ? "Data tersebut layak digunakan"
                : "Data tersebut tidak layak digunakan";
            // Update the status in the status_uji input field
            const statusField = document.getElementById('status_uji');
            statusField.value = result;

            // Change the background color based on the result
            if (result === "Data tersebut layak digunakan") {
                statusField.style.backgroundColor = "green";
                statusField.style.color = "white"; // Ensure text is readable
            } else {
                statusField.style.backgroundColor = "red";
                statusField.style.color = "white"; // Ensure text is readable
            }
                }
        
    </script>

    <!-- JavaScript remains unchanged -->
    <script>
        function updateStatusQ() {
            var qOverN = parseFloat(document.getElementById('q_over_n').value);
            var qValue = parseFloat(document.getElementById('q_value').value);

            var statusCell = document.getElementById('q_over_n_status');
            var statusText = document.getElementById('q_over_n_status_text');

            if (qOverN < qValue) {
                statusCell.style.backgroundColor = 'green';
                statusText.style.backgroundColor = 'green';
                statusText.value = 'OK!';
            } else {
                statusCell.style.backgroundColor = 'red';
                statusText.style.backgroundColor = 'red';
                statusText.value = 'NOT OK!';
            }
        }

    </script>

    <script>
        function updateStatusR() {
            var rOverN = parseFloat(document.getElementById('r_over_n').value);
            var rValue = parseFloat(document.getElementById('r_value').value);

            var statusCellR = document.getElementById('r_over_n_status');
            var statusTextR = document.getElementById('r_over_n_status_text');

            if (rOverN < rValue) {
                statusCellR.style.backgroundColor = 'green';
                statusTextR.style.backgroundColor = 'green';
                statusTextR.value = 'OK!';
            } else {
                statusCellR.style.backgroundColor = 'red';
                statusTextR.style.backgroundColor = 'red';
                statusTextR.value = 'NOT OK!';
            }
        }

    </script>

</body>
</html>
