<?php
session_start();

$cid = $_SESSION['cid'];

include_once("../connections/main.php");
// echo $cid;
$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    include_once("../connections/userDb.php");
    $accessingUserDB = mysqli_query($userDbConn, "SELECT * FROM notifications");

    if (mysqli_num_rows($accessingUserDB) >> 0) {
        $count = 0;
        while ($notifications = mysqli_fetch_assoc($accessingUserDB)) {
            if ($notifications['status'] == true) {
                $count++;
                $notifiItem .= '<div class="notifi-item">
            <div class="text">
                <h4>' . $notifications['senderName'] . '</h4>
                <p>' . $notifications['description'] . '</p>
                <button class="notifiConfirm" onclick="notificationResp(' . $notifications['id'] . ',1)">Confirm</button>
                <button class="notifiDecline" onclick="notificationResp(' . $notifications['id'] . ',0)">Decline</button>
            </div>
        </div>';
            } else {
                $notifiItem .= '<div class="notifi-item">
            <div class="text">
                <h4>' . $notifications['senderName'] . '</h4>
                <p>' . $notifications['description'] . '</p>
                
            </div>
        </div>';
            }
        }

        $notifiStart = '<h2>Notifications <span>' . $count . '</span></h2>';
        $output = $notifiStart . $notifiItem;
        echo json_encode(array('count' => $count, 'data' => $output));
    } else {
        echo "No notifications are found!";
    }
} else {
    echo "No user found against this cid";
}
