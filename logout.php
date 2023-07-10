<?php

session_start();
unset($_SESSION['sessionBrgyOperatorID']);

header("Location: index.php");
?>