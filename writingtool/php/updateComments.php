<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");

$cid = $_SESSION['cid'];
$msgId = mysqli_real_escape_string($conn, $_POST['msgId']);
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$comment = mysqli_real_escape_string($conn, $_POST['comment']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);


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
        $chapter = tableName($chapter, $storyDbConn);

        if ($storyDbData['result'] == true) {

            $totalChars = $storyDbData['totalChars'];

            $storyDetails = fetchStory($storyDbConn, $chapter);

            if ($storyDetails['result'] == true) {
                $msgHead = $charCode . "_msgId";
                $commentHead = $charCode . "_comment";
                // $totalNumOfWords = $charCode . "_totalNumberOfWords";
                // $words = (string)str_word_count($chatText);
                $sql = "UPDATE story_" . $chapter . " SET `" . $commentHead . "` = '{$comment}' WHERE " . $msgHead . "={$msgId}";

                $updateComment = mysqli_query($storyDbConn, $sql);
                if ($updateComment) {
                    $results['result'] = true;
                    $results['msg'] = "Comment Updated Successfully!";
                    $results['error'] = false;
                    $results['sql'] = $sql;
                    // $results['data'] = array()
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
