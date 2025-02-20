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
    } else if ($_SESSION['role'] === 'Guru') {
        redirect('guru/index.php');
        exit();
    }
} else {
    redirect('index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['cetak'])) {
    $laporantittle = "Data Laporan Evaluasi Kelas";
    $id_kelas = $_POST['id_kelas'];
    $nama_kelas = $_POST['nama_kelas'];
    $nama_guru = $_POST['nama_guru'];

    $query = "SELECT tb_kelas.nama_kelas, 
    tb_guru.nama AS nama_guru, 
    tb_murid.nama AS nama_murid, 
    tb_evaluasi.evaluasi_total
    FROM tb_kelas 
    LEFT JOIN tb_guru ON tb_kelas.id_guru = tb_guru.id_guru 
    LEFT JOIN tb_kelas_assesment ON tb_kelas.id_kelas = tb_kelas_assesment.id_kelas
    LEFT JOIN tb_murid ON tb_kelas_assesment.id_murid = tb_murid.id_murid
    LEFT JOIN tb_evaluasi ON tb_kelas.id_kelas = tb_evaluasi.id_kelas_assesment
    WHERE tb_kelas_assesment.id_kelas = '$id_kelas'
    ORDER BY tb_murid.nama ASC";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['laporan'] = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $_SESSION['tittle'] = $laporantittle;
        $_SESSION['nama_kelas'] = $nama_kelas;
        $_SESSION['nama_guru'] = $nama_guru;
        header("Location: printevaluasi.php");
        exit();
    } else {
        echo "Tidak ada data yang ditemukan.";
    }
}

$querys = "SELECT * FROM tb_kelas INNER JOIN tb_guru ON tb_kelas.id_guru = tb_guru.id_guru";
$result_querys = $conn->query($querys);

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];
$profile = $_SESSION['profile'];

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?php echo asset(''); ?>" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Dashboard - <?php echo $role; ?> | Global Intelligence Academy</title>

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
                    <div
                        class="container-xxl flex-grow-1 container-p-y d-flex justify-content-center align-items-center">
                        <div class="col-sm-6 col-lg-8">
                            <div class="card text-center">
                                <div class="card-body">
                                    <form action="laporan-evaluasi.php" method="post">
                                        <h5 class="card-title">Cetak Laporan Evaluasi Kelas</h5>
                                        <div class="form-floating form-floating-custom mb-3">
                                            <select class="form-select" id="selectFloatingLabel" name="id_kelas"
                                                required>
                                                <option value="" disabled selected>Pilih Kelas</option>
                                                <?php
                                                while ($row = $result_querys->fetch_assoc()) {
                                                    echo '<option value="' . $row['id_kelas'] . '" data-nama-guru="' . $row['nama'] . '" data-nama-kelas="' . $row['nama_kelas'] . '">' . $row['nama_kelas'] . '</option>';
                                                }
                                                ?>
                                            </select>
                                            <label for="selectFloatingLabel">Pilih Kelas</label>
                                        </div>
                                        <input type="hidden" id="nama_guru" name="nama_guru">
                                        <input type="hidden" id="nama_kelas" name="nama_kelas">
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-outline-primary"
                                                name="cetak">Cetak</button>
                                        </div>
                                    </form>
                                </div>
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
    <script>
    document.getElementById("selectFloatingLabel").addEventListener("change", function () {
        var selectedOption = this.options[this.selectedIndex];
        document.getElementById("nama_guru").value = selectedOption.getAttribute("data-nama-guru");
        document.getElementById("nama_kelas").value = selectedOption.getAttribute("data-nama-kelas");
    });
</script>

</body>

</html>