<?php
// Aini
include_once 'cfgdb.php';

session_start();

if (isset($_SESSION['nim'])) {
  header("Location: beranda");
  exit();
}

$nim = "";
$password = "";
$nama = "";
$universitas = "";
$penempatan = "";
$sukses = "";
$error = "";
$error_message = '';

$ambil = "SELECT pengguna.id, pengguna.nim, pengguna.nama, pengguna.universitas, penempatan.penempatan_nama
          FROM pengguna
          INNER JOIN penempatan ON pengguna.penempatan_id = penempatan.penempatan_id
          ORDER BY pengguna.id DESC";
$stmt = $conn->prepare($ambil);
$stmt->execute();
$hasil = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($hasil as $r2) {
    $id = $r2['id'];
    $nim = $r2['nim'];
    $nama = $r2['nama'];
    $universitas = $r2['universitas'];
    $penempatan = $r2['penempatan_nama'];
}

if (isset($_POST['daftar'])) {
    $nim = $_POST['nim'];
    $password = md5($_POST['password']);
    $nama = $_POST['nama'];
    $universitas = $_POST['universitas'];
    $penempatan = $_POST['penempatan_id'];

    // Cek apakah NIP sudah terdaftar dalam database
    $sql_cek_nim = "SELECT * FROM pengguna WHERE nim=?";
    $stmt_cek_nim = $conn->prepare($sql_cek_nim);
    $stmt_cek_nim->execute([$nim]);
    $jml_cek_nim = $stmt_cek_nim->rowCount();

    if ($jml_cek_nim > 0) {
        $error = "Mohon maaf, NIM sudah terdaftar!";
    } else {
        if ($nim && $password && $nama && $universitas && $penempatan) {
            $sql1 = "INSERT INTO pengguna (nim, nama, password, universitas, penempatan_id) VALUES (?, ?, ?, ?, ?)";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->execute([$nim, $nama, $password, $universitas, $penempatan]);
            $sukses = "Berhasil daftar akun!";
        } else {
            $error = "Silakan masukkan semua data!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta content="width=device-width, initial-scale=1.0" name="viewport" />
  <meta content="text/html; charset=UTF-8" http-equiv="Content-Type" />
  <meta content="IE=edge,chrome=1" http-equiv="X-UA-Compatible" />

  <title>Absensi Magang Puskesmas Tlogosari Kulon</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">
</head>

<body>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background-color: #000;
    }

    .bg-image {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('./img/pendaftaran.png');
      background-size: cover;
      background-position: center;
      background-repeat: no-repeat;
      z-index: -1;
      animation-name: zoom;
      animation-duration: 1s;
      animation-timing-function: ease-in-out;
      animation-fill-mode: forwards;
      opacity: 0;
    }

    @keyframes zoom {
      from {
        transform: scale(1);
      }

      to {
        transform: scale(1.2);
        opacity: 0.5;
      }
    }

    @keyframes tara {
      from {
        margin-top: 0;
        opacity: 0;
      }

      to {
        margin-top: 70px;
        margin-bottom: 70px;
        opacity: 1;
      }
    }

    a {
      text-decoration: none;
    }

    p {
      margin-top: 15px
    }

    .background {
      height: 520px;
      position: absolute;
      transform: translate(-50%, -50%);
      top: 50%;
      left: 50%
    }

    form h3,
    label {
      font-weight: 500
    }

    input,
    label {
      display: block
    }

    *,
    :after,
    :before {
      padding: 0;
      margin: 0;
      box-sizing: border-box
    }

    .social,
    label {
      margin-top: 30px
    }

    /*body{background: rgb(81,172,255);background: linear-gradient(90deg, rgba(81,172,255,1) 0%, rgba(156,208,255,1) 50%, rgba(250,255,255,1) 100%);}*/
    .background {
      max-width: 330px
    }

    .background .shape {
      height: 200px;
      width: 200px;
      position: absolute;
      border-radius: 50%
    }

    .shape:first-child {
      background: linear-gradient(#1845ad, #23a2f6);
      left: -20px;
      top: -50px
    }

    .shape:last-child {
      background: linear-gradient(#1845ad, #23a2f6);
      right: -20px;
      bottom: -50px
    }

    form {
      margin-top: 5vh;
      background-color: rgba(0, 0, 0, 0.8);
      max-width: 300px;
      margin-left: auto;
      margin-right: auto;
      border-radius: 10px;
      /*box-shadow:0 0 40px rgba(8,7,16,.6);*/
      padding: 50px 35px;
      transition: all 0.3s ease;
    }

    form * {
      font-family: Poppins, sans-serif;
      /*color: #fff;*/
      letter-spacing: .5px;
      outline: 0;
      border: none
    }

    h3,
    p,
    form>label,
    form>input {
      color: #fff !important;
    }

    .form-select {
      color: #fff;
      background-color: rgb(255 255 255 / 8%);
      border: 0px solid #fff;
      font-size: 14px;
    }

    .form-select option {
      color: #000;
    }

    .social div,
    input {
      border-radius: 3px
    }

    form h3 {
      font-size: 32px;
      line-height: 42px;
    }

    form h3,
    form p {
      text-align: center
    }

    @media only screen and (min-width: 500px) {
      form {
        max-width: 350px;
        margin-top: 0;
        animation-name: tara;
        animation-duration: .7s;
        animation-timing-function: ease-in-out;
        animation-fill-mode: forwards;
      }
    }

    label {
      font-size: 16px
    }

    input {
      height: 50px;
      width: 100%;
      background-color: rgb(255 255 255 / 8%);
      padding: 0 10px;
      margin-top: 8px;
      font-size: 14px;
      font-weight: 300
    }

    ::placeholder {
      color: #e5e5e5
    }

    button {
      margin-top: 30px;
      margin-bottom: 25px;
      width: 100%;
      background-color: #243763;
      color: #fff;
      padding: 15px 0;
      font-size: 18px;
      font-weight: 600;
      border-radius: 5px;
      cursor: pointer
    }

    .social {
      display: flex
    }

    .social div {
      background: rgba(255, 255, 255, .27);
      width: 150px;
      padding: 5px 10px 10px 5px;
      color: #eaf0fb;
      text-align: center
    }

    .social div:hover {
      background-color: rgba(255, 255, 255, .47)
    }

    .social .fb {
      margin-left: 25px
    }

    .social i {
      margin-right: 4px
    }

    .back-button {
      display: inline-flex;
      justify-content: center;
      align-items: center;
      position: absolute;
      width: 40px;
      height: 40px;
      padding: 10px;
      border-radius: 50%;
      background-color: #ffffff17;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
      transition: all 0.3s ease-in-out;
    }

    .back-button:hover {
      opacity: .7;
    }

    .line {
      stroke-width: 2;
      stroke: #fff;
      fill: none;
    }

    .error {
        color: red;
        font-size: 12px;
        margin-top: 5px;
    }

    button:disabled {
        background-color: grey;
        cursor: not-allowed;
    }
  </style>
  <!--<div class="background">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    -->
  <div class="bg-image"></div>
  <form method="POST" action="" id="form-login">
    <a href="./" class="back-button">
      <svg class='line' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'>
        <g
          transform='translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) translate(5.000000, 8.500000)'>
          <path d='M14,0 C14,0 9.856,7 7,7 C4.145,7 0,0 0,0'></path>
        </g>
      </svg>
    </a>
    <h3>Daftar</h3>
    <p>Masukkan Data Diri</p>
    <?php
    if ($error) {
      ?>
      <div class="alert alert-danger text-center" style="padding:1rem 0.5rem;font-size:14px" role="alert">
        <?php echo $error ?>
      </div>
      <?php
    }
    ?>
    <?php
    if ($sukses) {
      ?>
      <div class="alert alert-success text-center" style="padding:1rem 0.5rem;font-size:14px;line-height:1.8em"
        role="alert">
        <?php echo $sukses ?>
        <br>
        <span>Silakan <a href="login">Login</a></span>
      </div>
      <?php
    }
    ?>

    <label for="nim">NIM</label>
    <input type="text" placeholder="Nomor Induk Mahasiswa" id="nim" name="nim">
    <div id="nimError" class="error"></div>

    <label for="password">Password</label>
    <input type="password" placeholder="Kata Sandi" id="password" name="password">
    <div id="passwordError" class="error"></div>

    <label for="nama">Nama</label>
    <input type="text" placeholder="Nama Lengkap" id="nama" name="nama">

    <label for="universitas">Universitas</label>
    <input type="text" placeholder="Universitas" id="universitas" name="universitas">

    </div>
    <?php

    // Mendapatkan data dari tabel penempatan
    $sqlpenempatan = "SELECT * FROM penempatan";
    $stmt = $conn->prepare($sqlpenempatan);
    $stmt->execute();
    $result = $stmt->fetchAll();

    $option_penempatan = '';
    foreach ($result as $row) {
      $option_penempatan .= '<option value="' . $row['penempatan_id'] . '">' . $row['penempatan_nama'] . '</option>';
    }
    ?>
    <label for="penempatan" class="col-sm-2 col-form-label">Penempatan</label>
    <div>
      <select class="form-select" name="penempatan_id" id="penempatan">
        <option value="">- Pilih Penempatan -</option>
        <?php echo $option_penempatan ?>
      </select>
    </div>
    <button type="submit" name="daftar" id="submitBtn">Daftar</button>

    <span style="margin-top:15px;color:#fff;font-size:14px">Sudah punya akun? <a href="login">Login</a></span>

  </form>
</body>

<script>
    const nimInput = document.getElementById('nim');
    const passwordInput = document.getElementById('password');
    const nimError = document.getElementById('nimError');
    const passwordError = document.getElementById('passwordError');
    const submitBtn = document.getElementById('submitBtn');

    nimInput.addEventListener('input', validateForm);
    passwordInput.addEventListener('input', validateForm);

    document.getElementById('form-login').addEventListener('submit', function(event) {
        if (submitBtn.disabled) {
            event.preventDefault();
            alert("Form tidak valid, silakan periksa kembali.");
        }
    });
</script>

</html>