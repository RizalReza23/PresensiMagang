<?php
session_start();
require_once('cfgall.php');

function getLogbook($conn, $userid)
{
    $stmt = $conn->prepare("
        SELECT absen.logbook 
        FROM absen  
        WHERE absen.nik = ? AND logbook IS NOT NULL
    ");
    
    $stmt->bindParam(1, $userid);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $logbook = ($result && isset($result['logbook'])) ? $result['logbook'] : null;
    $stmt->closeCursor();

    return $logbook;
}

$logbook = getLogbook($conn, $userid);

// Memastikan bahwa user telah melakukan absen masuk terlebih dahulu
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
    // Memeriksa apakah logbook telah diisi
    if (isset($_POST['logbook'])) {
        $logbook_input = $_POST['logbook'];

        if ($obj->cek_Logbook($userid)) {
            echo '
            <script> 
                swal.fire({
                    title: "Gagal!",
                    text: "Anda sudah mengisi logbook hari ini.",
                    icon: "error",
                }).then((result) => {
                    setTimeout(function () {
                        window.location.href = "login";
                     }, 300);
                })
            </script>
            ';
        } else {
            // Hanya mengisi logbook tanpa absen keluar
            if ($obj->update_Logbook($userid, $logbook_input)) {
                ?>
                <script>
                    swal.fire({
                        title: "Berhasil!",
                        text: "Anda berhasil mengisi logbook hari ini!",
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
                        text: "Gagal mengisi logbook hari ini.",
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
                text: "Logbook tidak terdeteksi!",
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