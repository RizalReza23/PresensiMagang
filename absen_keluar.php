<?php
session_start();
require_once('cfgall.php');

function getJarakIdeal($conn, $userid)
{
    $stmt = $conn->prepare("
        SELECT pengaturan.jarak 
        FROM pengaturan 
        INNER JOIN pengguna ON pengguna.penempatan_id = pengaturan.penempatan_id 
        WHERE pengguna.nim = ?
    ");
    
    $stmt->bindParam(1, $userid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $jarak_ideal = ($result && isset($result['jarak'])) ? $result['jarak'] : 0;
    $stmt->closeCursor();

    return $jarak_ideal;
}

// Panggil fungsi getJarakIdeal dengan userid
$jarak_ideal = getJarakIdeal($conn, $userid);

// jika variabel bernilai kosong maka redirect ke login
if (empty($obj->get_idabsen($userid))) {
    echo '
    <script>
        swal.fire({
            title: "Gagal!",
            text: "Anda harus melakukan absen masuk terlebih dahulu",
            icon: "error",
        }).then((result) => {
            setTimeout(function () {
                window.location.href = "login";
             }, 300);
        })
    </script>
    ';
} else {
    if (isset($_POST['jarak'])) {
        $jarak = $_POST['jarak'];

        // Selanjutnya cek apakah sudah absen keluar sebelumnya
        if ($obj->cek_Absenkeluar($userid)) {
            echo '
            <script> 
                swal.fire({
                    title: "Gagal!",
                    text: "Anda sudah absen keluar hari ini",
                    icon: "error",
                }).then((result) => {
                    setTimeout(function () {
                        window.location.href = "login";
                     }, 300);
                })
            </script>
            ';
        } else {
            $jarak = floatval($jarak);
            $jarak_konv = floor($jarak);

            if ($jarak_konv <= $jarak_ideal) {
                $tgl_keluar = date('Y-m-d');
                $jam_keluar = date('H:i:s');

                if ($obj->update_Absenkeluar($tgl_keluar, $jam_keluar, $obj->id_absen)) {
                    ?>
                    <script>
                        swal.fire({
                            title: "Berhasil!",
                            text: "Anda berhasil absen keluar pada pukul <?php echo date('H:i:s') ?>!",
                            icon: "success",
                        }).then((result) => {
                            setTimeout(function () {
                                window.location.href = "login";
                            }, 300);
                        })
                    </script>
                    <?php
                } else {
                    echo '
                    <script> 
                        swal.fire({
                            title: "Gagal!",
                            text: "Anda gagal absen keluar hari ini!",
                            icon: "error",
                        }).then((result) => {
                            setTimeout(function () {
                                window.location.href = "login";
                             }, 300);
                        })
                    </script>
                    ';
                }
            } else {
                echo '
                <script>
                    swal.fire({
                        title: "Gagal!",
                        html: "Anda tidak berada pada lokasi<br>Kantor<br><br>Jarak Anda: ' . $jarak . ' km",
                        icon: "error",
                    }).then((result) => {
                        setTimeout(function () {
                            window.location.href = "login";
                        }, 300);
                    });
                </script>';
            }
        }
    } else {
        echo '
        <script>
            swal.fire({
                title: "Gagal!",
                text: "Jarak tidak terdeteksi!",
                icon: "error",
            }).then((result) => {
                setTimeout(function () {
                    window.location.href = "login";
                 }, 300);
            })
        </script>
        ';
    }
}
?>
