<?php 
// Aini

session_start();
unset($_SESSION['nik']);
//session_destroy();
 
header("Location: login");
 
?>