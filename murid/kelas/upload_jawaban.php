<?php
session_start();
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_soal = $_POST['id_soal'];
    $id_murid = $_POST['id_murid'];
    $file_name = $_FILES['file']['name'];
    $file_temp = $_FILES['file']['tmp_name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $random_name = uniqid() . '.' . $file_ext;
    $file_path = BASE_PATH . 'assets/file_jawaban/' . $random_name;
    $nama_jawaban = $file_name;

    if (move_uploaded_file($file_temp, $file_path)) {
        $query = "INSERT INTO tb_jawaban (id_soal, id_murid, nama_file, file_jawaban) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("iiss", $id_soal, $id_murid, $nama_jawaban, $random_name);
        $stmt->execute();
        $stmt->close();
        echo 'success';
    } else {
        echo 'error';
    }
}
?>