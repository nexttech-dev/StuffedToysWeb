<?php
session_start();
include_once "config.php";
$email = mysqli_real_escape_string($conn, $_POST['email']);
$password = mysqli_real_escape_string($conn, $_POST['pwd']);
if (!empty($email) && !empty($password)) {
    $sql = mysqli_query($conn, "SELECT * FROM personalInfo WHERE email = '{$email}' OR userName = '{$email}'");
    if (mysqli_num_rows($sql) == 1) {
        $row = mysqli_fetch_assoc($sql);
        $user_pass = md5($password);

        if ($user_pass === $row['pwd']) {
            $status = "Active";
            $sql2 = mysqli_query($conn, "UPDATE personalInfo SET status = '{$status}' WHERE uid = {$row['uid']}");
            if ($sql2) {
                $_SESSION['cid'] = $row['cid'];
                echo "success";
            } else {
                echo "Something went wrong. Please try again!";
            }
        } else {
            echo "Email/User Name or Password is Incorrect!";
        }
    } else {
        echo "$email - This email not Exist!";
    }
} else {
    echo "All input fields are required!";
}
