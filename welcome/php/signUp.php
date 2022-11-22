<?php
session_start();
include_once "config.php";
$fullName = mysqli_real_escape_string($conn, $_POST['fullName']);
$userName = mysqli_real_escape_string($conn, $_POST['userName']);
$email = mysqli_real_escape_string($conn, $_POST['email']);
$pwd = mysqli_real_escape_string($conn, $_POST['pwd']);
$confirmPwd = mysqli_real_escape_string($conn, $_POST['confirmPwd']);


if (!empty($fullName) && !empty($userName) && !empty($email) && !empty($pwd) && !empty($confirmPwd)) {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $sql = mysqli_query($conn, "SELECT * FROM personalInfo WHERE email = '{$email}' OR userName = '{$email}'");
        if (mysqli_num_rows($sql) > 0) {
            echo "$email - This email already exist!";
        } else {
            $time = time();
            $uid = rand(time(), 100000000);
            $cid = rand(time(), 100000000); //cahceId...will be altered to and fro
            $status = "Active";
            $encrypt_pass = md5($pwd);
            $creatingDatabase =  mysqli_query($backEndConn, "CREATE DATABASE `$uid`");

            if ($creatingDatabase) {

                $userDBConn = mysqli_connect($hostname, $username, $password, $uid);

                if (!$userDBConn) {
                    echo "Database created Successfully but connection to DB failed!";
                } else {

                    $creatingNotificationTable = mysqli_query($userDBConn, "CREATE TABLE notifications (
                        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        sender VARCHAR(255) NOT NULL,
                        senderName VARCHAR(255) NOT NULL,
                        description VARCHAR(255) NOT NULL,
                        date  VARCHAR(255) NOT NULL,
                        time  VARCHAR(255) NOT NULL,
                        status boolean NOT NULL,
                        storyId INT(11)
                    )");
                    $creatingRequestsOutTable = mysqli_query($userDBConn, "CREATE TABLE requestsOutgoing (
                        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        recipient VARCHAR(255) NOT NULL,
                        recipientName VARCHAR(255) NOT NULL,
                        storyName VARCHAR(255) NOT NULL,
                        characters VARCHAR(255) NOT NULL,
                        date VARCHAR(255) NOT NULL,
                        time VARCHAR(255) NOT NULL,
                        status VARCHAR(255) NOT NULL,
                        storyId INT(11) NOT NULL
                    )");
                    $creatingRequestsInTable = mysqli_query($userDBConn, "CREATE TABLE requestsIncoming (
                        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        sender VARCHAR(255) NOT NULL,
                        senderName VARCHAR(255) NOT NULL,
                        storyName VARCHAR(255) NOT NULL,
                        characters VARCHAR(255) NOT NULL,
                        date VARCHAR(255) NOT NULL,
                        time VARCHAR(255) NOT NULL,
                        status VARCHAR(255) NOT NULL,
                        storyId INT(11) NOT NULL
                    )");

                    $creatingStoriesInTable = mysqli_query($userDBConn, "CREATE TABLE stories (
                        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                        storyName VARCHAR(255) NOT NULL,
                        storyChars VARCHAR(255) NOT NULL,
                        storyId INT(11) NOT NULL,
                        startedBy VARCHAR(255) NOT NULL,
                        date VARCHAR(255) NOT NULL,
                        time VARCHAR(255) NOT NULL,
                        status boolean NOT NULL,
                        storyDbName VARCHAR (255) NOT NULL
                    )");


                    if ($creatingNotificationTable && $creatingRequestsOutTable && $creatingRequestsInTable) {
                        $insert_query = mysqli_query($conn, "INSERT INTO personalInfo (uid, cid, fullName, userName, email, pwd, status)
                        VALUES ({$uid}, '{$cid}','{$fullName}', '{$userName}', '{$email}', '{$encrypt_pass}', '{$status}')");
                        if ($insert_query) {
                            $insert_query_1 = mysqli_query($conn, "INSERT INTO regUsers (uid, userName,  fullName)
                            VALUES ({$uid}, '{$userName}', '{$fullName}')");
                            if ($insert_query_1) {
                                $select_sql2 = mysqli_query($conn, "SELECT * FROM personalInfo WHERE email = '{$email}'");
                                if (mysqli_num_rows($select_sql2) == 1) {
                                    $result = mysqli_fetch_assoc($select_sql2);
                                    $_SESSION['cid'] = $result['cid'];
                                    $registeringSignIn = mysqli_query($conn, "INSERT INTO signedInUsers (uid,sessionId) VALUES ('{$uid}','{$result['cid']}')");
                                    if ($registeringSignIn) {
                                        echo "success";
                                    } else {
                                        echo "Everything Fine! Just login failed.";
                                    }
                                } else {
                                    echo "Something went wrong. Please contact admin!";
                                }
                            } else {
                                echo "Something went wrong. Please try again!";
                            }
                        } else {
                            echo "Something went wrong. Please try again!";
                        }
                    } else {
                        echo "Error while creating tables for users database!";
                    }
                }
            } else {
                echo "Something went wrong. Please try again!";
            }
        }
    } else {
        echo "$email is not a valid email!";
    }
} else {
    echo "All input fields are required!";
}
