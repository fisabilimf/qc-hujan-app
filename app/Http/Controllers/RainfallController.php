<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RainfallController extends Controller
{
    public function index()
    {
        return view('qchujan'); // Return your Blade template view
    }

    public function generateExcel(Request $request)
    {
        $data = $request->all(); // Retrieve all the form data

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();
        
        // Create the first sheet for "Data"
        $dataSheet = $spreadsheet->getActiveSheet();
        $dataSheet->setTitle('Data');

        // Create the second sheet for "Uji RAPS"
        $ujiRapsSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Uji RAPS');
        $spreadsheet->addSheet($ujiRapsSheet);

        // Create the third sheet for "Uji Abnormalitas Data"
        $ujiAbnormalitasSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, 'Uji Abnormalitas Data');
        $spreadsheet->addSheet($ujiAbnormalitasSheet);

        // Add data to the "Data" sheet
        $this->populateDataSheet($dataSheet, $data);

        // Add data to the "Uji RAPS" sheet
        $this->populateUjiRAPSSheet($ujiRapsSheet, $data);

        // Add data to the "Uji Abnormalitas Data" sheet
        $this->populateUjiAbnormalitasDataSheet($ujiAbnormalitasSheet, $data);

        // Create a streamed response to download the generated Excel file
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $response = new StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="Data_Curah_Hujan_Harian.xlsx"');

        return $response;
    }

    private function populateDataSheet($sheet, $data)
    {
        // Set Title
        $sheet->setCellValue('A1', 'DATA CURAH HUJAN HARIAN');
        $sheet->mergeCells('A1:M1');
        $sheet->getStyle('A1:M1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        $sheet->setCellValue('A2', 'Tahun : ' . ($data['tahun'] ?? '[Tahun]'));
        $sheet->mergeCells('A2:M2');
        $sheet->getStyle('A2:M2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        // Station Information
        $sheet->mergeCells('A4:B4');
        $sheet->setCellValue('A4', 'Nama Stasiun');
        $sheet->mergeCells('C4:D4');
        $sheet->setCellValue('C4', $data['nama_stasiun'] ?? '[Nama Stasiun]');
    
        $sheet->mergeCells('A5:B5');
        $sheet->setCellValue('A5', 'Kode Stasiun');
        $sheet->mergeCells('C5:D5');
        $sheet->setCellValue('C5', $data['kode_stasiun'] ?? '[Kode Stasiun]');
    
        $sheet->mergeCells('F5:G5');
        $sheet->setCellValue('F5', 'Wilayah Sungai');
        $sheet->mergeCells('H5:I5');
        $sheet->setCellValue('H5', $data['wilayah_sungai'] ?? '[Wilayah Sungai]');
    
        $sheet->mergeCells('F6:G6');
        $sheet->setCellValue('F6', 'Kelurahan/Desa');
        $sheet->mergeCells('H6:I6');
        $sheet->setCellValue('H6', $data['kelurahan'] ?? '[Kelurahan/Desa]');
    
        $sheet->mergeCells('A6:B6');
        $sheet->setCellValue('A6', 'Lintang Selatan');
        $sheet->mergeCells('C6:D6');
        $sheet->setCellValue('C6', $data['longitude'] ?? '[Longitude]');
    
        $sheet->mergeCells('F7:G7');
        $sheet->setCellValue('F7', 'Kecamatan');
        $sheet->mergeCells('H7:I7');
        $sheet->setCellValue('H7', $data['kecamatan'] ?? '[Kecamatan]');
    
        $sheet->mergeCells('F8:G8');
        $sheet->setCellValue('F8', 'Kabupaten');
        $sheet->mergeCells('H8:I8');
        $sheet->setCellValue('H8', $data['kabupaten'] ?? '[Kabupaten]');
    
        $sheet->mergeCells('A7:B7');
        $sheet->setCellValue('A7', 'Bujur Timur');
        $sheet->mergeCells('C7:D7');
        $sheet->setCellValue('C7', $data['latitude'] ?? '[Latitude]');
    
        $sheet->mergeCells('A8:B8');
        $sheet->setCellValue('A8', 'Elevasi');
        $sheet->mergeCells('C8:D8');
        $sheet->setCellValue('C8', $data['elevation'] ?? '[Elevation]');
    
        $sheet->mergeCells('J5:K5');
        $sheet->setCellValue('J5', 'Kode Database');
        $sheet->mergeCells('L5:M5');
        $sheet->setCellValue('L5', $data['kode_database'] ?? '[Kode Database]');
    
        $sheet->mergeCells('J6:K6');
        $sheet->setCellValue('J6', 'Tahun Pendirian');
        $sheet->mergeCells('L6:M6');
        $sheet->setCellValue('L6', $data['tahun_pendirian'] ?? '[Tahun Pendirian]');
    
        $sheet->mergeCells('J7:K7');
        $sheet->setCellValue('J7', 'Tipe Alat');
        $sheet->mergeCells('L7:M7');
        $sheet->setCellValue('L7', $data['tipe_alat'] ?? '[Tipe Alat]');
    
        $sheet->mergeCells('J8:K8');
        $sheet->setCellValue('J8', 'Pengelola');
        $sheet->mergeCells('L8:M8');
        $sheet->setCellValue('L8', $data['pengelola'] ?? '[Pengelola]');
    
        // Rainfall Data Table Headers
        $sheet->mergeCells('A10:A11');
        $sheet->setCellValue('A10', 'Tanggal');
        $sheet->mergeCells('B10:M10');
        $sheet->setCellValue('B10', 'Bulan');
    
        for ($month = 1; $month <= 12; $month++) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($month + 1);
            $sheet->setCellValue("{$columnLetter}11", $month);
        }
    
        // Write daily rainfall data
        for ($day = 1; $day <= 31; $day++) {
            $sheet->setCellValue('A' . ($day + 11), $day);
            for ($month = 1; $month <= 12; $month++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($month + 1);
                $sheet->setCellValue("{$columnLetter}" . ($day + 11), $data["day{$day}_month{$month}"] ?? 0);
            }
        }
    
        // Write totals
        $totals = ['Total', 'Periode1', 'Periode2', 'Periode3', 'Maksimum', 'DataHujan'];
        foreach ($totals as $index => $total) {
            $row = 42 + $index;
            $sheet->setCellValue('A' . $row, ucfirst($total));
            
            for ($month = 1; $month <= 12; $month++) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($month + 1);
                $sheet->setCellValue($columnLetter . $row, $data[strtolower($total) . "_month{$month}"] ?? 0);
            }
        }
    }    

    private function populateUjiRAPSSheet($sheet, $data)
    {
        // Set starting row for the analysis
        $analysisStart = 1;

        // Write analysis table headers
        $analysisHeaders = ['No', 'Bulan', 'Curah Hujan', 'Sk*', '[Sk*]', 'Dy^2', 'Dy', 'Sk**', '[Sk**]'];
        $headerStyleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFD9EAD3']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        foreach ($analysisHeaders as $i => $header) {
            $column = chr(66 + $i); // Column B onwards (ASCII 66 is 'B')
            $sheet->setCellValue("{$column}{$analysisStart}", $header);
            $sheet->getStyle("{$column}{$analysisStart}")->applyFromArray($headerStyleArray);
        }

        // Define normal cell style
        $normalStyleArray = [
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];

        // Write analysis data
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        foreach ($months as $i => $month) {
            $row = $analysisStart + $i + 1;

            // Write row data
            $sheet->setCellValue("A{$row}", $i + 1);  // No
            $sheet->setCellValue("B{$row}", $month);  // Bulan
            $sheet->setCellValue("C{$row}", $data["curah_hujan_{$i}"] ?? 0);  // Curah Hujan
            $sheet->setCellValue("D{$row}", $data["sk_{$i}"] ?? 0);  // Sk*
            $sheet->setCellValue("E{$row}", $data["sk_brackets_{$i}"] ?? 0);  // [Sk*]
            $sheet->setCellValue("F{$row}", $data["dy2_{$i}"] ?? 0);  // Dy^2
            $sheet->setCellValue("G{$row}", $data["dy_{$i}"] ?? 0);  // Dy
            $sheet->setCellValue("H{$row}", $data["sk_star_{$i}"] ?? 0);  // Sk**
            $sheet->setCellValue("I{$row}", $data["sk_star_brackets_{$i}"] ?? 0);  // [Sk**]

            // Apply normal cell style
            $sheet->getStyle("B{$row}:J{$row}")->applyFromArray($normalStyleArray);
        }

        // Write final analysis summary
        $sheet->setCellValue("B" . ($analysisStart + 14), 'Rerata');
        $sheet->setCellValue("C" . ($analysisStart + 14), $data['rerata_curah_hujan'] ?? 0);
        $sheet->setCellValue("E" . ($analysisStart + 14), $data['rerata_sk_brackets'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 15), 'Jumlah');
        $sheet->setCellValue("C" . ($analysisStart + 15), $data['jumlah_curah_hujan'] ?? 0);
        $sheet->setCellValue("F" . ($analysisStart + 15), $data['jumlah_dy2'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 16), 'Maks');
        $sheet->setCellValue("C" . ($analysisStart + 16), $data['maks_curah_hujan'] ?? 0);
        $sheet->setCellValue("H" . ($analysisStart + 16), $data['maks_sk'] ?? 0);
        $sheet->setCellValue("I" . ($analysisStart + 16), $data['maks_sk_brackets'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 17), 'Min');
        $sheet->setCellValue("C" . ($analysisStart + 17), $data['min_curah_hujan'] ?? 0);
        $sheet->setCellValue("H" . ($analysisStart + 17), $data['min_sk'] ?? 0);
        $sheet->setCellValue("I" . ($analysisStart + 17), $data['min_sk_brackets'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 19), 'Hasil analisis :');
    
        $sheet->setCellValue("B" . ($analysisStart + 20), 'n');
        $sheet->setCellValue("C" . ($analysisStart + 20), $data['n_value'] ?? 12);
    
        $sheet->setCellValue("B" . ($analysisStart + 21), 'Sk**mak');
        $sheet->setCellValue("C" . ($analysisStart + 21), $data['sk_mak'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 22), 'Sk**min');
        $sheet->setCellValue("C" . ($analysisStart + 22), $data['sk_min'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 23), 'Q = Sk**mak');
        $sheet->setCellValue("C" . ($analysisStart + 23), $data['sk_mak'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 24), 'R = Sk**mak - Sk**min');
        $sheet->setCellValue("C" . ($analysisStart + 24), $data['r_sk_diff'] ?? 0);
    
        $sheet->setCellValue("B" . ($analysisStart + 25), 'Q/n^0.5');
        $sheet->setCellValue("C" . ($analysisStart + 25), $data['q_over_n'] ?? 0);
        $sheet->setCellValue("D" . ($analysisStart + 25), '< dengan probabilitas 95% dari tabel');
        $sheet->setCellValue("E" . ($analysisStart + 25), $data['q_value'] ?? 0);
        if (($data['q_over_n'] ?? 0) < ($data['q_value'] ?? 0)) {
            $sheet->setCellValue("F" . ($analysisStart + 25), $data['q_over_n_status_text'] ?? 'OK!');
            $sheet->getStyle("F" . ($analysisStart + 25))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FF00');
        } else {
            $sheet->setCellValue("F" . ($analysisStart + 25), $data['q_over_n_status_text'] ?? 'NOT OK!');
            $sheet->getStyle("F" . ($analysisStart + 25))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        }
    
        $sheet->setCellValue("B" . ($analysisStart + 26), 'R/n^0.5');
        $sheet->setCellValue("C" . ($analysisStart + 26), $data['r_over_n'] ?? 0);
        $sheet->setCellValue("D" . ($analysisStart + 26), '< dengan probabilitas 95% dari tabel');
        $sheet->setCellValue("E" . ($analysisStart + 26), $data['r_value'] ?? 0);
        if (($data['r_over_n'] ?? 0) < ($data['r_value'] ?? 0)) {
            $sheet->setCellValue("F" . ($analysisStart + 26), $data['r_over_n_status_text'] ?? 'OK!');
            $sheet->getStyle("F" . ($analysisStart + 26))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FF00FF00');
        } else {
            $sheet->setCellValue("F" . ($analysisStart + 26), $data['r_over_n_status_text'] ?? 'NOT OK!');
            $sheet->getStyle("F" . ($analysisStart + 26))->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
        }
    
        // Write the final analysis table for Q/n^0.5 and R/n^0.5 values
        $finalTableStart = $analysisStart + 28;
        $sheet->mergeCells("B{$finalTableStart}:D{$finalTableStart}");
        $sheet->setCellValue("B{$finalTableStart}", 'Nilai Q/n^0.5 dan R/n^0.5');

        $finalTableHeaders = ['n', 'Q/n^0.5', 'Q/n^0.5', 'Q/n^0.5', 'R/n^0.5', 'R/n^0.5', 'R/n^0.5'];
        foreach ($finalTableHeaders as $i => $header) {
            $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 2); // Convert column index to letter
            $sheet->setCellValue("{$columnLetter}{$finalTableStart}", $header); // Set cell value using column letter and row number
        }
    
        // Sample values for Q/n^0.5 and R/n^0.5
        $finalTableData = [
            [" ", "90%", "95%", "99%", "90%", "95%", "99%"],
            [10, 1.05, 1.14, 1.29, 1.21, 1.28, 1.38],
            [20, 1.10, 1.22, 1.42, 1.34, 1.43, 1.60],
            [30, 1.12, 1.24, 1.48, 1.40, 1.50, 1.70],
            [40, 1.14, 1.27, 1.52, 1.44, 1.55, 1.78],
            [100, 1.17, 1.29, 1.63, 1.62, 1.75, 2.00]
        ];
    
        foreach ($finalTableData as $rowIndex => $rowData) {
            foreach ($rowData as $colIndex => $value) {
                $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($colIndex + 1); // Convert column index to letter
                $sheet->setCellValue("{$columnLetter}" . ($finalTableStart + 1 + $rowIndex), $value); // Set cell value
            }
        }
    
        // Final source citation
        $sheet->mergeCells("C" . ($finalTableStart + 9) . ":H" . ($finalTableStart + 9));
        $sheet->setCellValue("C" . ($finalTableStart + 9), 'Sumber: Sri Harto, 1993: 168');
    }

    private function populateUjiAbnormalitasDataSheet($sheet, $data)
    {
        // Set starting row for the "Uji Abnormalitas Data" table
        $abnormalDataStart = 1;  // Assuming this is the correct starting row
    
        // Write headers for the "Uji Abnormalitas Data" table
        $sheet->setCellValue("B{$abnormalDataStart}", 'No');
        $sheet->setCellValue("C{$abnormalDataStart}", 'Bulan');
        $sheet->setCellValue("D{$abnormalDataStart}", 'Curah Hujan (mm)');
        $sheet->setCellValue("E{$abnormalDataStart}", 'Log X');
    
        // Define header styles
        $headerStyleArray = [
            'font' => ['bold' => true],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
            'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => ['argb' => 'D9EAD3']],
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]]
        ];
    
        // Apply header styles
        $sheet->getStyle("B{$abnormalDataStart}:E{$abnormalDataStart}")->applyFromArray($headerStyleArray);
    
        // List of months
        $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
    
        // Write data rows for the "Uji Abnormalitas Data" table
        foreach ($months as $i => $month) {
            $row = $abnormalDataStart + $i + 1;
            $sheet->setCellValue("B{$row}", $i + 1);  // Row number
            $sheet->setCellValue("C{$row}", $month);  // Month name
            $sheet->setCellValue("D{$row}", $data["curah_hujan_x_{$i}"] ?? 0);  // Curah Hujan (mm)
            $sheet->setCellValue("E{$row}", $data["logx_{$i}"] ?? 0);  // Log X
        }
    
        // Write additional rows for Stdev, Mean, Kn, Xh, and Xi
        $sheet->setCellValue("C" . ($abnormalDataStart + 13), 'Stdev');
        $sheet->setCellValue("D" . ($abnormalDataStart + 13), $data['stdev'] ?? 0);
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 14), 'Mean');
        $sheet->setCellValue("D" . ($abnormalDataStart + 14), $data['xmean'] ?? 0);
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 15), 'Kn');
        $sheet->setCellValue("D" . ($abnormalDataStart + 15), $data['kn'] ?? 2.13);
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 16), 'Nilai Ambang Atas');
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 17), 'Xh=');
        $sheet->setCellValue("D" . ($abnormalDataStart + 17), $data['Xh'] ?? 0);
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 18), 'Nilai Ambang Bawah');
    
        $sheet->setCellValue("C" . ($abnormalDataStart + 19), 'Xi=');
        $sheet->setCellValue("D" . ($abnormalDataStart + 19), $data['Xi'] ?? 0);
    
        // Write the status of the test
        $sheet->setCellValue("C" . ($abnormalDataStart + 20), $data['status_uji'] ?? '-');
    
        // Apply styles to the additional rows
        $sheet->getStyle("C" . ($abnormalDataStart + 13) . ":D" . ($abnormalDataStart + 20))->applyFromArray([
            'borders' => ['allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN]],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER]
        ]);
    }

    public function store_get($pos_id, $model, $tahun1, $tahun2)
    {
        $forms = [
            'pos_id' => $pos_id,
            'model' => $model,
            'tahun1' => $tahun1,
            'tahun2' => $tahun2
        ];

        $data = [];
        $data_plot = [];
        $bulan = [];
        $jum_bulan = [];
        $nilai_maksimum = [];
        $hari_hujan = [];
        $jum_periode = [];
        $awal_tahun = $forms['tahun1'];
        $akhir_tahun = $forms['tahun2'];
        $pemodelan = $forms['model'];

        for ($tahun_data = $forms['tahun1']; $tahun_data <= $forms['tahun2']; $tahun_data++) {
            $forms['tahun'] = $tahun_data;

            if ($forms['model'] == 0) {
                $tipe = 1;
                $pos = pos::find($forms['pos_id']);
                $da = data_hujans::where('pos_id', '=', $forms['pos_id'])
                    ->where('model', '=', 0)
                    ->where('del', '=', 0)
                    ->whereBetween('tanggal', [$forms['tahun'] . '-01-01', $forms['tahun'] . '-12-31'])
                    ->get();
                $judul = 'DATA CURAH HUJAN HARIAN';
                $ad = 'Elevasi';
                $warna = '#96E761';
            } elseif ($forms['model'] == 1) {
                $tipe = 1;
                $da = data_hujans::select('tanggal', DB::raw('SUM(nilai) as nilai'))
                    ->where('pos_id', '=', $forms['pos_id'])
                    ->where('model', '=', 1)
                    ->where('del', '=', 0)
                    ->whereBetween('tanggal', [$forms['tahun'] . '-01-01', $forms['tahun'] . '-12-31'])
                    ->groupBy('tanggal')
                    ->get();
                $pos = pos::find($forms['pos_id']);
                $judul = 'DATA CURAH HUJAN HARIAN';
                $ad = 'Elevasi';
                $warna = '#96E761';
            } elseif ($forms['model'] == 2) {
                $tipe = 2;
                $pos = pos_dugas::find($forms['pos_id']);
                $da = data_levels::where('pos_id', '=', $forms['pos_id'])
                    ->where('model', '=', 0)
                    ->where('del', '=', 0)
                    ->whereBetween('tanggal', [$forms['tahun'] . '-01-01', $forms['tahun'] . '-12-31'])
                    ->get();
                $judul = 'DATA TINGGI MUKA AIR HARIAN';
                $ad = 'Luas DPS';
                $warna = '#8FC1F1';
            } elseif ($forms['model'] == 3) {
                $tipe = 2;
                $da = data_levels::select('tanggal', DB::raw('SUM(nilai) as nilai'))
                    ->where('pos_id', '=', $forms['pos_id'])
                    ->where('model', '=', 1)
                    ->where('del', '=', 0)
                    ->whereBetween('tanggal', [$forms['tahun'] . '-01-01', $forms['tahun'] . '-12-31'])
                    ->groupBy('tanggal')
                    ->get();
                $pos = pos_dugas::find($forms['pos_id']);
                $judul = 'DATA TINGGI MUKA AIR HARIAN';
                $ad = 'Luas DPS';
                $warna = '#8FC1F1';
            }

            $tahun[$tahun_data] = $forms['tahun'];
            $datanya = 0.033;

            foreach ($da as $value) {
                $day = date('j', strtotime($value->tanggal));
                $month = date('n', strtotime($value->tanggal));

                $dt1 = strtotime(date('Y', strtotime($value->tanggal)) . '-01-01 00:00:00');
                $dt2 = strtotime($value->tanggal . ' 00:00:00');
                $telat = (($dt2 - $dt1) / 3600) / 24;
                $data[$tahun_data][$month][$day] = str_replace(',', '.', $value->nilai);

                if ($value->nilai != 'x' && $value->nilai != '-' && $value->nilai != '0') {
                    $dat[$tahun_data][$month][$day] = $value->nilai;
                    $data_plot[$tahun_data][$month][$day][1] = $value->nilai;
                    $data_plot[$tahun_data][$month][$day][2] = $telat * $datanya;
                }
            }

            for ($i = 1; $i <= 12; $i++) {
                $jum_periode[$tahun_data][1][$i] = 0;
                $jum_periode[$tahun_data][2][$i] = 0;
                $jum_periode[$tahun_data][3][$i] = 0;
                $bulan[$tahun_data][$i] = date('t', mktime(0, 0, 0, $i, 1, $tahun[$tahun_data]));

                if (isset($dat[$tahun_data][$i])) {
                    $jum_bulan[$tahun_data][$i] = array_sum($dat[$tahun_data][$i]);
                    $nilai_maksimum[$tahun_data][$i] = max($dat[$tahun_data][$i]);
                    $nilai_minimum[$tahun_data][$i] = min($dat[$tahun_data][$i]);
                    $rata[$tahun_data][$i] = number_format(array_sum($dat[$tahun_data][$i]) / count($dat[$tahun_data][$i]), 2);
                    $hari_hujan[$tahun_data][$i] = count($dat[$tahun_data][$i]);
                } else {
                    $jum_bulan[$tahun_data][$i] = 0;
                    $nilai_maksimum[$tahun_data][$i] = 0;
                    $hari_hujan[$tahun_data][$i] = 0;
                    $nilai_minimum[$tahun_data][$i] = 0;
                    $rata[$tahun_data][$i] = 0;
                }

                for ($t = 1; $t < 31; $t++) {
                    if (isset($data[$tahun_data][$i][$t]) && $data[$tahun_data][$i][$t] != 'x' && $data[$tahun_data][$i][$t] != '-' && $data[$tahun_data][$i][$t] != '.') {
                        if (!is_numeric($data[$tahun_data][$i][$t])) {
                            $data[$tahun_data][$i][$t] = 0;
                        }

                        if ($t <= 10) {
                            $jum_periode[$tahun_data][1][$i] += $data[$tahun_data][$i][$t];
                        } elseif ($t >= 11 && $t <= 20) {
                            $jum_periode[$tahun_data][2][$i] += $data[$tahun_data][$i][$t];
                        } elseif ($t >= 21) {
                            $jum_periode[$tahun_data][3][$i] += $data[$tahun_data][$i][$t];
                        }
                    }
                }
            }

            $jum_tahun[$tahun_data] = array_sum($jum_bulan[$tahun_data]);
            $hujan_max[$tahun_data] = max($nilai_maksimum[$tahun_data]);
            $total_hari[$tahun_data] = array_sum($hari_hujan[$tahun_data]);
        }

        return view('laporans.laporan', compact(
            'pemodelan', 'awal_tahun', 'akhir_tahun', 'data_plot', 'tipe', 'pos', 'data', 'judul', 'ad', 'tahun', 'bulan', 'jum_bulan', 'nilai_maksimum', 'nilai_minimum', 'rata', 'hari_hujan', 'jum_periode', 'jum_tahun', 'hujan_max', 'total_hari', 'warna'
        ));
    }
}
