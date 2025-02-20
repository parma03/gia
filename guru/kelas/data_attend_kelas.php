<?php
session_start();
include '../../config/koneksi.php';

// Pengecekan session untuk redirect jika sudah login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Murid') {
        redirect('murid/index.php');
        exit();
    } else if ($_SESSION['role'] === 'Admin') {
        redirect('admin/index.php');
        exit();
    } else if ($_SESSION['role'] === 'Pimpinan') {
        redirect('pimpinan/index.php');
        exit();
    }
} else {
    redirect('index.php');
    exit();
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];
$profile = $_SESSION['profile'];

$id_kelas = $_GET['id_kelas'];
$querydetailkelas = "SELECT * FROM tb_kelas INNER JOIN tb_guru ON tb_kelas.id_guru=tb_guru.id_guru WHERE tb_kelas.id_kelas = ?";
$stmt = $conn->prepare($querydetailkelas);
$stmt->bind_param("i", $id_kelas);
$stmt->execute();
$result = $stmt->get_result();
$detail = $result->fetch_assoc();

$query = "SELECT tb_kelas.id_kelas, tb_kelas.nama_kelas, tb_murid.id_murid, tb_murid.nama, tb_murid.profile 
          FROM tb_kelas_assesment 
          INNER JOIN tb_murid ON tb_kelas_assesment.id_murid = tb_murid.id_murid
          INNER JOIN tb_kelas ON tb_kelas_assesment.id_kelas = tb_kelas.id_kelas
          WHERE tb_kelas.id_kelas = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_kelas);
$stmt->execute();
$result = $stmt->get_result();

$queryMuridAvailable = "SELECT * FROM tb_murid WHERE id_murid NOT IN (SELECT id_murid FROM tb_kelas_assesment WHERE id_kelas = ?)";
$stmt = $conn->prepare($queryMuridAvailable);
$stmt->bind_param("i", $id_kelas);
$stmt->execute();
$resultMuridAvailable = $stmt->get_result();

$querySoal = "SELECT * FROM tb_soal WHERE id_kelas_assesment = ?";
$stmtSoal = $conn->prepare($querySoal);
$stmtSoal->bind_param("i", $id_kelas);
$stmtSoal->execute();
$resultSoal = $stmtSoal->get_result();

if (isset($_POST['tambah_soal'])) {
    $id_kelas = $_POST['id_kelas'];
    $nama_soal = $_POST['nama_soal'];
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $file_ext;
    $file_path = BASE_PATH . 'assets/file_soal/' . $random_name;

    if (move_uploaded_file($file_temp, $file_path)) {
        $queryInsertSoal = "INSERT INTO tb_soal (nama_soal, file_soal, id_kelas_assesment) VALUES (?, ?, ?)";
        $stmtInsertSoal = $conn->prepare($queryInsertSoal);
        $stmtInsertSoal->bind_param("ssi", $nama_soal, $random_name, $id_kelas);
        if ($stmtInsertSoal->execute()) {
            $_SESSION['notification'] = "Data Admin berhasil ditambah.";
            $_SESSION['alert'] = "alert-success";
            header("Location: data_attend_kelas.php?id_kelas=$id_kelas");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

$queryMateri = "SELECT * FROM tb_materi WHERE id_kelas_assesment = ?";
$stmtMateri = $conn->prepare($queryMateri);
$stmtMateri->bind_param("i", $id_kelas);
$stmtMateri->execute();
$resultMateri = $stmtMateri->get_result();

if (isset($_POST['tambah_materi'])) {
    $id_kelas = $_POST['id_kelas'];
    $nama_materi = $_POST['nama_materi'];
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $file_ext;
    $file_path = BASE_PATH . 'assets/file_materi/' . $random_name;

    if (move_uploaded_file($file_temp, $file_path)) {
        $queryInsertMateri = "INSERT INTO tb_materi (nama_materi, file_materi, id_kelas_assesment) VALUES (?, ?, ?)";
        $stmtInsertMateri = $conn->prepare($queryInsertMateri);
        $stmtInsertMateri->bind_param("ssi", $nama_materi, $random_name, $id_kelas);
        if ($stmtInsertMateri->execute()) {
            $_SESSION['notification'] = "Data Admin berhasil ditambah.";
            $_SESSION['alert'] = "alert-success";
            header("Location: data_attend_kelas.php?id_kelas=$id_kelas");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?php echo asset(''); ?>" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Data Kelas <?php echo $detail['nama_kelas']; ?> - <?php echo $role; ?> | Global Intelligence Academy</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo asset('img/logo.png'); ?>" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="<?php echo asset('vendor/fonts/boxicons.css'); ?>" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo asset('vendor/css/core.css'); ?>" class="template-customizer-core-css" />
    <link rel="stylesheet" href="<?php echo asset('vendor/css/theme-default.css'); ?>"
        class="template-customizer-theme-css" />
    <link rel="stylesheet" href="<?php echo asset('css/demo.css'); ?>" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo asset('vendor/libs/perfect-scrollbar/perfect-scrollbar.css'); ?>" />

    <link rel="stylesheet" href="<?php echo asset('vendor/libs/apex-charts/apex-charts.css'); ?>" />

    <!-- Page CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.bootstrap5.min.css" />

    <!-- Helpers -->
    <script src="<?php echo asset('vendor/js/helpers.js'); ?>"></script>
    <script src="<?php echo asset('js/config.js'); ?>"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <?php include '../_partial/menu.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include '../_partial/navbar.php'; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="card">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-header">Data Kelas <?php echo $detail['nama_kelas'] ?></h5>
                                <a href="data_kelas.php" type="button" class="btn btn-icon btn-outline-primary me-3">
                                    <span class="tf-icons bx bx-home bx-22px"></span>
                                </a>
                            </div>
                            <div class="justify-content-between align-items-center">
                                <center>
                                    <button type="button" class="btn btn-outline-primary me-3" data-bs-toggle="modal"
                                        data-bs-target="#addSoalModal">
                                        <span class="tf-icons bx bx-file bx-22px"></span>Tambah Soal
                                    </button>
                                    <button type="button" class="btn btn-outline-primary me-3" data-bs-toggle="modal"
                                        data-bs-target="#addMateriModal">
                                        <span class="tf-icons bx bxs-file-doc bx-22px"></span>&nbsp;Tambah Materi
                                    </button>
                                </center>
                            </div>
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myTableMurid" class="datatables-basic table border-top">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th>Nama Siswa</th>
                                            <th>Jumlah Kehadiran</th>
                                            <th>Status Hari Ini</th>
                                            <th>Nilai Evaluasi</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($result)) {
                                            $id_murid = $data['id_murid'];
                                            // Hitung jumlah kehadiran
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

                                            // Cek status presensi hari ini
                                            $tanggalHariIni = date("Y-m-d");
                                            $queryHariIni = "SELECT * FROM tb_presensi WHERE id_kelas_assesment = ? AND id_murid = ? AND tanggal = ?";
                                            $stmtHariIni = $conn->prepare($queryHariIni);
                                            $stmtHariIni->bind_param("iis", $id_kelas, $id_murid, $tanggalHariIni);
                                            $stmtHariIni->execute();
                                            $resultHariIni = $stmtHariIni->get_result();
                                            $presensiHariIni = $resultHariIni->num_rows > 0;

                                            // Ambil evaluasi
                                            $queryEvaluasi = "SELECT 
                                                                evaluasi_total, 
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

                                            ?>
                                            <tr>
                                                <td><?php echo $no++; ?></td>
                                                <td><?php echo htmlspecialchars($data['nama'], ENT_QUOTES, 'UTF-8'); ?></td>
                                                <td><?php echo $jumlah_hadir ? $jumlah_hadir : '0'; ?>/<?php echo $jumlah_hadir ? $jumlah_hadir : '0'; ?>
                                                </td>
                                                <td><?php echo $presensiHariIni ? 'Sudah Diambil' : 'Belum Mengambil Absen'; ?>
                                                </td>
                                                <td>
                                                    <?php if ($resultEvaluasi->num_rows > 0) { ?>
                                                        <table class="table table-sm table-bordered mb-0">
                                                            <thead>
                                                                <tr>
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
                                                                        <em>Rata-rata:
                                                                            <?php echo number_format($rata_rata_evaluasi, 2); ?></em>
                                                                    </td>
                                                                </tr>
                                                            </tfoot>
                                                        </table>
                                                    <?php } else { ?>
                                                        <span>Belum Dinilai</span>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group">
                                                        <button type="button"
                                                            class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end" style="">
                                                            <li>
                                                                <button class="dropdown-item btn btn-success btn-sm" <?php echo $presensiHariIni ? 'disabled' : ''; ?>
                                                                    onclick="updatePresensi(<?php echo $id_murid; ?>, 'Hadir')">Hadir</button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item btn btn-danger btn-sm" <?php echo $presensiHariIni ? 'disabled' : ''; ?>
                                                                    onclick="updatePresensi(<?php echo $id_murid; ?>, 'Tidak Hadir')">Tidak
                                                                    Hadir</button>
                                                            </li>
                                                            <li>
                                                                <button class="dropdown-item btn btn-warning btn-sm"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editModal<?php echo $id_murid; ?>">
                                                                    Evaluasi Nilai</button>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="editModal<?php echo $id_murid; ?>" tabindex="-1"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <?php
                                                        $queryJawaban = "SELECT * FROM tb_jawaban
                                                            LEFT JOIN tb_soal ON tb_jawaban.id_soal = tb_soal.id_soal
                                                            WHERE tb_soal.id_kelas_assesment = ? AND tb_jawaban.id_murid = ?
                                                            AND tb_jawaban.id_jawaban NOT IN (
                                                                SELECT id_jawaban FROM tb_evaluasi WHERE id_murid = ? AND id_kelas_assesment = ?
                                                            )";
                                                        $stmtJawaban = $conn->prepare($queryJawaban);
                                                        $stmtJawaban->bind_param("iiii", $id_kelas, $id_murid, $id_murid, $id_kelas);
                                                        $stmtJawaban->execute();
                                                        $resultJawaban = $stmtJawaban->get_result();
                                                        ?>
                                                        <form action="update_evaluasi.php" method="POST">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Tambah Nilai Evaluasi</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <input type="hidden" name="id_murid"
                                                                    value="<?php echo $id_murid; ?>">
                                                                <input type="hidden" name="id_kelas"
                                                                    value="<?php echo $id_kelas; ?>">

                                                                <div class="form-floating form-floating-custom mb-3">
                                                                    <select class="form-select" id="selectFloatingLabel"
                                                                        name="id_jawaban">
                                                                        <option value="" disabled>Pilih
                                                                            Jawaban</option>
                                                                        <?php
                                                                        while ($jawaban = $resultJawaban->fetch_assoc()) {
                                                                            echo '<option value="' . $jawaban['id_jawaban'] . '">' . htmlspecialchars($jawaban['nama_file'], ENT_QUOTES, 'UTF-8') . '</option>';
                                                                        }
                                                                        ?>
                                                                    </select>
                                                                    <label for="selectFloatingLabel">Pilih
                                                                        Jawaban</label>
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="evaluasi_tugas" class="form-label">Evaluasi
                                                                        Tugas</label>
                                                                    <input type="number" class="form-control"
                                                                        name="evaluasi_tugas" id="evaluasi_tugas"
                                                                        value="<?php echo $evaluasi['evaluasi_tugas'] ?? ''; ?>">
                                                                </div>
                                                                <div class="mb-3">
                                                                    <label for="evaluasi_total" class="form-label">Evaluasi
                                                                        Total</label>
                                                                    <input type="text" class="form-control"
                                                                        name="evaluasi_total" id="evaluasi_total"
                                                                        value="<?php echo $evaluasi['evaluasi_total'] ?? ''; ?>"
                                                                        readonly>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary"
                                                                    data-bs-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include '../_partial/footer.php'; ?>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Add Soal Modal-->
    <div class="modal fade" id="addSoalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">
                        Data Soal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="data_attend_kelas.php?id_kelas=<?php $id_kelas; ?>" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                        <div class="form-floating form-floating-custom mb-3">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Masukan Nama Soal"
                                name="nama_soal" required />
                            <label for="floatingInput">Nama
                                Soal</label>
                        </div>
                        <div class="form-floating form-floating-custom mb-3">
                            <input type="file" class="form-control" id="floatingInput" name="file" required />
                            <label for="floatingInput">File Soal</label>
                        </div>
                        </tr>
                        <button type="submit" class="btn btn-outline-primary" name="tambah_soal">Tambah</button>
                    </form>
                    <div class="modal-body">
                        <div class="accordion mt-4" id="accordionExample">
                            <?php if ($resultSoal->num_rows > 0) {
                                while ($soal = $resultSoal->fetch_assoc()) {
                                    $id_soal = $soal['id_soal'];
                                    $nama_file_soal = $soal['file_soal'];
                                    $nama_soal = $soal['nama_soal'];

                                    // Query untuk mendapatkan murid yang telah mengunggah jawaban untuk soal ini
                                    $queryJawaban = "SELECT tb_murid.nama, tb_jawaban.file_jawaban AS file_jawaban, tb_evaluasi.evaluasi_total 
                                                        FROM tb_jawaban 
                                                        INNER JOIN tb_murid ON tb_jawaban.id_murid = tb_murid.id_murid 
                                                        LEFT JOIN tb_evaluasi ON tb_jawaban.id_jawaban = tb_evaluasi.id_jawaban
                                                        WHERE tb_jawaban.id_soal = ?";

                                    $stmtJawaban = $conn->prepare($queryJawaban);
                                    $stmtJawaban->bind_param("i", $id_soal);
                                    $stmtJawaban->execute();
                                    $resultJawaban = $stmtJawaban->get_result();
                                    ?>
                                    <div class="card accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $id_soal; ?>">
                                            <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?php echo $id_soal; ?>" aria-expanded="false"
                                                aria-controls="collapse<?php echo $id_soal; ?>">
                                                <?php echo htmlspecialchars($nama_soal, ENT_QUOTES, 'UTF-8'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $id_soal; ?>" class="accordion-collapse collapse"
                                            aria-labelledby="heading<?php echo $id_soal; ?>" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="table-responsive text-nowrap">
                                                    <table class="table border-top">
                                                        <thead>
                                                            <tr>
                                                                <th>Nama Murid</th>
                                                                <th>File Jawaban</th>
                                                                <th>Evaluasi Nilai</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php if ($resultJawaban->num_rows > 0) {
                                                                while ($jawaban = $resultJawaban->fetch_assoc()) { ?>
                                                                    <tr>
                                                                        <td><?php echo htmlspecialchars($jawaban['nama'], ENT_QUOTES, 'UTF-8'); ?>
                                                                        </td>
                                                                        <td>
                                                                            <a href="../../assets/file_jawaban/<?php echo htmlspecialchars($jawaban['file_jawaban'], ENT_QUOTES, 'UTF-8'); ?>"
                                                                                target="_blank">
                                                                                <?php echo htmlspecialchars($jawaban['file_jawaban'], ENT_QUOTES, 'UTF-8'); ?>
                                                                            </a>
                                                                        </td>
                                                                        <td>
                                                                            <?php echo $jawaban['evaluasi_total'] ?? 'Belum Input Nilai'; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php }
                                                            } else { ?>
                                                                <tr>
                                                                    <td colspan="2" class="text-center">Belum ada jawaban yang
                                                                        diunggah</td>
                                                                </tr>
                                                            <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">Belum ada File Soal</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Materi Modal-->
    <div class="modal fade" id="addMateriModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">
                        Data Materi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="data_attend_kelas.php?id_kelas=<?php $id_kelas; ?>" method="post"
                        enctype="multipart/form-data">
                        <input type="hidden" name="id_kelas" value="<?php echo $id_kelas; ?>">
                        <div class="form-floating form-floating-custom mb-3">
                            <input type="text" class="form-control" id="floatingInput" placeholder="Masukan Nama Materi"
                                name="nama_materi" required />
                            <label for="floatingInput">Nama
                                Materi</label>
                        </div>
                        <div class="form-floating form-floating-custom mb-3">
                            <input type="file" class="form-control" id="floatingInput" name="file" required />
                            <label for="floatingInput">File Materi</label>
                        </div>
                        </tr>
                        <button type="submit" class="btn btn-outline-primary" name="tambah_materi">Tambah</button>
                    </form>
                    <div class="modal-body">
                        <div class="accordion mt-4" id="accordionExample">
                            <?php if ($resultMateri->num_rows > 0) {
                                while ($materi = $resultMateri->fetch_assoc()) {
                                    $id_materi = $materi['id_materi'];
                                    $nama_file_materi = $materi['file_materi'];
                                    $nama_file = $materi['nama_materi'];

                                    ?>
                                    <div class="card accordion-item">
                                        <h2 class="accordion-header" id="heading<?php echo $id_materi; ?>">
                                            <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                                                data-bs-target="#collapse<?php echo $id_materi; ?>" aria-expanded="false"
                                                aria-controls="collapse<?php echo $id_materi; ?>">
                                                <?php echo htmlspecialchars($nama_file, ENT_QUOTES, 'UTF-8'); ?>
                                            </button>
                                        </h2>
                                        <div id="collapse<?php echo $id_materi; ?>" class="accordion-collapse collapse"
                                            aria-labelledby="heading<?php echo $id_materi; ?>"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <?php
                                                    $filePath = asset('file_materi/') . $nama_file_materi; // Pastikan path file benar.
                                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

                                                    if (in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif'])) {
                                                        echo '<img src="' . $filePath . '" alt="File Image" class="img-fluid">';
                                                    } elseif ($fileExtension === 'pdf') {
                                                        echo '<iframe src="' . $filePath . '" width="100%" height="500px" frameborder="0"></iframe>';
                                                    } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                                                        echo '<p><a href="' . $filePath . '" class="btn btn-primary">Download & View Word File</a></p>';
                                                    } else {
                                                        echo '<p>File type not supported for preview. <a href="' . $filePath . '" target="_blank">Download File</a></p>';
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            } else { ?>
                                <div class="text-center py-4">
                                    <p class="text-muted">Belum ada File Materi</p>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                            Close
                        </button>
                    </div>
                </div>
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
    <script>
        $('#myTableMurid').DataTable({
            fixedHeader: true,
            fixedColumns: true
        });

        $('#myTableTambah').DataTable({
            fixedHeader: true,
            fixedColumns: true
        });

        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.checkbox-item');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        function updatePresensi(id_murid, status) {
            $.ajax({
                url: 'update_presensi.php',
                method: 'POST',
                data: {
                    id_kelas: <?php echo $id_kelas; ?>,
                    id_murid: id_murid,
                    status: status
                },
                success: function (response) {
                    location.reload();
                }
            });
        }
    </script>
</body>

</html>