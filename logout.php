<?php

session_start();

$conn = new mysqli('localhost', 'root', '', 'ebarangaydatabase');

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                    $brgyID = $_SESSION['sessionBrgyOperatorID'];
                    $operation = 'Logged Out';
                    $dateAndTime = date('Y-m-d H:i:s');

                    $sqlGetOperatorID = "SELECT brgyOperatorID FROM barangay_operator WHERE userID = $brgyID";
                    $resultGetOperatorID = $conn->query($sqlGetOperatorID);
                    
                    $rowGetOperatorID = $resultGetOperatorID->fetch_assoc();

                    $logBrgy = $rowGetOperatorID['brgyOperatorID'];


                    $sqlLog = "INSERT INTO logs_table (operation, dateAndTime, brgyOperatorID) VALUES ('$operation', '$dateAndTime', '$logBrgy')";
                    $resultLog = $conn->query($sqlLog);
                    
unset($_SESSION['sessionBrgyOperatorID']);

header("Location: index.php");
?>