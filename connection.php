<?php

$conn = mysqli_connect("localhost", "root", "");

if(mysqli_connect_error())
{
    echo "failed to connect to mysql : ".mysqli_connect_error();
    die();
}