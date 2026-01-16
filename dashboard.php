<?php
//query untuk mengambil data article
$sql1 = "SELECT * FROM articles ORDER BY tanggal DESC";
$hasil1 = $conn->query($sql1);
$sql2 = "SELECT * FROM gallery ORDER BY tanggal DESC";
$hasil2 = $conn->query($sql2);
$sql = "SELECT * FROM user WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$hasil = $stmt->get_result();



$row = $hasil->fetch_assoc();
//menghitung jumlah baris data article
$jumlah_article = $hasil1->num_rows; 
$jumlah_gallery = $hasil2->num_rows;

?>
<div class="justify-content-center align-items-center flex container">
    <p class="text-center fs-4 fw-lighter">Selamat Datang,</p>
    <p class="text-center fs-3 fw-bold text-danger"><?php echo $_SESSION['username']; ?></p>
    <?php
                    if ($row["foto"] != '') {
                        if (file_exists('img/' . $row["foto"])) { 
                            echo '<div class="text-center"><img src="img/' . $row["foto"] . '" class="rounded-circle" alt="foto Profil" style="max-width: 200px; width: 200px; height: 200px; object-fit: cover;"></div>';
                        } else {
                            echo '<br><p class="text-warning">File foto tidak ditemukan di ' . 'img/' . $row["foto"] . '</p>';
                        }
                    } else {
                        echo '<br><p class="text-muted">Belum ada foto profil</p>';
                    }
                    ?>
    <div class="row row-cols-1 row-cols-md-4 g-4 justify-content-center pt-4 flex ro">
        <div class="col">
            <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="p-3">
                            <h5 class="card-title"><i class="bi bi-newspaper"></i> Article</h5> 
                        </div>
                        <div class="p-3">
                            <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_article; ?></span>
                        </div> 
                    </div>
                </div>
            </div>
        </div> 
        <div class="col">
            <div class="card border border-danger mb-3 shadow" style="max-width: 18rem;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="p-3">
                            <h5 class="card-title"><i class="bi bi-camera"></i> Gallery</h5> 
                        </div>
                        <div class="p-3">
                            <span class="badge rounded-pill text-bg-danger fs-2"><?php echo $jumlah_gallery; ?></span>
                        </div> 
                    </div>
                </div>
            </div>
        </div> 
    </div>
</div>
