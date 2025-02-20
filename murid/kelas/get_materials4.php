<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['id_kelas'], $_POST['id_murid'])) {
    $id_kelas = $_POST['id_kelas'];
    $id_murid = $_POST['id_murid'];

    // Menghitung jumlah pertemuan dan kehadiran
    $queryPresensi = "SELECT COUNT(*) AS jumlah_pertemuan, 
    SUM(CASE WHEN status = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
    SUM(CASE WHEN status = 'Tidak Hadir' THEN 1 ELSE 0 END) AS jumlah_tidak_hadir
    FROM tb_presensi
    WHERE id_kelas_assesment = ? AND id_murid = ?";
    $stmtPresensi = $conn->prepare($queryPresensi);
    $stmtPresensi->bind_param("ii", $id_kelas, $id_murid);
    $stmtPresensi->execute();
    $resultPresensi = $stmtPresensi->get_result();
    $dataPresensi = $resultPresensi->fetch_assoc();

    $jumlah_pertemuan = $dataPresensi['jumlah_pertemuan'];
    $jumlah_hadir = $dataPresensi['jumlah_hadir'];
    $jumlah_tidak_hadir = $dataPresensi['jumlah_tidak_hadir'];

    // Evaluasi kehadiran dihitung berdasarkan jumlah hadir dan total pertemuan
    $evaluasi_kehadiran = ($jumlah_hadir / $jumlah_pertemuan) * 100; // Persentase kehadiran

    // Menghitung total evaluasi dengan memasukkan nilai tugas dan kehadiran
    // menentukan bobot tugas dan kehadiran, misalnya tugas 70% dan kehadiran 30%
    $bobot_tugas = 0.7;
    $bobot_kehadiran = 0.3;

    $queryEvaluasi1 = "SELECT * FROM tb_murid LEFT JOIN tb_evaluasi ON tb_murid.id_murid = tb_evaluasi.id_murid WHERE tb_evaluasi.id_kelas_assesment = ? AND tb_murid.id_murid = ?";
    $stmtEvaluasi1 = $conn->prepare($queryEvaluasi1);
    $stmtEvaluasi1->bind_param("ii", $id_kelas, $id_murid);
    $stmtEvaluasi1->execute();
    $resultEvaluasi1 = $stmtEvaluasi1->get_result();
    $dataEvaluasi1 = $resultEvaluasi1->fetch_assoc();

    $queryEvaluasi = "SELECT 
    evaluasi_total,
    evaluasi_tugas,
    DATE_FORMAT(tanggal, '%d-%m-%Y') as tanggal,
    (SELECT AVG(evaluasi_total) 
     FROM tb_evaluasi 
     WHERE id_kelas_assesment = ? AND id_murid = ?) as rata_rata_evaluasi,
    (SELECT SUM(evaluasi_total) 
     FROM tb_evaluasi 
     WHERE id_kelas_assesment = ? AND id_murid = ?) as total_evaluasi
FROM tb_evaluasi 
WHERE id_kelas_assesment = ? AND id_murid = ?
ORDER BY tanggal DESC";

    $stmtEvaluasi = $conn->prepare($queryEvaluasi);
    $stmtEvaluasi->bind_param("iiiiii", $id_kelas, $id_murid, $id_kelas, $id_murid, $id_kelas, $id_murid);
    $stmtEvaluasi->execute();
    $resultEvaluasi = $stmtEvaluasi->get_result();
    $evaluasiSummary = $resultEvaluasi->fetch_assoc();

    // Get total and average
    $total_nilai_evaluasi = $evaluasiSummary['total_evaluasi'] ?? 0;
    $rata_rata_evaluasi = $evaluasiSummary['rata_rata_evaluasi'] ?? 0;


    $queryGuru = "SELECT tb_kelas.id_kelas, tb_kelas.nama_kelas, tb_kelas.nama_ruangan, tb_kelas.id_guru, 
          tb_guru.nama, tb_kelas.duty_start_day, tb_kelas.duty_end_day, 
          tb_kelas.duty_start_time, tb_kelas.duty_end_time, COUNT(tb_kelas_assesment.id_murid) AS jumlah_murid
          FROM tb_kelas
          LEFT JOIN tb_guru ON tb_kelas.id_guru = tb_guru.id_guru
          LEFT JOIN tb_kelas_assesment ON tb_kelas.id_kelas = tb_kelas_assesment.id_kelas WHERE tb_kelas_assesment.id_murid = ? AND tb_kelas_assesment.id_kelas = ?
          GROUP BY tb_kelas.id_kelas";
    $stmtGuru = $conn->prepare($queryGuru);
    $stmtGuru->bind_param("ii", $id_murid, $id_kelas);
    $stmtGuru->execute();
    $resultGuru = $stmtGuru->get_result();
    $dataGuru = $resultGuru->fetch_assoc();

    $evaluasi_total = ($dataEvaluasi1['evaluasi_tugas'] * $bobot_tugas) + ($evaluasi_kehadiran * $bobot_kehadiran); ?>


    <!-- Tombol Cetak -->
    <button onclick="printTabel()" class="btn btn-primary mt-3">Cetak</button>

    <style>
        /* Hide print-only elements during normal viewing */
        .print-only {
            display: none;
        }

        @media print {

            /* Show print-only elements when printing */
            .print-only {
                display: block;
            }

            /* Hide header and footer */
            @page {
                margin: 2cm;
                size: auto;
                -webkit-print-color-adjust: exact;
            }

            /* Remove header/footer from the browser */
            @page :first {
                margin-top: 2cm;
            }

            @page :left {
                margin-left: 2cm;
            }

            @page :right {
                margin-right: 2cm;
            }

            /* Hide URL and page numbers */
            @page :footer {
                display: none;
            }

            @page :header {
                display: none;
            }

            .watermark {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0.4;
                z-index: -1;
                pointer-events: none;
            }

            .report-header {
                text-align: center;
                margin-bottom: 30px;
            }

            .logo {
                max-width: 100px;
                height: auto;
            }

            .report-title {
                font-size: 18px;
                font-weight: bold;
                margin: 15px 0;
            }
        }
    </style>

    <div id="printArea">
        <!-- Print-only content -->
        <div class="print-only">
            <!-- Watermark -->
            <div class="watermark">
                <img src="<?php echo asset('img/logo.png'); ?>" alt="Watermark">
            </div>

            <!-- Report Header -->
            <div class="report-header">
                <img src="<?php echo asset('img/logo.png'); ?>" alt="Logo" class="logo">
                <h1 class="report-title">Laporan Hasil Evaluasi Siswa<br>Global Intelligence Academy</h1>
            </div>
        </div>
        <div class="mt-4">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th class="table-light">Nama</th>
                        <td>
                            <?php echo $dataEvaluasi1['nama']; ?>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">Presensi</th>
                        <td>
                            Hadir: <?php echo $jumlah_hadir; ?> <br>
                            Tidak Hadir: <?php echo $jumlah_tidak_hadir; ?> <br>
                            Total Pertemuan : <?php echo $jumlah_pertemuan; ?> <br>
                        </td>
                    </tr>
                    <tr>
                        <td>Chart</td>
                        <td>
                            <div id="orderStatisticsChart1" class="px-3"></div>
                        </td>
                    </tr>
                    <tr>
                        <th class="table-light">Evaluasi Nilai</th>
                        <td>
                            <?php if ($resultEvaluasi->num_rows > 0) { ?>
                                <table class="table table-sm table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Nilai Tugas</th>
                                            <th>Nilai Evaluasi</th>
                                            <th>Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        // Reset pointer since we used fetch_assoc earlier
                                        mysqli_data_seek($resultEvaluasi, 0);
                                        while ($evaluasi = $resultEvaluasi->fetch_assoc()) { ?>
                                            <tr>
                                                <td><?php echo $evaluasi['evaluasi_tugas']; ?></td>
                                                <td><?php echo $evaluasi['evaluasi_total']; ?></td>
                                                <td><?php echo $evaluasi['tanggal']; ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="2">
                                                <strong>Total Nilai:
                                                    <?php echo number_format($total_nilai_evaluasi, 2); ?></strong><br>
                                                <em>Rata-rata: <?php echo number_format($rata_rata_evaluasi, 2); ?></em>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            <?php } else { ?>
                                <span>Belum Dinilai</span>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- Area Tanda Tangan -->
        <div class="d-flex justify-content-between mt-5 signature-area">
            <div class="text-center">
                <p>Guru</p>
                <br><br>
                <br>
                <p><?php echo $dataGuru['nama']; ?></p>
            </div>
            <div class="text-center">
                <p>Padang, <?php echo date('d-m-Y'); ?><br>
                    Pengelola Bimbel</p>
                <br><br>
                <p><u>Oknira Jalfi, S.Pd</u></p>
            </div>
        </div>
    </div>
    <!-- Core JS -->
    <script src="<?php echo asset('vendor/libs/jquery/jquery.js'); ?>"></script>
    <script src="<?php echo asset('vendor/libs/popper/popper.js'); ?>"></script>
    <script src="<?php echo asset('vendor/js/bootstrap.js'); ?>"></script>
    <script src="<?php echo asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.js'); ?>"></script>

    <script src="<?php echo asset('vendor/js/menu.js'); ?>"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="<?php echo asset('vendor/libs/apex-charts/apexcharts.js'); ?>"></script>

    <!-- Main JS -->
    <script src="<?php echo asset('js/main.js'); ?>"></script>

    <!-- Page JS -->
    <script src="<?php echo asset('js/dashboards-analytics.js'); ?>"></script>

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        function printTabel() {
            var printContents = document.getElementById('printArea').innerHTML;
            var originalContents = document.body.innerHTML;

            // Create a new style element for print-specific CSS
            var printStyles = `
            <style>
                body { margin: 2cm; }
                .watermark { 
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    opacity: 0.4;
                    z-index: -1;
                    pointer-events: none;
                }
                .report-header {
                    text-align: center;
                    margin-bottom: 30px;
                }
                .logo {
                    max-width: 100px;
                    height: auto;
                }
                .report-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin: 15px 0;
                }
                @media print {
                    .btn { display: none; }
                }
            </style>
        `;

        document.body.innerHTML = printStyles + printContents;

        // Remove default headers and footers
        window.onbeforeprint = function () {
            // Additional print preparations if needed
        };

        window.print();
        document.body.innerHTML = originalContents;
    }
</script>

<script>
    // Ambil data dari PHP ke dalam JavaScript
    var dataEvaluasi = [
        <?php
        mysqli_data_seek($resultEvaluasi, 0); // Reset hasil query
        while ($evaluasi = $resultEvaluasi->fetch_assoc()) {
            echo "{ x: '{$evaluasi['tanggal']}', y: {$evaluasi['evaluasi_total']} },";
        }
        ?>
    ];

    var options = {
        series: [{
            name: "Evaluasi Nilai",
            data: dataEvaluasi
        }],
        chart: {
            type: 'line',
            height: 350,
            zoom: {
                enabled: true
            }
        },
        dataLabels: {
            enabled: true
        },
        stroke: {
            curve: 'smooth'
        },
        title: {
            text: 'Grafik Evaluasi Nilai',
            align: 'left'
        },
        grid: {
            row: {
                colors: ['#f3f3f3', 'transparent'], // Pola warna latar belakang
                opacity: 0.5
            },
        },
        xaxis: {
            type: 'category',
            categories: dataEvaluasi.map(item => item.x),
            title: {
                text: 'Tanggal'
            }
        },
        yaxis: {
            title: {
                text: 'Nilai Evaluasi'
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#orderStatisticsChart1"), options);
    chart.render();
</script>
<?php
}
?>