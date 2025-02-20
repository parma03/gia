<?php
session_start();
include '../../config/koneksi.php';

// Pengecekan session untuk redirect jika sudah login
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'Murid') {
        redirect('murid/index.php');
        exit();
    } else if ($_SESSION['role'] === 'Guru') {
        redirect('guru/index.php');
        exit();
    } else if ($_SESSION['role'] === 'Pimpinan') {
        redirect('pimpinan/index.php');
        exit();
    }
} else {
    redirect('index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah'])) {
    $nama = $_POST['nama'];
    $role = "Murid";
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

    if ($data['count'] > 0) {
        $_SESSION['notification'] = "Username sudah terdaftar.";
        $_SESSION['alert'] = "alert-danger";
        redirect('admin/user/murid.php');
        exit();
    } else {
        if (move_uploaded_file($file_temp, $file_path)) {
            $query_user = "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                $last_id = $stmt->insert_id;
                $query_admin = "INSERT INTO tb_murid (id_user, nama, profile) VALUES (?, ?, ?)";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("iss", $last_id, $nama, $random_name);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Murid berhasil ditambah.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('admin/user/murid.php');
                    exit();
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Error: " . $stmt->error;
            }
        } else {
            $query_user = "INSERT INTO tb_user (username, password, role) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query_user);
            $stmt->bind_param("sss", $username, $password, $role);

            if ($stmt->execute()) {
                $last_id = $stmt->insert_id;
                $query_admin = "INSERT INTO tb_murid (id_user, nama) VALUES (?, ?)";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("is", $last_id, $nama);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Murid berhasil ditambah.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('admin/user/murid.php');
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
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

    $query_get_old_file = "SELECT profile FROM tb_murid WHERE id_user = ?";
    $stmt = $conn->prepare($query_get_old_file);
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $old_file = $row['profile'];

    if ($data['count'] > 1) {
        $_SESSION['notification'] = "Username sudah terdaftar.";
        $_SESSION['alert'] = "alert-danger";
        redirect('admin/user/murid.php');
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
                $query_admin = "UPDATE tb_murid SET nama = ?, profile = ? WHERE id_user = ?";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("ssi", $nama, $random_name, $id_user);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Murid berhasil diupdate.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('admin/user/murid.php');
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
                $query_admin = "UPDATE tb_murid SET nama = ? WHERE id_user = ?";
                $stmt = $conn->prepare($query_admin);
                $stmt->bind_param("si", $nama, $id_user);
                if ($stmt->execute()) {
                    $_SESSION['notification'] = "Data Murid berhasil diupdate.";
                    $_SESSION['alert'] = "alert-success";
                    redirect('admin/user/murid.php');
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete'])) {
    $id_user = $_POST['id_user'];

    $query_select = "SELECT profile FROM tb_murid WHERE id_user = ?";
    $stmt_select = $conn->prepare($query_select);
    $stmt_select->bind_param("i", $id_user);
    $stmt_select->execute();
    $result = $stmt_select->get_result()->fetch_assoc();
    $profile = @$result['profile'];

    if ($profile && file_exists($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/profile/' . $profile)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/profile/' . $profile);
    }

    $query_admin = "DELETE FROM tb_user WHERE id_user = ?";
    $stmt = $conn->prepare($query_admin);
    $stmt->bind_param("i", $id_user);

    if ($stmt->execute()) {
        $query_delete = "DELETE FROM tb_murid WHERE id_user = ?";
        $stmtDelete = $conn->prepare($query_delete);
        $stmtDelete->bind_param("i", $id_user);
        if ($stmtDelete->execute()) {
            $_SESSION['notification'] = "Data Murid berhasil dihapus.";
            $_SESSION['alert'] = "alert-success";
            redirect('admin/user/murid.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $stmt_cek->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['tambah_wali'])) {
    $id_murid = $_POST['id_murid'];
    $nama_wali = $_POST['nama_wali'];
    $alamat_wali = $_POST['alamat_wali'];

    $query_user = "INSERT INTO tb_wali (id_murids, nama_wali, alamat_wali) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query_user);
    $stmt->bind_param("iss", $id_murid, $nama_wali, $alamat_wali);

    if ($stmt->execute()) {
        $_SESSION['notification'] = "Data Wali Murid berhasil ditambah.";
        $_SESSION['alert'] = "alert-success";
        redirect('admin/user/murid.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_wali'])) {
    $id_murid = $_POST['id_murid'];
    $nama_wali = $_POST['nama_wali'];
    $alamat_wali = $_POST['alamat_wali'];

    $query_user = "UPDATE tb_wali SET nama_wali = ?, alamat_wali = ? WHERE id_murids = ?";
    $stmt = $conn->prepare($query_user);
    $stmt->bind_param("ssi", $nama_wali, $alamat_wali, $id_murid);
    if ($stmt->execute()) {
        $_SESSION['notification'] = "Data Wali Murid berhasil diupdate.";
        $_SESSION['alert'] = "alert-success";
        redirect('admin/user/murid.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}

$id_user = $_SESSION['id_user'];
$role = $_SESSION['role'];
$username = $_SESSION['username'];
$nama = $_SESSION['nama'];
$profile = $_SESSION['profile'];

$query = "SELECT * FROM tb_user INNER JOIN tb_murid ON tb_user.id_user = tb_murid.id_user LEFT JOIN tb_wali ON tb_murid.id_murid = tb_wali.id_murids";
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

    <title>Data Murid - <?php echo $role; ?> | Global Intelligence Academy</title>

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
                                <h5 class="card-header">Data Murid</h5>
                                <!-- Tombol di sebelah kanan -->
                                <button type="button" class="btn btn-icon btn-outline-primary me-3"
                                    data-bs-toggle="modal" data-bs-target="#addModal">
                                    <span class="tf-icons bx bx-user-plus bx-22px"></span>
                                </button>
                            </div>
                            <div class="card-datatable table-responsive pt-0">
                                <table id="myTable" class="datatables-basic table border-top">
                                    <thead>
                                        <tr>
                                            <th width="1%">No</th>
                                            <th width="1%">Profile</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Informasi Wali</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        while ($data = mysqli_fetch_array($result)) { ?>
                                            <tr>
                                                <td><?php echo $no++ ?></td>
                                                <td>
                                                    <div class="avatar avatar-small">
                                                        <?php if ($data["profile"] === NULL) { ?>
                                                            <img class="avatar-img rounded-circle"
                                                                src="<?php echo asset('img/avatars/1.png'); ?>" />
                                                        <?php } else { ?>
                                                            <img class="avatar-img rounded-circle"
                                                                src="<?php echo asset('profile/' . $data['profile']); ?>" />
                                                        <?php } ?>
                                                    </div>
                                                </td>
                                                <td><?php echo $data['nama']; ?></td>
                                                <td><?php echo $data['username']; ?></td>
                                                <td>
                                                    <?php if ($data["id_wali"] === NULL) { ?>
                                                        <div class="form-button-action">
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#tambahModalWali<?php echo $data["id_murid"]; ?>"
                                                                class="btn btn-sm text-primary btn-icon item-add">
                                                                <i class="bx bxs-add-to-queue"></i>
                                                            </a>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="form-button-action">
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#showModalWali"
                                                                class="btn btn-sm text-primary btn-icon item-edit"
                                                                data-idMurid="<?php echo $data['id_murid']; ?>"
                                                                data-nama="<?php echo $data['nama']; ?>"
                                                                data-nama-wali="<?php echo $data['nama_wali']; ?>"
                                                                data-alamat-wali="<?php echo $data['alamat_wali']; ?>">
                                                                <i class="bx bxs-show"></i>
                                                            </a>
                                                            <a href="#" data-bs-toggle="modal"
                                                                data-bs-target="#editModalWali<?php echo $data["id_murid"]; ?>"
                                                                class="btn btn-sm text-warning btn-icon item-edit">
                                                                <i class="bx bxs-edit"></i>
                                                            </a>
                                                        </div>
                                                    <?php } ?>
                                                </td>
                                                <td>
                                                    <div class="form-button-action">
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#editModal<?php echo $data["id_user"]; ?>"
                                                            class="btn btn-sm text-warning btn-icon item-edit">
                                                            <i class="bx bxs-edit"></i>
                                                        </a>
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal<?php echo $data["id_user"]; ?>"
                                                            class="btn btn-sm text-danger btn-icon item-delete">
                                                            <i class="bx bxs-trash"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Tambah Modal Wali-->
                                            <div class="modal fade" id="tambahModalWali<?php echo $data["id_murid"]; ?>"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">
                                                                Tambah Data Wali <?php echo $data['nama']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="murid.php" method="post"
                                                                enctype="multipart/form-data">
                                                                <input type="hidden" name="id_murid"
                                                                    value="<?php echo $data["id_murid"]; ?>" />
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Nama Wali Murid"
                                                                                name="nama_wali"
                                                                                value="<?php echo $data["nama_wali"]; ?>" />
                                                                            <label for="floatingInput">Nama
                                                                                Wali Murid</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Alamat Wali Murid"
                                                                                name="alamat_wali"
                                                                                value="<?php echo $data["alamat_wali"]; ?>" />
                                                                            <label for="floatingInput">Alamat
                                                                                Wali Murid</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-danger"
                                                                        data-bs-dismiss="modal">
                                                                        Close
                                                                    </button>&nbsp;&nbsp;
                                                                    <button type="submit" class="btn btn-outline-primary"
                                                                        name="tambah_wali">Tambah</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Modal Wali-->
                                            <div class="modal fade" id="editModalWali<?php echo $data["id_murid"]; ?>"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">
                                                                Update Data Wali <?php echo $data['nama']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="murid.php" method="post"
                                                                enctype="multipart/form-data">
                                                                <input type="hidden" name="id_murid"
                                                                    value="<?php echo $data["id_murid"]; ?>" />
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Nama Wali Murid"
                                                                                name="nama_wali"
                                                                                value="<?php echo $data["nama_wali"]; ?>" />
                                                                            <label for="floatingInput">Nama
                                                                                Wali Murid</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Alamat Wali Murid"
                                                                                name="alamat_wali"
                                                                                value="<?php echo $data["alamat_wali"]; ?>" />
                                                                            <label for="floatingInput">Alamat
                                                                                Wali Murid</label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-danger"
                                                                        data-bs-dismiss="modal">
                                                                        Close
                                                                    </button>&nbsp;&nbsp;
                                                                    <button type="submit" class="btn btn-outline-primary"
                                                                        name="update_wali">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Edit Modal-->
                                            <div class="modal fade" id="editModal<?php echo $data["id_user"]; ?>"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">
                                                                Update Data <?php echo $data['nama']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <center>
                                                                <div class="col-md-3">
                                                                    <?php if ($data["profile"] === NULL) { ?>
                                                                        <img class="card-img card-img-center align-items-center"
                                                                            src="<?php echo asset('img/avatars/1.png'); ?>" />
                                                                    <?php } else { ?>
                                                                        <img class="card-img card-img-center align-items-center"
                                                                            src="<?php echo asset('profile/' . $data['profile']); ?>" />
                                                                    <?php } ?>
                                                                </div>
                                                            </center>
                                                            <form action="murid.php" method="post"
                                                                enctype="multipart/form-data">
                                                                <input type="hidden" name="id_user"
                                                                    value="<?php echo $data["id_user"]; ?>" />
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="file" class="form-control"
                                                                                id="floatingInput" name="file"
                                                                                value="<?php echo $data["profile"]; ?>" />
                                                                            <label for="floatingInput">Profile</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Nama Murid" name="nama"
                                                                                value="<?php echo $data["nama"]; ?>" />
                                                                            <label for="floatingInput">Nama
                                                                                Murid</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-floating form-floating-custom mb-3">
                                                                            <input type="text" class="form-control"
                                                                                id="floatingInput"
                                                                                placeholder="Masukan Username"
                                                                                name="username"
                                                                                value="<?php echo $data["username"]; ?>" />
                                                                            <label for="floatingInput">Username</label>
                                                                        </div>
                                                                        <div
                                                                            class="form-floating form-password-toggle form-floating-custom mb-3 position-relative">
                                                                            <input type="password" class="form-control"
                                                                                id="floatingPassword" placeholder="Password"
                                                                                name="password"
                                                                                value="<?php echo $data["password"]; ?>" />
                                                                            <label for="floatingPassword">Password</label>
                                                                            <!-- Toggle Visibility -->
                                                                            <span
                                                                                class="position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer toggle-password"
                                                                                id="basic-default-password2">
                                                                                <i class="bx bx-hide"
                                                                                    id="passwordToggleIcon"></i>
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-danger"
                                                                        data-bs-dismiss="modal">
                                                                        Close
                                                                    </button>&nbsp;&nbsp;
                                                                    <button type="submit" class="btn btn-outline-primary"
                                                                        name="edit">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Delete Modal-->
                                            <div class="modal fade" id="deleteModal<?php echo $data["id_user"]; ?>"
                                                tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel1">
                                                                Delete Data <?php echo $data['nama']; ?>
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form action="murid.php" method="post">
                                                                <input type="hidden" name="id_user"
                                                                    value="<?php echo $data["id_user"]; ?>" />
                                                                <div class="modal-body">
                                                                    <label for="exampleInputEmail1">
                                                                        Yakin Menghapus
                                                                        Data
                                                                        ini?</label>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-outline-danger"
                                                                        data-bs-dismiss="modal">
                                                                        Close
                                                                    </button>&nbsp;&nbsp;
                                                                    <button type="submit" class="btn btn-outline-primary"
                                                                        name="delete">Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
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

    <!-- View Modal Wali-->
    <div class="modal fade" id="showModalWali" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modalNama" class="modal-title" id="exampleModalLabel1">
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="table-responsive text-nowrap">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <th class="table-light">Nama Wali</th>
                                        <td id="modalNamaWali"></td>
                                    </tr>
                                    <tr>
                                        <th class="table-light">Alamat Wali</th>
                                        <td id="modalAlamatWali"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal-->
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">
                        Tambah Data Murid
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="murid.php" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="file" class="form-control" id="floatingInput" name="file" />
                                    <label for="floatingInput">Profile</label>
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control" id="floatingInput"
                                        placeholder="Masukan Nama Murid" name="nama" />
                                    <label for="floatingInput">Nama
                                        Murid</label>
                                </div>
                                <div class="form-floating form-floating-custom mb-3">
                                    <input type="text" class="form-control" id="floatingInput"
                                        placeholder="Masukan Username" name="username" />
                                    <label for="floatingInput">Username</label>
                                </div>
                                <div
                                    class="form-floating form-password-toggle form-floating-custom mb-3 position-relative">
                                    <input type="password" class="form-control" id="floatingPassword"
                                        placeholder="Password" name="password" />
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

        $('a[data-bs-target^="#showModalWali"]').on('click', function () {
            var idMurid = $(this).data('id-murid');
            var nama = $(this).data('nama');
            var namaWali = $(this).data('nama-wali');
            var alamatWali = $(this).data('alamat-wali');

            var modalId = $(this).data('bs-target');
            $(modalId).find('#modalNama').text("View Data Wali " + nama);
            $(modalId).find('#modalNamaWali').text(namaWali);
            $(modalId).find('#modalAlamatWali').text(alamatWali);
        });
    </script>
</body>

</html>