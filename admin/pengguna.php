<?php
session_start();

include_once 'main-admin.php';

$nik = "";
$password = "";
$nama = "";
$penempatan = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'hapus') {
    $id = $_GET['id'];
    $sql1 = "DELETE FROM pengguna WHERE id = :id";
    $stmt1 = $conn->prepare($sql1);
    $stmt1->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt1->execute();
    if ($stmt1->rowCount() > 0) {
        $sukses = "Berhasil hapus pengguna";
    } else {
        $error = "Gagal melakukan hapus pengguna";
    }
}

if ($op == 'tambah') {
    $nik = $_POST['tambahNik'];
    $nama = $_POST['tambahNama'];
    $password = md5($_POST['tambahPassword']);
    $penempatan = $_POST['tambahPenempatan'];

    $sql_cek_nik = "SELECT * FROM pengguna WHERE nik=:nik";
    $stmt_cek_nik = $conn->prepare($sql_cek_nik);
    $stmt_cek_nik->bindParam(':nik', $nik, PDO::PARAM_STR);
    $stmt_cek_nik->execute();
    $jml_cek_nik = $stmt_cek_nik->rowCount();

    if ($jml_cek_nik > 0) {
        $error = "NIK sudah terdaftar!";
    } else {
        if ($nik && $password && $nama && $penempatan) {
            $sql1 = "INSERT INTO pengguna (nik, nama, password, penempatan_id) VALUES (:nik, :nama, :password, :penempatan)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':nik', $nik, PDO::PARAM_STR);
            $stmt1->bindParam(':nama', $nama, PDO::PARAM_STR);
            $stmt1->bindParam(':password', $password, PDO::PARAM_STR);
            $stmt1->bindParam(':penempatan', $penempatan, PDO::PARAM_INT);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                $sukses = "Berhasil menambah data pengguna: $nama";
            } else {
                $error = "Gagal menambah data pengguna";
            }
        } else {
            $error = "Silakan masukkan semua data";
        }
    }
}

if ($op == 'simpan') {
    $nik = $_POST['editNik'];
    $nama = $_POST['editNama'];
    $password = $_POST['editPassword'];
    $penempatan = $_POST['editPenempatan'];

    if ($nama && $penempatan) {
        if ($password && trim($password) !== '') {
            $password = md5($password);
            $sql1 = "UPDATE pengguna SET nama=:nama, password=:password,penempatan_id=:penempatan WHERE nik=:nik";
        } else {
            // Jika password kosong atau hanya berisi spasi
            $sql1 = "UPDATE pengguna SET nama=:nama, penempatan_id=:penempatan WHERE nik=:nik";
        }

        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(':nama', $nama, PDO::PARAM_STR);
        $stmt1->bindParam(':penempatan', $penempatan, PDO::PARAM_INT);
        $stmt1->bindParam(':nik', $nik, PDO::PARAM_STR);
        if ($password && trim($password) !== '') {
            $stmt1->bindParam(':password', $password, PDO::PARAM_STR);
        }
        $stmt1->execute();

        if ($stmt1->rowCount() > 0) {
            $sukses = "Data berhasil diupdate untuk $nama";
        } else {
            $error = "Data gagal diupdate";
        }
    } else {
        $error = "Silakan masukkan semua data";
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
    <title>Data Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <style>
        .mx-auto {
            max-width: 800px
        }

        .card {
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <!-- Edit Data Pengguna -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=simpan" method="POST">
                        <div class="mb-3 d-none">
                            <label for="editNik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="editNik" name="editNik">
                        </div>
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="editPassword" name="editPassword">
                            <div class="form-text">Biarkan kosong jika tak ingin mengubahnya.</div>
                        </div>
                        <div class="mb-3">
                            <?php
                            $sqlpenempatan = "SELECT * FROM penempatan";
                            $stmt = $conn->prepare($sqlpenempatan);
                            $stmt->execute();

                            $option_penempatan = '';
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                $option_penempatan .= '<option value="' . $row['penempatan_id'] . '">' . $row['penempatan_nama'] . '</option>';
                            }
                            ?>
                            <label for="editPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="editPenempatan" name="editPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <?php echo $option_penempatan ?>
                            </select>
                        </div>

                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Data Pengguna -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Pengguna</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=tambah" method="POST">
                        <div class="mb-3">
                            <label for="tambahNik" class="form-label">NIK</label>
                            <input type="text" class="form-control" id="tambahNik" name="tambahNik" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahNama" class="form-label">Nama</label>
                            <input type="text" class="form-control" id="tambahNama" name="tambahNama" required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="tambahPassword" name="tambahPassword"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="tambahPenempatan" class="form-label">Penempatan</label>
                            <select class="form-select" id="tambahPenempatan" name="tambahPenempatan" required>
                                <option value="">- Pilih Penempatan -</option>
                                <?php echo $option_penempatan ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto mb-5">
        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">Data Pengguna</div>
                <a data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <button type="button" class="btn btn-primary">Tambah Pengguna</button>
                </a>
            </div>

            <div class="card-body table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <!--<th scope="col">No</th>-->
                            <th scope="col">NIK</th>
                            <th scope="col">Foto</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Penempatan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2 = "SELECT pengguna.id, pengguna.nik, pengguna.nama, pengguna.foto_profil, pengguna.password, penempatan.penempatan_id, penempatan.penempatan_nama
                        FROM pengguna
                        INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id
                        ORDER BY pengguna.nik ASC";

                        $stmt = $conn->prepare($sql2);
                        $stmt->execute();
                        $urut = 1;

                        while ($r2 = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $id = $r2['id'];
                            $nik = $r2['nik'];
                            $nama = $r2['nama'];
                            if ($r2['foto_profil'] == NULL) {
                                $nama_file = "default.png";
                            } else {
                                $nama_file = $r2['foto_profil'];
                                $path_to_file = "../foto_profil/" . $nama_file;
                                if (!file_exists($path_to_file)) {
                                    $nama_file = "default.png";
                                }
                            }
                            $password = '';
                            $id_penempatan = $r2['penempatan_id'];
                            $penempatan = $r2['penempatan_nama'];

                            ?>
                            <tr>
                                <!--<th scope="row">
                                    <?php echo $urut++ ?>
                                </th>-->
                                <td scope="row">
                                    <b>
                                        <?php echo $nik ?>
                                    </b>
                                </td>
                                <td scope="row">
                                    <div style="imgBx"><img src="../foto_profil/<?php echo $nama_file; ?>" alt="" width="32"
                                            height="32" style="border-radius:50%"></div>
                                </td>
                                <td scope="row">
                                    <?php echo $nama ?>
                                </td>
                                <td scope="row">
                                    <?php echo $penempatan ?>
                                </td>
                                <td scope="row">
                                    <a id="iniEditModal" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $id ?>" data-nik="<?php echo $nik ?>"
                                        data-nama="<?php echo $nama ?>" data-password="<?php echo $password ?>" data-penempatan="<?php echo $id_penempatan ?>">
                                        <button type="button" class="btn btn-warning btn-sm">Edit</button>
                                    </a>
                                    <button type='button'
                                        onclick='return confirmDelete(`<?php echo $id ?>`,`<?php echo $nama ?>`)'
                                        class='btn btn-danger btn-sm'>Hapus</button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var tables = document.querySelectorAll('.table');
            tables.forEach(function (table) {
                new DataTable(table);
            });
        });

        function confirmDelete(id, nama) {
            Swal.fire({
                title: "Konfirmasi",
                html: "Apakah Anda yakin ingin menghapus <b>`" + nama + "`</b>?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "?op=hapus&id=" + id;
                }
            });

            return false;
        }

        var editModal = new bootstrap.Modal(document.getElementById('editModal'), {
            keyboard: false
        });

        var editButtons = document.querySelectorAll('a[id="iniEditModal"]');
        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                var nik = this.getAttribute('data-nik');
                var nama = this.getAttribute('data-nama');
                var password = this.getAttribute('data-password');
                var penempatan = this.getAttribute('data-penempatan');

                document.getElementById('editId').value = id;
                document.getElementById('editNik').value = nik;
                document.getElementById('editNama').value = nama;
                document.getElementById('editPassword').value = password;

                var editPenempatanSelect = document.getElementById('editPenempatan');
                for (var i = 0; i < editPenempatanSelect.options.length; i++) {
                    if (editPenempatanSelect.options[i].value === penempatan) {
                        editPenempatanSelect.options[i].selected = true;
                        break;
                    }
                }

                editModal.show();
            });
        });
    </script>
</body>

</html>