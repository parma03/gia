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


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
    $id_user = $_POST['id_user'];
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $file_ext;
    $file_path = BASE_PATH . 'assets/profile/' . $random_name;

    $query_cek = "SELECT COUNT(*) AS count FROM tb_user WHERE username = ?";
    $stmt_cek = $conn->prepare($query_cek);
    $stmt_cek->bind_param("s", $username);
    $stmt_cek->execute();
    $result = $stmt_cek->get_result();
    $data = $result->fetch_assoc();

    $query_get_old_file = "SELECT profile FROM tb_guru WHERE id_user = ?";
    $stmt = $conn->prepare($query_get_old_file);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_file = $row['profile'];

    if ($data['count'] > 1) {
        $_SESSION['notification'] = "Username sudah terdaftar.";
        $_SESSION['alert'] = "alert-danger";
        redirect('guru/profile.php');
        exit();
    } else {
        if (!empty($file_name) && move_uploaded_file($file_temp, $file_path)) {
            if (!empty($old_file) && file_exists($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/profile/' . $old_file)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/profile/' . $old_file);
            }

            $query_user = "UPDATE tb_user SET username = ?, password = ? WHERE id_user = ?";
            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("ssi", $username, $password, $id_user);
            if ($stmt->execute()) {
                $query_admin = "UPDATE tb_guru SET nama = ?, profile = ? WHERE id_user = ?";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("ssi", $nama, $random_name, $id_user);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Pimpinan berhasil diupdate.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('guru/logout.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            $query_user = "UPDATE tb_user SET username = ?, password = ? WHERE id_user = ?";
            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("ssi", $username, $password, $id_user);
            if ($stmt->execute()) {
                $query_admin = "UPDATE tb_guru SET nama = ? WHERE id_user = ?";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("si", $nama, $id_user);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Pimpinan berhasil diupdate.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('guru/logout.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        }
    }

    $stmt_cek->close();
    $stmt->close();
    $conn->close();
}

$conn->close();
?>
<!doctype html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default"
    data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

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
                    <div
                        class="container-xxl flex-grow-1 container-p-y d-flex justify-content-center align-items-center">
                        <div class="col-sm-6 col-lg-8">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5 class="card-title">My Profile</h5>
                                    <center>
                                        <div class="col-md-3">
                                            <?php if ($profile === NULL) { ?>
                                                <img class="card-img card-img-center align-items-center"
                                                    src="../assets/profile/1.png" />
                                            <?php } else { ?>
                                                <img class="card-img card-img-center align-items-center"
                                                    src="../assets/profile/<?php echo $profile; ?>" />
                                            <?php } ?>
                                        </div>
                                    </center>
                                    <form action="profile.php" method="post" enctype="multipart/form-data">
                                        <div class="card-body">
                                            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                            <div class="form-floating form-floating-custom mb-3">
                                                <input type="file" class="form-control" id="floatingInput"
                                                    name="file" />
                                                <label for="floatingInput">Profile</label>
                                            </div>
                                            <div class="form-group">
                                                <div class="form-floating form-floating-custom mb-3">
                                                    <input type="text" class="form-control" id="floatingInput"
                                                        placeholder="Masukan Nama" name="nama"
                                                        value="<?php echo $nama; ?>" />
                                                    <label for="floatingInput">Nama</label>
                                                </div>
                                            </div>
                                            <div class="row g-6">
                                                <div class="col-sm-6">
                                                    <div class="mb-4">
                                                        <div class="form-floating form-floating-custom mb-3">
                                                            <input type="text" class="form-control" id="floatingInput"
                                                                placeholder="Masukan Username" name="username"
                                                                value="<?php echo $username; ?>" />
                                                            <label for="floatingInput">Username</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-4">
                                                        <div
                                                            class="form-floating form-password-toggle form-floating-custom mb-3 position-relative">
                                                            <input type="password" class="form-control"
                                                                id="floatingPassword" placeholder="Password"
                                                                name="password" required />
                                                            <label for="floatingPassword">Password</label>
                                                            <span
                                                                class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer toggle-password"
                                                                id="basic-default-password2">
                                                                <i class="bx bx-hide" id="passwordToggleIcon"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-outline-primary"
                                                name="update">Update</button>
                                        </div>
                                    </form>
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

    <!-- Add Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">
                        Tambah Data Admin
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="admin.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="file" class="form-control" id="floatingInput" name="file" />
                                    <label for="floatingInput">Profile</label>
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control" id="floatingInput"
                                        placeholder="Masukan Nama Admin" name="nama" />
                                    <label for="floatingInput">Nama
                                        Admin</label>
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control" id="floatingInput"
                                        placeholder="Masukan Username" name="username" />
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div
                                    class="form-floating form-password-toggle form-floating-custom mb-3 position-relative">
                                    <input type="password" class="form-control" id="floatingPassword"
                                        placeholder="Password" name="password" required />
                                    <label for="floatingPassword">Password</label>
                                    <!-- Toggle Visibility -->
                                    <span
                                        class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer toggle-password"
                                        id="basic-default-password2">
                                        <i class="bx bx-hide" id="passwordToggleIcon"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-outline-primary" name="tambah">Tambah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->

    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="../assets/vendor/libs/apex-charts/apexcharts.js"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->
    <script src="../assets/js/dashboards-analytics.js"></script>

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