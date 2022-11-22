<?php
$hostname = "localhost";
$username = "root";
$password = "";
// echo "User Id form external file" . $userId;
$storyDbConn = mysqli_connect($hostname, $username, $password, $storyDbName);
if (!$storyDbConn) {
    echo "failed to connect to storyDbConn " . mysqli_connect_error();
}
