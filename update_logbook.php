<?php
session_start();
require_once('cfgall.php');

// Validasi input
if (isset($_POST['userid']) && isset($_POST['logbook'])) {
    $userid = $_POST['userid'];
    $logbook_input = $_POST['logbook'];

    // Cek apakah pengguna sudah absen masuk tapi belum absen keluar
    $sql = "SELECT * FROM absen WHERE nim = :userid AND jam_masuk IS NOT NULL AND jam_keluar IS NULL";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':userid', $userid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

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
        if ($result) {
            // Cek apakah logbook sudah diisi
            if (!empty($result['logbook'])) {
                echo '
                <script> 
                    swal.fire({
                        title: "Gagal!",
                        text: "Anda sudah mengisi logbook hari ini",
                        icon: "error",
                    }).then((result) => {
                        setTimeout(function () {
                            window.location.href = "login";
                        }, 300);
                    })
                </script>
                ';
            } else {
                // Logbook belum diisi, bisa diisi sekarang
                $sql = "UPDATE absen SET logbook = :logbook WHERE nim = :userid AND jam_keluar IS NULL";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':logbook', $logbook_input);
                $stmt->bindParam(':userid', $userid);
        
                if ($stmt->execute()) {
                    echo '
                    <script> 
                        swal.fire({
                            title: "Berhasil!",
                            text: "Anda sudah isi logbook hari ini",
                            icon: "success",
                        }).then((result) => {
                            setTimeout(function () {
                                window.location.href = "login";
                            }, 300);
                        })
                    </script>
                    ';
                } else {
                    echo '
                    <script> 
                        swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan saat menyimpan logbook",
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
        } else {
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
        }
    }
}
?>
