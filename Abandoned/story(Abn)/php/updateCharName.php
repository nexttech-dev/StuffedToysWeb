<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$charName = mysqli_real_escape_string($conn, $_POST['charName']);
$charNewName = mysqli_real_escape_string($conn, $_POST['charNewName']);

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
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");

        if ($storiesInfoTable) {
            if (mysqli_num_rows($storiesInfoTable) >= 0) {
                $storiesInfoRow =  mysqli_fetch_assoc($storiesInfoTable);
                for ($i = 1; $i <= (int)$storyChars; $i++) {
                    if ($storiesInfoRow[$i . '_charName'] == $charName) {
                        $sql = "UPDATE storiesInfo SET `" . (string)$i . '_charName' . "` = '{$charNewName}' WHERE " . $i . '_charName' . "='{$charName}'";
                        $updateCharName = mysqli_query($storyDbConn, $sql);
                        if ($updateCharName) {
                            echo json_encode(array('msg' => "Char Name Updated Successfully", "success" => true));
                        } else {
                            echo json_encode(array('error' => "Char updation failed", "success" => false));
                        }
                    }
                }
            } else {
                echo json_encode(array('error' => "Stories Info missing", "success" => false));
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
