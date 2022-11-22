<?php
$hostname = "localhost";
$username = "root";
$password = "tvc@2022";
$dbname = "users";

$conn = mysqli_connect($hostname, $username, $password, $dbname);
if (!$conn) {
    echo "Database connection error" . mysqli_connect_error();
}
