<?php
session_start();

include_once 'main-admin.php';

$nim = "";
$password = "";
$nama = "";
$guru = "";
$sukses = "";
$error = "";

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapitulasi</title>
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
    <div class="mx-auto">

        <!-- untuk mengeluarkan data -->
        <div class="card mb-3">
            <div class="card-header">
                Rekapitulasi
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">NIP</th>
                            <th scope="col">Nama</th>
                            <th scope="col">Penempatan</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT pengguna.id, pengguna.nim, pengguna.nama, penempatan.penempatan_nama
                        FROM pengguna
                        INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id
                        ORDER BY pengguna.id DESC";
                        $stmt = $conn->query($sql);
                        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        $urut = 1;
                        foreach ($results as $row) {
                            $id = $row['id'];
                            $nim = $row['nim'];
                            $nama = $row['nama'];
                            $penempatan = $row['penempatan_nama'];

                            ?>
                            <tr>
                                <th scope="row">
                                    <?php echo $urut++ ?>
                                </th>
                                <td scope="row">
                                    <?php echo $nim ?>
                                </td>
                                <td scope="row">
                                    <?php echo $nama ?>
                                </td>
                                <td scope="row">
                                    <?php echo $penempatan ?>
                                </td>
                                <td scope="row">
                                    <a href="hasil_rekap?nim=<?php echo $nim ?>"><button type="button"
                                            class="btn btn-warning">Lihat</button></a>
                                    <a class="btn btn-success" href="ekspor_rekap?nim=<?php echo $nim; ?>">Ekspor</a>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>

                </table>
            </div>
        </div>
        <script>
            $(document).ready(function () {
                $('.table').DataTable();
            });
        </script>
</body>

</html>