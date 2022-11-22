<?php
$hostname = "localhost";
$username = "root";
$password = "";
// echo "User Id form external file" . $userId;
$userDbConn = mysqli_connect($hostname, $username, $password, $userId);
if (!$userDbConn) {
    echo "Database connection error" . mysqli_connect_error();
}
