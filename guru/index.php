<?php
session_start();
include '../config/koneksi.php';

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

// Query untuk mendapatkan jumlah murid
$query = "
    SELECT 
        (SELECT COUNT(*) FROM tb_kelas WHERE id_guru = '$id_guru') AS jumlah_kelas
";
$result = $conn->query($query);

if ($result && $row = $result->fetch_assoc()) {
    $jumlah_kelas = $row['jumlah_kelas'];
} else {
    echo "Error: " . $conn->error;
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
            <?php include '_partial/menu.php'; ?>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <?php include '_partial/navbar.php'; ?>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-lg-8 mb-4 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Welcome Back, <?php echo $nama ?>!
                                                    🎉</h5>
                                                <br><br><br>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-4">
                                                <img src="<?php echo asset('img/illustrations/man-with-laptop-light.png'); ?>"
                                                    height="140" alt="View Badge User"
                                                    data-app-dark-img="<?php echo asset('img/illustrations/man-with-laptop-dark.png'); ?>"
                                                    data-app-light-img="<?php echo asset('img/illustrations/man-with-laptop-light.png'); ?>" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 order-1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div
                                                    class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                    </div>
                                                    <div class="dropdown">
                                                        <button class="btn p-0" type="button" id="cardOpt6"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <!-- <i class="bx bx-dots-vertical-rounded"></i> -->
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end"
                                                            aria-labelledby="cardOpt6">
                                                            <a class="dropdown-item" href="kelas/data_kelas.php">View
                                                                More</a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <span>kelas: </span>
                                                <h3 class="card-title text-nowrap mb-1"><?php echo $jumlah_kelas; ?>
                                                </h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <?php include '_partial/footer.php'; ?>
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
</body>

</html>