<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/tableName.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$currentRow = mysqli_real_escape_string($conn, $_POST['row']);
$currentCol = mysqli_real_escape_string($conn, $_POST['col']);
$text = mysqli_real_escape_string($conn, $_POST['text']);
$commentStatus = mysqli_real_escape_string($conn, $_POST['commentStatus']);

$commentStatus = filter_var($commentStatus, FILTER_VALIDATE_BOOLEAN);
$commentStatus = (int)$commentStatus;




$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

// echo $storyId, $charName, $charNewName;
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

        $sql = "INSERT INTO `pointer` (`charCode`, `row`, `col`, `text`,`chapter`,`comment`,`status`) values ($userId, " . (int)$currentRow . ", " . (int)$currentCol . ", '" . $text . "','{$chapter}',$commentStatus,1) ON DUPLICATE KEY UPDATE `col`=" . (int)$currentCol . ", `row`=" . (int)$currentRow . ", `chapter`='" . $chapter . "', `text`= '" . $text . "' , `comment`= '" . $commentStatus . "'";
        $updateCharName = mysqli_query($storyDbConn, $sql);
        if ($updateCharName) {
            echo json_encode(array('msg' => "Char Name Updated Successfully", "success" => true));
        } else {
            echo json_encode(array('error' => "Char updation failed", "sql" => $sql, "success" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
