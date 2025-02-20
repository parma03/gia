<?php
session_start();
include 'config/koneksi.php';

// Pengecekan session untuk redirect jika sudah login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Admin') {
        header("Location: admin/index.php");
        exit();
    } else if ($_SESSION['role'] === 'Murid') {
        header("Location: murid/index.php");
        exit();
    } else if ($_SESSION['role'] === 'Guru') {
        header("Location: guru/index.php");
        exit();
    } else if ($_SESSION['role'] === 'Pimpinan') {
        header("Location: pimpinan/index.php");
        exit();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM tb_user WHERE username=? AND password=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        $_SESSION['id_user'] = $user['id_user'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['username'] = $user['username'];
        $id_user = $_SESSION['id_user'];
        if ($user['role'] === 'Admin') {
            $query = "SELECT * FROM tb_admin WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            $_SESSION['nama'] = $admin['nama'];
            $_SESSION['profile'] = $admin['profile'];
            header("Location: admin/index.php");
            exit();
        } else if ($user['role'] === 'Murid') {
            $query = "SELECT * FROM tb_murid WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $murid = $result->fetch_assoc();
            $_SESSION['nama'] = $murid['nama'];
            $_SESSION['id_murid'] = $murid['id_murid'];
            $_SESSION['profile'] = $murid['profile'];
            header("Location: murid/index.php");
            exit();
        } else if ($user['role'] === 'Pimpinan') {
            $query = "SELECT * FROM tb_pimpinan WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $pimpinan = $result->fetch_assoc();
            $_SESSION['nama'] = $pimpinan['nama'];
            $_SESSION['profile'] = $pimpinan['profile'];
            header("Location: pimpinan/index.php");
            exit();
        } else if ($user['role'] === 'Guru') {
            $query = "SELECT * FROM tb_guru WHERE id_user = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id_user);
            $stmt->execute();
            $result = $stmt->get_result();
            $guru = $result->fetch_assoc();
            $_SESSION['nama'] = $guru['nama'];
            $_SESSION['id_guru'] = $guru['id_guru'];
            $_SESSION['profile'] = $guru['profile'];
            header("Location: guru/index.php");
            exit();
        }
    } else {
        $_SESSION['notification'] = "Username atau Password Salah.";
        $_SESSION['alert'] = "danger";
    }
    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en" class="light-style customizer-hide" dir="ltr" data-theme="theme-default" data-assets-path="assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>Login | Global Intelligence Academy</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/img/logo.png" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons. Uncomment required icon fonts -->
    <link rel="stylesheet" href="assets/vendor/fonts/boxicons.css" />

    <!-- Core CSS -->
    <link rel="stylesheet" href="assets/vendor/css/core.css" class="template-customizer-core-css" />
    <link rel="stylesheet" href="assets/vendor/css/theme-default.css" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="assets/css/demo.css" />

    <!-- Vendors CSS -->
    <link rel="stylesheet" href="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="assets/vendor/css/pages/page-auth.css" />
    <!-- Helpers -->
    <script src="assets/vendor/js/helpers.js"></script>
    <script src="assets/js/config.js"></script>
</head>

<body>
    <!-- Content -->
    <div class="container-xxl">
        <!-- Notification -->
        <?php if (isset($_SESSION['notification'])): ?>
            <div class="alert alert-<?php echo $_SESSION['alert']; ?> alert-dismissible fade show" role="alert">
                <?php echo $_SESSION['notification'];
                unset($_SESSION['notification']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <script>
                setTimeout(function () {
                    document.querySelector('.alert').style.display = 'none';
                }, 5000);
            </script>
        <?php endif; ?>
        <div class="authentication-wrapper authentication-basic container-p-y">
            <div class="authentication-inner">
                <!-- Register -->
                <div class="card">
                    <div class="card-body">
                        <!-- Logo -->
                        <div class="app-brand justify-content-center">
                            <a href="index.php" class="app-brand-link gap-2">
                                <span class="app-brand-logo demo">
                                    <img src="assets/img/logo.png" width="150" height="150">
                                </span>
                            </a>
                        </div>
                        <!-- /Logo -->
                        <h4 class="mb-2">LOGIN</h4>

                        <form id="formAuthentication" class="mb-3" action="index.php" method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username"
                                    placeholder="Masukan Username username" autofocus />
                            </div>
                            <div class="mb-3 form-password-toggle">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label" for="password">Password</label>
                                </div>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="password" class="form-control" name="password"
                                        placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                            </div>
                            <div class="mb-3">
                                <button class="btn btn-primary d-grid w-100" name="login" type="submit">Sign in</button>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Register -->
            </div>
        </div>
    </div>

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="assets/vendor/libs/jquery/jquery.js"></script>
    <script src="assets/vendor/libs/popper/popper.js"></script>
    <script src="assets/vendor/js/bootstrap.js"></script>
    <script src="assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

    <script src="assets/vendor/js/menu.js"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->
    <script src="assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag in your head or just before your close body tag. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
</body>

</html>