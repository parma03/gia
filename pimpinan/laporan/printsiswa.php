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

$laporan = $_SESSION['laporan'];
$tittle = $_SESSION['tittle'];
$total_surat_masuk = count($laporan);

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Laporan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .signature-left {
            float: left;
            width: 50%;
            text-align: center;
        }

        .signature-right {
            float: right;
            width: 50%;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container-xxl">
        <div style="display: flex; align-items: center;">
            <img src="<?php echo asset('img/logo.png'); ?>" width="100px" style="margin-right: 20px;" />
            <div style="text-align: center; width: 100%;">
                <h2>Laporan Data Siswa</h2>
                <h3>Global Intelligence Academy</h3>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama Siswa</th>
                        <th>Username</th>
                        <th>Nama Wali</th>
                        <th>Alamat Wali</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $total_pendapatan = 0; // Variabel untuk menghitung total pendapatan
                    
                    foreach ($laporan as $row) {
                        echo "<tr>";
                        echo "<td>{$no}</td>";
                        echo "<td>{$row['nama']}</td>";
                        echo "<td>{$row['username']}</td>";
                        echo "<td>" .($row['nama_wali'] ?? 'Belum Mengisi Data') . "</td>";
                        echo "<td>" .($row['alamat_wali'] ?? 'Belum Mengisi Data') . "</td>";
                        echo "</tr>";

                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <div class="signature-left">
            <p>
                <br>
                Pengelola Bimbel
            </p>
            <br><br>
            <p><u>Oknira Jalfi, S.Pd</u></p>
        </div>
        <div class="signature-right">
            <p>
                Padang, <?php echo date('d-m-Y'); ?><br>
                Admin
            </p>
            <br><br>
            <p><u>Imam Misman Turmudhi</u></p>
        </div>
    </div>
    <script>
        window.print();
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>
</body>

</html>