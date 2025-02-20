<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['id_kelas'])) {
    $id_kelas = $_POST['id_kelas'];

    $queryMateri = "SELECT * FROM tb_materi WHERE id_kelas_assesment = ?";
    $stmt = $conn->prepare($queryMateri);
    $stmt->bind_param("i", $id_kelas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="accordion mt-4" id="accordionMateri">';
        while ($materi = $result->fetch_assoc()) {
            $id_materi = $materi['id_materi'];
            ?>
            <h2 class="accordion-header" id="headingMateri<?php echo $id_materi; ?>">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                    data-bs-target="#collapseMateri<?php echo $id_materi; ?>" aria-expanded="false"
                    aria-controls="collapseMateri<?php echo $id_materi; ?>">
                    <?php echo htmlspecialchars($materi['nama_materi'], ENT_QUOTES, 'UTF-8'); ?>
                </button>
            </h2>
            <div id="collapseMateri<?php echo $id_materi; ?>" class="accordion-collapse collapseMateri"
                aria-labelledby="headingMateri<?php echo $id_materi; ?>" data-bs-parent="#accordionMateri">
                <div class="accordion-body">
                    <div class="row">
                        <?php
                        $filePath = asset('file_materi/') . $materi['file_materi']; // Pastikan path file benar.
                        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                        if (in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif'])) {
                            echo '<img src="' . $filePath . '" alt="File Image" class="img-fluid">';
                        } elseif ($fileExtension === 'pdf') {
                            echo '
                                <iframe src="' . $filePath . '" width="100%" height="500px" frameborder="0"></iframe>
                                ';
                        } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                            echo '<p><a href="' . $filePath . '" class="btn btn-primary">Download & View Word File</a></p>';
                        } else {
                            echo '<p>File type not supported for preview. <a href="' . $filePath . '" target="_blank">Download File</a></p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="text-center py-4">
                <p class="text-muted">Belum ada File Materi</p>
              </div>';
    }

    $stmt->close();
}
?>