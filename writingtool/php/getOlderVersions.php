
<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalTables.php");


$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chap = mysqli_real_escape_string($conn, $_POST['chap']);

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
        $chapter = tableName($chap, $storyDbConn);
        $totalTables = totalTables($chap, $storyDbConn);

        $fetchingSaves = mysqli_query($storyDbConn, "SELECT * FROM config WHERE configName = 'saves'");
        if ($fetchingSaves && mysqli_num_rows($fetchingSaves) == 1) {
            $story = mysqli_fetch_assoc($fetchingSaves);
            $savesJSON = json_decode($story['configDetails'], true);


            echo json_encode(array('error' => false, "success" => true, 'saves' => json_encode($savesJSON)));
        } else {
            echo json_encode(array('error' => "No Saves Row", "success" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
