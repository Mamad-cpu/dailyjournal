<?php
include "koneksi.php";

$sql = "SELECT * FROM user WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();

$hasil = $stmt->get_result();



$row = $hasil->fetch_assoc();
?>

<div class="container">
    <div class="row mb-2">
        <div class="">
            <form action="" method="post" id="profileForm" enctype="multipart/form-data">
                <label for="user" class="form-label">Username</label>
                <input
                    type="text"
                    name="user"
                    id="user"
                    class="form-control my-4 py-2 w-full rounded-4"
                    placeholder="Username"
                    value="<?= $row['username'] ?>"
                    readonly
                />
                <label for="pass" class="form-label">Ganti Password</label>
                <input
                    type="password"
                    name="pass"
                    id="pass"
                    class="form-control my-4 py-2 rounded-4"
                    placeholder="Kosongkan jika tidak ingin mengubah"
                />
                <label for="foto" class="form-label">Ganti Foto Profil</label>
                <input type="file" class="form-control" name="foto" accept="image/*">
                <div class="mb-3 mt-3">
                    <label for="formGroupExampleInput3" class="form-label">Foto Saat Ini</label>
                    <?php
                    if ($row["foto"] != '') {
                        if (file_exists('img/' . $row["foto"])) { 
                            echo '<br><img src="img/' . $row["foto"] . '" class="img-fluid" alt="foto Profil" style="max-width: 200px;">';
                        } else {
                            echo '<br><p class="text-warning">File foto tidak ditemukan di ' . 'img/' . $row["foto"] . '</p>';
                        }
                    } else {
                        echo '<br><p class="text-muted">Belum ada foto profil</p>';
                    }
                    ?>
                    <input type="hidden" name="foto_lama" value="<?= $row["foto"] ?>">
                </div>
                <button type="submit" name="update" class="btn btn-primary">Update Profil</button>
            </form>
        </div>
    </div>
</div>
<?php
// Handler update profil
if (isset($_POST['update'])) {
    include "upload_photo.php";
    
    $username = $row['username'];
    $pass = $_POST['pass'];
    $foto = '';
    $nama_foto = $_FILES['foto']['name'];
    
    // Hash password dengan MD5 jika ada
    if ($pass != '') {
        $pass = md5($pass);
    }
    
    // Jika ada file foto baru
    if ($nama_foto != '') {
        $cek_upload = upload_photo($_FILES["foto"]);
        
        if ($cek_upload['status']) {
            $foto = $cek_upload['message'];
            
            // Hapus foto lama
            if (!empty($_POST['foto_lama']) && file_exists("img/" . $_POST['foto_lama'])) {
                unlink("img/" . $_POST['foto_lama']);
            }
        } else {
            echo "<script>
                alert('" . $cek_upload['message'] . "');
                document.location='admin.php?page=profile';
            </script>";
            die;
        }
    } else {
        $foto = $_POST['foto_lama'];
    }
    
    // Update database
    if ($pass != '') {
        $stmt = $conn->prepare("UPDATE user SET password = ?, foto = ? WHERE id = ?");
        $stmt->bind_param("ssi", $pass, $foto, $_SESSION['user_id']);
    } else {
        $stmt = $conn->prepare("UPDATE user SET foto = ? WHERE id = ?");
        $stmt->bind_param("si", $foto, $_SESSION['user_id']);
    }
    
    if ($stmt->execute()) {
        echo "<script>
            alert('Profil berhasil diupdate');
            document.location='admin.php?page=profile';
        </script>";
    } else {
        echo "<script>
            alert('Gagal update profil');
            document.location='admin.php?page=profile';
        </script>";
    }
    
    $stmt->close();
    $conn->close();
}
?>