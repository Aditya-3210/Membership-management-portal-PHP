<?php
include('includes/config.php');

$_SESSION = array();

session_destroy();

header("Location: index2.php");
exit();
?>
