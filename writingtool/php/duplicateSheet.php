
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

        $newTableName =  explode("_", $chapter);
        $newTableName = $totalTables['data'] . "_" . $newTableName[1];

        $duplicatingSheet = mysqli_query($storyDbConn, "CREATE TABLE story_" . $newTableName . $totalTables['data'] . " LIKE story_" . $chapter . "");

        if ($duplicatingSheet) {
            $copyingDataToNewTable = mysqli_query($storyDbConn, "INSERT story_" . $newTableName . $totalTables['data'] . " SELECT * FROM story_" . $chapter . "");
            if ($copyingDataToNewTable) {
                echo json_encode(array('msg' => "Cleariing Successfull", "success" => true, 'sql' => $sql));
            } else {
                echo json_encode(array('error' => "New Sheet Created but its empty!", "success" => false));
            }
        } else {
            echo json_encode(array('error' => "Cant access stories Info", "success" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
