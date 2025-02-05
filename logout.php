<?php 
// Aini

session_start();
unset($_SESSION['nim']);
//session_destroy();
 
header("Location: login");
 
?>