<?php
include_once "connections/main.php";
session_start();
$userId = mysqli_real_escape_string($conn, $_POST['userId']);
$storyName =  mysqli_real_escape_string($conn, $_POST['storyName']);
$totalChars =  mysqli_real_escape_string($conn, $_POST['totalChars']);
$sender = $_SESSION['cid'];
$date = date("d.m.Y");
$time = date("h:i:s");
$status = 'Pending';
include "connections/userDb.php";
// echo $userDbConn;
$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$sender}'");
if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $senderName = $row['fullName'];
    $sender = $row['uid'];
    $time = time();
    $nid = rand(time(), 100000000);
    $sendingRequest = mysqli_query($userDbConn, "INSERT INTO requestsIncoming (sender,senderName,storyName,characters,date,time,status,storyId) VALUES ('{$sender}','{$senderName}','{$storyName}','{$totalChars}','{$date}','{$time}','{$status}',{$nid})");
    if ($sendingRequest) {
        $desc = $senderName . " wants to create story on " . $storyName . " having " . $totalChars . " characters";
        $sendingNotification = mysqli_query($userDbConn, "INSERT INTO notifications (sender,senderName,description,date,time,status,storyId) VALUES ('{$sender}','{$senderName}','{$desc}','{$date}','{$time}',1,{$nid})");
        if ($sendingNotification) {
            $recipientId = $userId;
            $userId = $row['uid'];
            include "connections/userDb.php";
            $storingRequest = mysqli_query($userDbConn, "INSERT INTO requestsOutgoing (recipient,recipientName,storyName,characters,date,time,status,storyId) VALUES ('{$recipientId}','{$senderName}','{$storyName}','{$totalChars}','{$date}','{$time}','{$status}',{$nid})");
            if ($storingRequest) {
                echo "Sucessfully sent request!";
            } else {
                echo "Unable to send request! 00";
            }
        } else {
            echo "Unable to send request! 01";
        }
    } else {
        echo "Unable to send request! 02";
    }
} else {
    echo "No user found against this cid";
}


// echo "Request Sent" . $totalChars;
