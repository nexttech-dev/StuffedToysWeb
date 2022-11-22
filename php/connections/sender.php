<?php
$hostname = "localhost";
$username = "root";
$password = "";
// echo "User Id form external file" . $userId;
$senderDbConn = mysqli_connect($hostname, $username, $password, $sender);
if (!$senderDbConn) {
    echo "Database connection error" . mysqli_connect_error();
}
