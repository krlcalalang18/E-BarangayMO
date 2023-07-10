<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '1234') {

        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;

        header("Location: admin_login_page.php");
        exit;
    } else {
        header("Location: session_error_page_admin_blocker.php");
    }
}
?>
