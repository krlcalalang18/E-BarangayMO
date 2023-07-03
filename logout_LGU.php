<?php

session_start();
unset($_SESSION['LGUOperatorID']);

header("Location: index.php");
?>