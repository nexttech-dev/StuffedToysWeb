
<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);

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
        include_once("../connections/server.php");

        // $deletingStory = mysqli_query($backEndConn, "DROP DATABASE `" . $storyDbName . "`");


        // if ($deletingStory) {
        //     $deletingStory = mysqli_query($userDbConn, "DELETE FROM stories WHERE storyId = {$storyId}");
        //     if ($deletingStory) {
        $deletingStory = mysqli_query($userDbConn, "DELETE FROM stories WHERE storyId = {$storyId}");

        echo json_encode(array('msg' => "Cleariing Successfull", "success" => true));
        // } else {
        // echo json_encode(array('error' => "Cant access stories Info", "success" => false));
        // }
        // } else {
        // echo json_encode(array('error' => "Cant access stories Info", "success" => false));
        // }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
