<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");

$cid = $_SESSION['cid'];
$msgId = mysqli_real_escape_string($conn, $_POST['msgId']);
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chatText = mysqli_real_escape_string($conn, $_POST['chatText']);
$aux = false;

$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

$userData = getCharDetailsByCid($cid, $conn);

if ($userData['result'] == true) {

    $userId = $userData['data']['charUID'];
    $userName = $userData['data']['charFullName'];

    include_once("../connections/userDb.php");

    $storyData = getUserStoriesByStoryId($storyId, $userDbConn);
    if ($storyData['result'] == true) {

        $storyChars = $storyData['data']['storyChars'];
        $storyDbName = $storyData['data']['storyDbName'];


        include_once("../connections/storyDb.php");

        $storyDbData = fetchStoriesInfo($storyDbConn);

        if ($storyDbData['result'] == true) {
            $charDetails = getCharDetailsByUserId($storyDbData['rawData'], $userId, $aux);


            if ($charDetails['result'] == true) {
                if ($charCode == "null") {
                    $charCode = $charDetails['data']['charCode'];
                }

                $totalChars = $charDetails['data']['totalChars'];
                $charCode = $charDetails['data']['charCode'];
                $charName = $charDetails['data']['charName'];
                $charColor = $charDetails['data']['charColor'];

                $storyDetails = fetchStory($storyDbConn);

                if ($storyDetails['result'] == true) {
                    $msgHead = $charCode . "_msgId";
                    $chatHead = $charCode . "_message";
                    $totalNumOfWords = $charCode . "_totalNumberOfWords";
                    $words = (string)str_word_count($chatText);
                    $sql = "UPDATE story SET `" . $chatHead . "` = '{$chatText}',`" . $totalNumOfWords . "` = '{$words}' WHERE " . $msgHead . "={$msgId}";

                    $updateMsg = mysqli_query($storyDbConn, $sql);
                    if ($updateMsg) {
                        $results['result'] = true;
                        $results['msg'] = "Message Updated Successfully!";
                        $results['error'] = false;
                        echo json_encode($results);
                    } else {
                        $results['msg'] = "Message not updated!";
                        $results['errorCode'] = "UMX001";
                        echo json_encode($results);
                    }
                } else {
                    $results['msg'] = $storyDetails['msg'];
                    $results['errorCode'] = $storyDetails['errorCode'];
                    echo json_encode($results);
                }
            } else {
                $results['msg'] = $charDetails['msg'];
                $results['errorCode'] = $charDetails['errorCode'];
                echo json_encode($results);
            }
        } else {
            $results['msg'] = $storyDbData['msg'];
            $results['errorCode'] = $storyDbData['errorCode'];
            echo json_encode($results);
        }
    } else {
        $results['msg'] = $storyData['msg'];
        $results['errorCode'] = $storyData['errorCode'];
        echo json_encode($results);
    }
} else {
    $results['msg'] = $userData['msg'];
    $results['errorCode'] = $userData['errorCode'];
    echo json_encode($results);
}
