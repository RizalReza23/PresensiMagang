<?php
session_start();

include_once 'main-admin.php';

$penempatan_id = "";
$penempatan_nama = "";
$sukses = "";
$error = "";

if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = "";
}

if ($op == 'hapus') {
    $penempatan_id = $_GET['id'];
    $sql1 = "DELETE FROM penempatan WHERE penempatan_id = :penempatan_id";
    $stmt = $conn->prepare($sql1);
    $stmt->bindParam(':penempatan_id', $penempatan_id);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        $sukses = "Berhasil hapus data";
    } else {
        $error = "Gagal melakukan delete data";
    }
}

if ($op == 'tambah') {
    $penempatan_nama = $_POST['tambahNama'];

    $sql_cek_nama = "SELECT * FROM penempatan WHERE penempatan_nama = :penempatan_nama";
    $stmt_cek_nama = $conn->prepare($sql_cek_nama);
    $stmt_cek_nama->bindParam(':penempatan_nama', $penempatan_nama);
    $stmt_cek_nama->execute();
    $jml_cek_nama = $stmt_cek_nama->rowCount();

    if ($jml_cek_nama > 0) {
        $error = "Nama penempatan sudah ada!";
    } else {
        if ($penempatan_nama) {
            $sql1 = "INSERT INTO penempatan (penempatan_nama) VALUES (:penempatan_nama)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':penempatan_nama', $penempatan_nama);
            $stmt1->execute();
            if ($stmt1->rowCount() > 0) {
                $sukses = "Berhasil menambah data penempatan: $penempatan_nama";
            } else {
                $error = "Gagal menambah data penempatan";
            }
        } else {
            $error = "Silakan masukkan semua data";
        }
    }
}

if ($op == 'simpan') {
    $penempatan_id = $_POST['editId'];
    $penempatan_nama = $_POST['editNama'];

    if ($penempatan_id && $penempatan_nama) {
        $sql1 = "UPDATE penempatan SET penempatan_nama = :penempatan_nama WHERE penempatan_id = :penempatan_id";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bindParam(':penempatan_nama', $penempatan_nama);
        $stmt1->bindParam(':penempatan_id', $penempatan_id);
        $stmt1->execute();
        if ($stmt1->rowCount() > 0) {
            $sukses = "Data berhasil diupdate";
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
    <title>Data Penempatan</title>
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
    <!-- Edit Data penempatan -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Data Penempatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=simpan" method="POST">
                        <div class="mb-3">
                            <label for="editNama" class="form-label">Nama Penempatan</label>
                            <input type="text" class="form-control" id="editNama" name="editNama" required>
                        </div>

                        <input type="hidden" id="editId" name="editId">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Tambah Data penempatan -->
    <div class="modal fade" id="tambahModal" tabindex="-1" aria-labelledby="tambahModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahModalLabel">Tambah Data Penempatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="?op=tambah" method="POST">
                        <div class="mb-3">
                            <label for="tambahNama" class="form-label">Nama Penempatan</label>
                            <input type="text" class="form-control" id="tambahNama" name="tambahNama" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mx-auto">

        <!-- untuk mengeluarkan data -->
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="d-flex align-items-center">Data Penempatan</div>
                <a data-bs-toggle="modal" data-bs-target="#tambahModal">
                    <button type="button" class="btn btn-primary">Tambah Penempatan</button>
                </a>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No Penempatan</th>
                            <th scope="col">Nama Penempatan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM penempatan";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $urut = 1;

                        foreach ($result as $row) {
                            $penempatan_id = $row['penempatan_id'];
                            $penempatan_nama = $row['penempatan_nama'];
                            ?>
                            <tr>
                                <td scope="row">
                                    <?php echo $urut++ ?>
                                </td>
                                <td scope="row">
                                    <?php echo $penempatan_nama ?>
                                </td>
                                <td scope="row">
                                    <a id="iniEditModal" data-bs-toggle="modal" data-bs-target="#editModal"
                                        data-id="<?php echo $penempatan_id ?>" data-penempatan="<?php echo $penempatan_nama ?>">
                                        <button type="button" class="btn btn-warning">Edit</button>
                                    </a>
                                    <button type='button' onclick='return confirmDelete(`<?php echo $penempatan_id ?>`)'
                                        class='btn btn-danger'>Hapus</button>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var tables = document.querySelectorAll('.table');
                        tables.forEach(function (table) {
                            new DataTable(table);
                        });
                    });

                    function confirmDelete(penempatan_id) {
                        Swal.fire({
                            title: "Konfirmasi",
                            html: "Apakah Anda yakin ingin menghapus?",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ya, Hapus",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "?op=hapus&id=" + penempatan_id;
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
                            var penempatan = this.getAttribute('data-penempatan');

                            document.getElementById('editId').value = id;
                            document.getElementById('editNama').value = penempatan;

                            editModal.show();
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</body>

</html>