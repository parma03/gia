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
$id_guru = $_SESSION['id_guru'];
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];
$profile = $_SESSION['profile'];

$query = "SELECT tb_kelas.id_kelas, tb_kelas.nama_kelas, tb_kelas.nama_ruangan, tb_kelas.id_guru, 
          tb_guru.nama, tb_kelas.duty_start_day, tb_kelas.duty_end_day, 
          tb_kelas.duty_start_time, tb_kelas.duty_end_time, COUNT(tb_kelas_assesment.id_murid) AS jumlah_murid
          FROM tb_kelas
          LEFT JOIN tb_guru ON tb_kelas.id_guru = tb_guru.id_guru
          LEFT JOIN tb_kelas_assesment ON tb_kelas.id_kelas = tb_kelas_assesment.id_kelas WHERE tb_kelas.id_guru = '$id_guru'
          GROUP BY tb_kelas.id_kelas";
$result = $conn->query($query);

$query_guru_tambah = "SELECT id_guru, nama FROM tb_guru";
$result_guru_tambah = $conn->query($query_guru_tambah);
$query_guru = "SELECT id_guru, nama FROM tb_guru";
$result_guru = $conn->query($query_guru);
$gurus = [];
while ($guru = mysqli_fetch_array($result_guru)) {
    $gurus[] = $guru;
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr" data-theme="theme-default"
    data-assets-path="<?php echo asset(''); ?>" data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Data Kelas - <?php echo $role; ?> | Global Intelligence Academy</title>

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
                                <h5 class="card-header">Data Kelas</h5>
                                <!-- Tombol di sebelah kanan -->
                                <!-- <button type="button" class="btn btn-icon btn-outline-primary me-3"
                                    data-bs-toggle="modal" data-bs-target="#addModal">
                                    <span class="tf-icons bx bx-user-plus bx-22px"></span>
                                </button> -->
                            </div>
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myTable" class="datatables-basic table border-top">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th>Nama Kelas</th>
                                            <th>Ruangan</th>
                                            <th>Nama Guru</th>
                                            <th>Jadwal</th>
                                            <th>Jumlah Siswa</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td><?php echo $no++ ?></td>
                                                <td><?php echo $data['nama_kelas']; ?></td>
                                                <td><?php echo $data['nama_ruangan']; ?></td>
                                                <td><?php echo $data['nama']; ?></td>
                                                <td><?php echo $data['duty_start_day']; ?> -
                                                    <?php echo $data['duty_end_day']; ?>,
                                                    <?php echo $data['duty_start_time']; ?> -
                                                    <?php echo $data['duty_end_time']; ?>
                                                </td>
                                                <td><?php echo $data['jumlah_murid']; ?></td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <a href="data_attend_kelas.php?id_kelas=<?php echo $data["id_kelas"]; ?>"
                                                            class="btn btn-sm text-primary btn-icon item-edit">
                                                            <i class="bx bxs-show"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
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
        $('#myTable').DataTable({
            fixedHeader: true,
            fixedColumns: true
        });
    </script>
</body>

</html>