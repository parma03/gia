<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['id_kelas'], $_POST['id_murid'])) {
    $id_kelas = $_POST['id_kelas'];
    $id_murid = $_POST['id_murid'];

    $querypresensi = "SELECT * FROM tb_presensi WHERE id_kelas_assesment = ? AND id_murid = ?";
    $stmt = $conn->prepare($querypresensi);
    $stmt->bind_param("ii", $id_kelas, $id_murid);
    $stmt->execute();
    $result = $stmt->get_result();

    $hadir = [];
    $absen = [];

    if ($result->num_rows > 0) {
        while ($presensi = $result->fetch_assoc()) {
            if ($presensi['status'] == 'Hadir') {
                $hadir[] = $presensi;
            } else {
                $absen[] = $presensi;
            }
        }
    } else {
        echo '<div class="text-center py-4">
                <p class="text-muted">Belum ada Presensi</p>
              </div>';
    }

    $stmt->close();
}
?>
<center>
    <p>Jumlah Kehadiran: <?php echo count($hadir); ?> | Jumlah Ketidakhadiran: <?php echo count($absen); ?></p>
</center>
<div class="container">
    <div class="row">
        <div class="col-md-6">
            <?php if (!empty($hadir)): ?>
                <?php foreach ($hadir as $index => $data): ?>
                    <div class="mt-4">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-success">
                                <div class="d-flex justify-content-between w-100">
                                    <h5 class="mb-1"><?php echo $data['status']; ?></h5>
                                    <small><?php echo $data['tanggal']; ?></small>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">Tidak Ada Record</p>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-md-6">
            <?php if (!empty($absen)): ?>
                <?php foreach ($absen as $index => $data): ?>
                    <div class="mt-4">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-danger">
                                <div class="d-flex justify-content-between w-100">
                                    <h5 class="mb-1"><?php echo $data['status']; ?></h5>
                                    <small><?php echo $data['tanggal']; ?></small>
                                </div>
                            </li>
                        </ul>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <p class="text-muted">Tidak Ada Record</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>