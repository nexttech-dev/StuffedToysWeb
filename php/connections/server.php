<?php
$server_hostname = "localhost";
$server_username = "root";
$server_password = "";

$backEndConn = mysqli_connect($server_hostname, $server_username, $server_password);
if (!$backEndConn) {
    echo "Database connection error" . mysqli_connect_error();
}
