<?php

session_start();
include_once "config.php";
$outgoing_id = $_SESSION['cid'];
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$sql = "SELECT * FROM personalInfo WHERE NOT cid = {$outgoing_id} AND (fullName LIKE '%{$searchTerm}%' OR userName LIKE '%{$searchTerm}%') ";
$query = mysqli_query($conn, $sql);
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "No users are available to create story";
} elseif (mysqli_num_rows($query) > 0) {
    while ($row = mysqli_fetch_assoc($query)) {

        $output .= '<a href="#"><div class="list">
        <div class="imgBx">
            <img src="assets/images/img1.jpg">
        </div>
        <div class="content">
            <h2 class="rank"><small>#</small>1</h2>
            <h4>' . $row['fullName'] . '</h4>
            <p>' . $row['userName'] . '</p>
        </div>
    </div></a>';
    }
}
echo $output;
