<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$msgId = mysqli_real_escape_string($conn, $_POST['msgId']);

// $char = mysqli_real_escape_string($conn, $_POST['char']);

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
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");
        if ($storiesInfoTable) {
            if (mysqli_num_rows($storiesInfoTable) >= 0) {
                $storiesInfoRow =  mysqli_fetch_assoc($storiesInfoTable);
                $toSave = true;
                for ($i = 1; $i <= (int)$storyChars; $i++) {
                    if ($storiesInfoRow[(string)$i . '_charController'] == $userId) {
                        $char = (string)$i;
                        $charName = $storiesInfoRow[(string)$i . '_charName'];
                        $charColor = $storiesInfoRow[(string)$i . '_color'];
                        $i =  (int)$storyChars;
                    }
                }

                $storyDetails = mysqli_query($storyDbConn, "SELECT * FROM story");
                if ($storyDetails) {
                    if (mysqli_num_rows($storyDetails) >= 0) {
                        $msgHead = $char . "_msgId";
                        $delMsg = mysqli_query($storyDbConn, "DELETE FROM story WHERE " . $msgHead . "={$msgId}");
                        if ($delMsg) {
                            echo json_encode(array('error' => "Msg Deleted Successfully", "success" => true, $msgHead => $msgId));
                        } else {
                            echo json_encode(array('error' => "Msg deletion failed", "success" => false));
                        }
                    } else {
                        echo json_encode(array('error' => "No messages found!", "success" => false));
                    }
                } else {
                    echo json_encode(array('error' => "Cant fetch Story Table", "success" => false));
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
