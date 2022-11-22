<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);

$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);



$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");


if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];

    include_once("../connections/userDb.php");

    $accessingStoryDB = mysqli_query($userDbConn, "SELECT * FROM stories WHERE storyId = {$storyId}");

    if (mysqli_num_rows($accessingStoryDB) == 1) {
        $story = mysqli_fetch_assoc($accessingStoryDB);
        $storyDbName = $story['storyDbName'];
        $storyChars = $story['storyChars'];

        include_once("../connections/storyDb.php");
        $storiesInfoTable = mysqli_query($storyDbConn, "DELETE from pointer");
        if ($storiesInfoTable) {
            $results['result'] = true;
            $results['error'] = false;
            echo json_encode($results);
        } else {
            echo json_encode(array('error' => "Cant access stories Info", "result" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "result" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "result" => false));
}
