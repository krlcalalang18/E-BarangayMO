<?php

session_start();
unset($_SESSION['LGUOperatorID']);

header("Location: lgu_operator_login_page.php");
?>