<?php
session_start();

include_once 'main-admin.php';

$id = "";
$jarak = "";
$sukses = "";
$error = "";

// Ambil data penempatan untuk dropdown
$penempatan_sql = "SELECT penempatan_id, penempatan_nama FROM penempatan";
$penempatan_stmt = $conn->query($penempatan_sql);
$penempatan_list = $penempatan_stmt->fetchAll(PDO::FETCH_ASSOC);

// Ambil pengaturan berdasarkan penempatan_id
$selected_penempatan_id = isset($_POST['penempatan_id']) ? $_POST['penempatan_id'] : 1;
$sql = "SELECT * FROM pengaturan WHERE penempatan_id = :penempatan_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':penempatan_id', $selected_penempatan_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

$batas_telat = $result['batas_telat'] ?? '';
$jarak = $result['jarak'] ?? '';
$fitur_foto = $result['fitur_foto'] ?? '';
$isFitur_foto = $fitur_foto == 1 ? 'checked' : '';

// Simpan pengaturan
if (isset($_POST['simpan'])) {
    $batas_telat = $_POST['batas_telat'];
    $jarak = $_POST['jarak'];
    $fitur_foto = isset($_POST['fitur_foto']) ? 1 : 0;
    $penempatan_id = $_POST['penempatan_id'];

    if ($batas_telat && $jarak) {
        // Periksa apakah pengaturan untuk penempatan_id sudah ada
        $check_sql = "SELECT COUNT(*) FROM pengaturan WHERE penempatan_id = :penempatan_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':penempatan_id', $penempatan_id);
        $check_stmt->execute();
        $exists = $check_stmt->fetchColumn() > 0;

        if ($exists) {
            // Update pengaturan yang sudah ada
            $sql = "UPDATE pengaturan SET batas_telat = :batas_telat, jarak = :jarak, fitur_foto = :fitur_foto WHERE penempatan_id = :penempatan_id";
        } else {
            // Insert pengaturan baru
            $sql = "INSERT INTO pengaturan (penempatan_id, batas_telat, jarak, fitur_foto) VALUES (:penempatan_id, :batas_telat, :jarak, :fitur_foto)";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':batas_telat', $batas_telat);
        $stmt->bindParam(':jarak', $jarak);
        $stmt->bindParam(':fitur_foto', $fitur_foto);
        $stmt->bindParam(':penempatan_id', $penempatan_id);
        $stmt->execute();

        $sukses = "Data berhasil diupdate";
    } else {
        $error = "Silakan masukkan semua data";
    }
}

// Ganti password
if (isset($_POST['submit'])) {
    $id_admin = 1;
    $oldpassword = md5($_POST['oldpassword']);
    $newpassword = md5($_POST['newpassword']);
    $confirmpassword = md5($_POST['confirmpassword']);

    $query = "SELECT password FROM admin WHERE id_admin=?";
    $stmt = $conn->prepare($query);
    $stmt->execute([$id_admin]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $row = $result[0];
        $oldpassword_db = $row['password'];

        if ($oldpassword == $oldpassword_db) {
            if ($newpassword == $confirmpassword) {
                $query = "UPDATE admin SET password=? WHERE id_admin=?";
                $stmt = $conn->prepare($query);
                $stmt->execute([$newpassword, $id_admin]);
                $script = "<script>
                Swal.fire(
                    'Berhasil!',
                    'Password berhasil diubah.',
                    'success'
                );</script>";
                echo $script;
            } else {
                $script = "<script>
                Swal.fire(
                    'Gagal!',
                    'Password baru tidak cocok dengan konfirmasi password.',
                    'error'
                );</script>";
                echo $script;
            }
        } else {
            $script = "<script>
            Swal.fire(
                'Gagal!',
                'Password lama salah.',
                'error'
            );</script>";
            echo $script;
        }
    } else {
        $script = "<script>
            Swal.fire(
                'Gagal!',
                'error'
            );</script>";
        echo $script;
    }
}

if ($error) {
    ?>
    <script>
        Swal.fire({
            title: "<?php echo $error ?>",
            icon: "error",
        })
    </script>
    <?php
}
if ($sukses) {
    ?>
    <script>
        Swal.fire({
            title: "<?php echo $sukses ?>",
            icon: "success",
        })
    </script>
    <?php
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="mx-auto">
        <!-- Dropdown untuk memilih pengaturan penempatan -->
        <div class="card mb-3">
            <div class="card-header">
                Pilih  Pengaturan Penempatan
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="mb-3">
                        <label for="penempatan_id" class="form-label">Penempatan</label>
                        <select id="penempatan_id" name="penempatan_id" class="form-select" onchange="this.form.submit()">
                            <?php foreach ($penempatan_list as $penempatan) { ?>
                                <option value="<?php echo $penempatan['penempatan_id']; ?>" <?php echo $penempatan['penempatan_id'] == $selected_penempatan_id ? 'selected' : ''; ?>>
                                    <?php echo $penempatan['penempatan_nama']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <form action="" method="POST">
                    <input type="hidden" name="penempatan_id" value="<?php echo $selected_penempatan_id; ?>">
                    <div class="mb-3 row">
                        <label for="batas_telat" class="col-sm-2 col-form-label">Telat Maks. (menit)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="batas_telat" name="batas_telat"
                                value="<?php echo $batas_telat ?>">
                            <div class="form-text">Maksimal keterlambatan pengguna dalam melakukan absen masuk (menit).</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="jarak" class="col-sm-2 col-form-label">Jarak Maks. (meter)</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="jarak" name="jarak" step="0.01" value="<?php echo htmlspecialchars($jarak, ENT_QUOTES, 'UTF-8'); ?>">
                            <div class="form-text">Jarak maksimal pengguna dari kantor (meter).</div>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2 col-form-label">Fitur Absen</label>
                        <div class="col-sm-10 d-flex align-items-center">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="switch_foto" name="fitur_foto" <?php echo $isFitur_foto; ?>>
                                <label class="form-check-label" for="switch_foto">Kamera Selfie Absensi</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="simpan" value="Simpan" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

        <!-- Formulir ganti password -->
        <div class="card" style="margin-bottom:50px">
            <div class="card-header" style="background:none">
                Form Ganti Password - <i>Abaikan jika tak perlu</i>
            </div>
            <div class="card-body">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Password Lama</label>
                        <input class="form-control" type="password" name="oldpassword" required>
                    </div>
                    <div>
                        <label class="form-label">Password Baru</label>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" type="password" name="newpassword" id="password-input" required>
                        <span class="input-group-text" onclick="togglePb()"><i id="eye-icon" class="bi bi-eye-slash"></i></span>
                    </div>
                    <div>
                        <label class="form-label">Konfirmasi Password</label>
                    </div>
                    <div class="input-group mb-3">
                        <input class="form-control" type="password" name="confirmpassword" id="confirm-password-input" required>
                        <span class="input-group-text" onclick="toggleCp()"><i id="eye-icon2" class="bi bi-eye-slash"></i></span>
                    </div>
                    <div class="col-12">
                        <input type="submit" name="submit" value="Simpan" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        function togglePb() {
            var passwordInput = document.getElementById("password-input");
            var eyeIcon = document.getElementById("eye-icon");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordInput.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }
        function toggleCp() {
            var conpasswordInput = document.getElementById("confirm-password-input");
            var eyeIcon = document.getElementById("eye-icon2");
            if (conpasswordInput.type === "password") {
                conpasswordInput.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                conpasswordInput.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        }
    </script>
</body>
</html>
