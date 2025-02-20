<?php
session_start();
include '../../config/koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_jawaban = $_POST['id_jawaban'];

    $query = "SELECT file_jawaban FROM tb_jawaban WHERE id_jawaban = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_jawaban);
    $stmt->execute();
    $stmt->bind_result($file_jawaban);
    $stmt->fetch();
    $stmt->close();

    if ($file_jawaban && file_exists($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/file_jawaban/' . $file_jawaban)) {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/imam/assets/file_jawaban/' . $file_jawaban);
    }

    $deleteQuery = "DELETE FROM tb_jawaban WHERE id_jawaban = ?";
    $deleteStmt = $conn->prepare($deleteQuery);
    $deleteStmt->bind_param("i", $id_jawaban);
    $deleteStmt->execute();
    $deleteStmt->close();

    echo 'success';
}
?>