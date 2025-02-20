<?php
session_start();
include '../../config/koneksi.php';


// Pengecekan session untuk redirect jika sudah login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Guru') {
        redirect('guru/index.php');
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
$id_murid = $_SESSION['id_murid'];
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];
$profile = $_SESSION['profile'];

$query = "SELECT tb_kelas.id_kelas, tb_kelas.nama_kelas, tb_kelas.nama_ruangan, tb_kelas.id_guru, 
          tb_guru.nama, tb_kelas.duty_start_day, tb_kelas.duty_end_day, 
          tb_kelas.duty_start_time, tb_kelas.duty_end_time, COUNT(tb_kelas_assesment.id_murid) AS jumlah_murid
          FROM tb_kelas
          LEFT JOIN tb_guru ON tb_kelas.id_guru = tb_guru.id_guru
          LEFT JOIN tb_kelas_assesment ON tb_kelas.id_kelas = tb_kelas_assesment.id_kelas WHERE tb_kelas_assesment.id_murid = '$id_murid'
          GROUP BY tb_kelas.id_kelas";
$result = $conn->query($query);

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
                                            <th>Nama Kelas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td>
                                                    <div class="accordion mt-4" id="accordionExample">
                                                        <h2 class="accordion-header"
                                                            id="heading<?php echo $data['id_kelas']; ?>">
                                                            <button type="button" class="accordion-button collapsed"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#collapse<?php echo $data['id_kelas']; ?>"
                                                                aria-expanded="false"
                                                                aria-controls="collapse<?php echo $data['id_kelas']; ?>">
                                                                <h4>
                                                                    <?php echo htmlspecialchars($data['nama_kelas'], ENT_QUOTES, 'UTF-8'); ?>
                                                                    <figure class="mt-2">
                                                                        <blockquote class="blockquote">
                                                                            <p class="mb-0">
                                                                                <?php echo $data['nama_ruangan']; ?>
                                                                            </p>
                                                                        </blockquote>
                                                                        <figcaption class="blockquote-footer">
                                                                            <?php echo $data['nama']; ?>
                                                                        </figcaption>
                                                                    </figure>
                                                                </h4>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse<?php echo $data['id_kelas']; ?>"
                                                            class="accordion-collapse collapse"
                                                            aria-labelledby="heading<?php echo $data['id_kelas']; ?>"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-lg">
                                                                        <div class="mt-4">
                                                                            <div class="list-group list-group-horizontal-md text-md-center"
                                                                                role="tablist">
                                                                                <a class="list-group-item list-group-item-action active"
                                                                                    id="home-list-item"
                                                                                    data-bs-toggle="list"
                                                                                    href="#horizontal-home"
                                                                                    aria-selected="true"
                                                                                    role="tab">Informasi Kelas</a>
                                                                                <a class="list-group-item list-group-item-action"
                                                                                    id="materi-list-item"
                                                                                    data-bs-toggle="list"
                                                                                    href="#horizontal-materi"
                                                                                    aria-selected="false" role="tab"
                                                                                    tabindex="-1"
                                                                                    onclick="showMaterials(<?php echo $data['id_kelas']; ?>)">Materi
                                                                                    Kelas</a>
                                                                                <a class="list-group-item list-group-item-action"
                                                                                    id="soal-list-item"
                                                                                    data-bs-toggle="list"
                                                                                    href="#horizontal-soal"
                                                                                    aria-selected="false" role="tab"
                                                                                    tabindex="-1"
                                                                                    onclick="showMaterials2(<?php echo $data['id_kelas']; ?>, <?php echo $id_murid; ?>)">Data
                                                                                    Soal</a>
                                                                                <a class="list-group-item list-group-item-action"
                                                                                    id="presensi-list-item"
                                                                                    data-bs-toggle="list"
                                                                                    href="#horizontal-presensi"
                                                                                    aria-selected="false" tabindex="-1"
                                                                                    onclick="showMaterials3(<?php echo $data['id_kelas']; ?>, <?php echo $id_murid; ?>)"
                                                                                    role="tab">Presensi</a>
                                                                                <a class="list-group-item list-group-item-action"
                                                                                    id="evaluasi-list-item"
                                                                                    data-bs-toggle="list"
                                                                                    href="#horizontal-evaluasi"
                                                                                    aria-selected="false" tabindex="-1"
                                                                                    onclick="showMaterials4(<?php echo $data['id_kelas']; ?>, <?php echo $id_murid; ?>)"
                                                                                    role="tab">Evaluasi</a>
                                                                            </div>
                                                                            <div class="tab-content px-0 mt-0">
                                                                                <div class="tab-pane fade active show"
                                                                                    id="horizontal-home" role="tabpanel"
                                                                                    aria-labelledby="home-list-item">
                                                                                    <div class="col-lg mb-6 mb-xl-0">
                                                                                        <div class="mt-4">
                                                                                            <ol class="list-group">
                                                                                                <li class="list-group-item">
                                                                                                    Hari: <br>
                                                                                                    <?php echo $data['duty_start_day']; ?>
                                                                                                    -
                                                                                                    <?php echo $data['duty_end_day']; ?>
                                                                                                </li>
                                                                                                <li class="list-group-item">
                                                                                                    Jam: <br>
                                                                                                    <?php echo $data['duty_start_time']; ?>
                                                                                                    -
                                                                                                    <?php echo $data['duty_end_time']; ?>
                                                                                                </li>
                                                                                            </ol>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="horizontal-materi" role="tabpanel"
                                                                                    aria-labelledby="materi-list-item">
                                                                                    <div class="col-lg" id="materiContent">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="horizontal-soal" role="tabpanel"
                                                                                    aria-labelledby="soal-list-item">
                                                                                    <div class="col-lg" id="soalContent">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="horizontal-presensi" role="tabpanel"
                                                                                    aria-labelledby="presensi-list-item">
                                                                                    <div class="col-lg"
                                                                                        id="presensiContent">
                                                                                    </div>
                                                                                </div>
                                                                                <div class="tab-pane fade"
                                                                                    id="horizontal-evaluasi" role="tabpanel"
                                                                                    aria-labelledby="evaluasi-list-item">
                                                                                    <div class="col-lg"
                                                                                        id="evaluasiContent">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>

        function showMaterials(idKelas) {
            // Fetch materi data using AJAX
            $.ajax({
                url: 'get_materials.php',
                type: 'POST',
                data: { id_kelas: idKelas },
                success: function (response) {
                    $('#materiContent').html(response);
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengambil data materi');
                }
            });
        }

        function showMaterials2(idKelas, idMurid) {
            // Fetch materi data using AJAX
            $.ajax({
                url: 'get_materials2.php',
                type: 'POST',
                data: { id_kelas: idKelas, id_murid: idMurid },
                success: function (response) {
                    $('#soalContent').html(response);
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengambil data materi');
                }
            });
        }

        function showMaterials3(idKelas, idMurid) {
            // Fetch materi data using AJAX
            $.ajax({
                url: 'get_materials3.php',
                type: 'POST',
                data: { id_kelas: idKelas, id_murid: idMurid },
                success: function (response) {
                    $('#presensiContent').html(response);
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengambil data materi');
                }
            });
        }

        function showMaterials4(idKelas, idMurid) {
            // Fetch materi data using AJAX
            $.ajax({
                url: 'get_materials4.php',
                type: 'POST',
                data: { id_kelas: idKelas, id_murid: idMurid },
                success: function (response) {
                    $('#evaluasiContent').html(response);
                },
                error: function () {
                    alert('Terjadi kesalahan saat mengambil data materi');
                }
            });
        }

        // Initialize DataTable
        $(document).ready(function () {
            $('#myTable').DataTable({
                responsive: true,
                order: [[0, 'asc']]
            });
        });
    </script>
</body>

</html>