<?php
session_start();
include '../../config/koneksi.php';

if (isset($_POST['id_kelas'], $_POST['id_murid'])) {
    $id_kelas = $_POST['id_kelas'];
    $id_murid = $_POST['id_murid'];

    $querysoal = "SELECT * FROM tb_soal WHERE id_kelas_assesment = ?";
    $stmt = $conn->prepare($querysoal);
    $stmt->bind_param("i", $id_kelas);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo '<div class="accordion mt-4" id="accordionsoal">';
        while ($soal = $result->fetch_assoc()) {
            $id_soal = $soal['id_soal'];
            ?>
            <h2 class="accordion-header" id="headingsoal<?php echo $id_soal; ?>">
                <button type="button" class="accordion-button collapsed" data-bs-toggle="collapse"
                    data-bs-target="#collapsesoal<?php echo $id_soal; ?>" aria-expanded="false"
                    aria-controls="collapsesoal<?php echo $id_soal; ?>">
                    <?php echo htmlspecialchars($soal['nama_soal'], ENT_QUOTES, 'UTF-8'); ?>
                </button>
            </h2>
            <div id="collapsesoal<?php echo $id_soal; ?>" class="accordion-collapse collapsesoal"
                aria-labelledby="headingsoal<?php echo $id_soal; ?>" data-bs-parent="#accordionsoal">
                <div class="accordion-body">
                    <div class="row">
                        <div class="container">
                            <h5>Soal</h5>
                            <?php
                            $filePath = asset('file_soal/') . $soal['file_soal'];
                            $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                            if (in_array($fileExtension, ['png', 'jpg', 'jpeg', 'gif'])) {
                                echo '<img src="' . $filePath . '" alt="File Image" class="img-fluid">';
                            } elseif ($fileExtension === 'pdf') {
                                echo '<iframe src="' . $filePath . '" width="100%" height="500px" frameborder="0"></iframe>';
                            } elseif (in_array($fileExtension, ['doc', 'docx'])) {
                                echo '<p><a href="' . $filePath . '" class="btn btn-primary">Download & View Word File</a></p>';
                            } else {
                                echo '<p>File type not supported for preview. <a href="' . $filePath . '">Download File</a></p>';
                            }
                            ?>

                            <!-- Menampilkan jawaban -->
                            <div class="mt-3">
                                <h5>Jawaban</h5>
                                <?php
                                $queryJawaban = "SELECT * FROM tb_jawaban WHERE id_soal = ? AND id_murid = ?";
                                $stmtJawaban = $conn->prepare($queryJawaban);
                                $stmtJawaban->bind_param("ii", $id_soal, $id_murid);
                                $stmtJawaban->execute();
                                $jawabanResult = $stmtJawaban->get_result();

                                if ($jawabanResult->num_rows > 0) {
                                    while ($jawaban = $jawabanResult->fetch_assoc()) {
                                        echo '<div class="d-flex align-items-center">';
                                        echo '<p>' . $jawaban['nama_file'] . '</p> &nbsp;&nbsp;&nbsp;';
                                        echo '<button class="btn btn-danger" onclick="deleteJawaban(' . $jawaban['id_jawaban'] . ')">Hapus</button>';
                                        echo '</div>';
                                    }
                                } else {
                                    echo '<p class="text-muted">Tidak ada jawaban</p>';
                                }
                                $stmtJawaban->close();
                                ?>
                            </div>
                            <br>
                            <?php
                            if ($jawabanResult->num_rows === 0) {
                                echo '<form id="uploadJawabanForm_' . $id_soal . '" method="POST" enctype="multipart/form-data">';
                                echo '<input type="file" name="file" class="form-control mb-2" required>';
                                echo '<button type="submit" class="btn btn-success">Upload Jawaban</button>';
                                echo '<input type="hidden" name="id_soal" value="' . $id_soal . '">';
                                echo '<input type="hidden" name="id_murid" value="' . $id_murid . '">';
                                echo '</form>';
                            } else {
                                echo '<p class="text-muted">Sudah Mengirim jawaban</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                $(document).ready(function () {
                    $(document).off('submit', 'form[id^="uploadJawabanForm"]').on('submit', 'form[id^="uploadJawabanForm"]', function (e) {
                        e.preventDefault();
                        
                        let form = $(this);
                        let formData = new FormData(this);

                        $.ajax({
                            url: 'upload_jawaban.php',
                            type: 'POST',
                            data: formData,
                            contentType: false,
                            processData: false,
                            beforeSend: function () {
                                form.find('button[type="submit"]').prop('disabled', true).text('Uploading...');
                            },
                            success: function (response) {
                                alert('Jawaban berhasil diunggah!');
                                setTimeout(function () {
                                    location.reload();
                                }, 500); // Tunggu 500ms sebelum reload
                            },
                            error: function () {
                                alert('Terjadi kesalahan saat mengunggah jawaban.');
                            },
                            complete: function () {
                                form.find('button[type="submit"]').prop('disabled', false).text('Upload Jawaban');
                            }
                        });
                    });

                    function deleteJawaban(idJawaban) {
                        if (confirm('Apakah Anda yakin ingin menghapus jawaban ini?')) {
                            $.ajax({
                                url: 'delete_jawaban.php',
                                type: 'POST',
                                data: { id_jawaban: idJawaban },
                                success: function () {
                                    alert('Jawaban berhasil dihapus!');
                                    setTimeout(function () {
                                        location.reload();
                                    }, 500);
                                },
                                error: function () {
                                    alert('Terjadi kesalahan saat menghapus jawaban.');
                                }
                            });
                        }
                    }
                });
            </script>

            <?php
        }
        echo '</div>';
    } else {
        echo '<div class="text-center py-4">
                <p class="text-muted">Belum ada File soal</p>
              </div>';
    }

    $stmt->close();
}
?>