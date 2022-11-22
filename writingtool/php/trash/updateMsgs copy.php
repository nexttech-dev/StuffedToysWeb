<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$msgId = mysqli_real_escape_string($conn, $_POST['msgId']);
$chatText = mysqli_real_escape_string($conn, $_POST['chatText']);

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
                $charDetails = getCharDetails($storiesInfoTable, $userId);
                if ($charDetails['result'] == true) {
                    $char = $charDetails['char'];
                    $charName = $charDetails['charName'];
                    $charColor = $charDetails['charColor'];
                    $i =  (int)$storyChars;
                } else {
                }

                $storyDetails = mysqli_query($storyDbConn, "SELECT * FROM story");
                if ($storyDetails) {
                    if (mysqli_num_rows($storyDetails) >= 0) {
                        $msgHead = $char . "_msgId";
                        $chatHead = $char . "_message";
                        $totalNumOfWords = $char . "_totalNumberOfWords";
                        $words = (string)str_word_count($chatText);
                        $sql = "UPDATE story SET `" . $chatHead . "` = '{$chatText}',`" . $totalNumOfWords . "` = '{$words}' WHERE " . $msgHead . "={$msgId}";
                        $updateMsg = mysqli_query($storyDbConn, $sql);
                        if ($updateMsg) {
                            echo json_encode(array('error' => "Msg Updated Successfully", "success" => true, $msgHead => $msgId, $chatHead => $chatText, $totalNumOfWords => $words, $sql));
                        } else {
                            echo json_encode(array('error' => "Msg updation failed", "success" => false));
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
function getAllCharDetails($storiesInfoTable, $totalChars)
{
    $result = array('result' => false);
    $charCode = 1;
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        if ($storiesInfoRow['charCode'] == (string)$charCode) {
            $result['result'] = true;
            $result[$charCode] = array('charCode' => $storiesInfoRow['charCode'], 'charName' => $storiesInfoRow['charName'], 'charColor' => $storiesInfoRow['color']);
            $charCode = $charCode + 1;
        }
    }
    return $result;
}
