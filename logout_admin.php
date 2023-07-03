<?php

session_start();
unset($_SESSION['sessionAdminID']);

header("Location: admin_login_page.php");
?>