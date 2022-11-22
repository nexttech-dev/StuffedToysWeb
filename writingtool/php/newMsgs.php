<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$prevDataLength = mysqli_real_escape_string($conn, $_POST['totalLines']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
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
                $allCharDetails = $storyDbData['data'];
                $lineNumber = $storyDetails['data']['totalNumberOfLines'];
                $date = date("d.m.Y");
                $time = time();
                $comment = "None";
                $totalChars = (int)$totalChars;

                $output = "";
                $onlyMsgs = array();
                $storyDetailsTemp = null;
                if ($lineNumber == ((int)$prevDataLength + 2)) {
                    $storyDetailsTemp = $storyDetails['rawData'];
                }
                mysqli_data_seek($storyDetails['rawData'], $lineNumber - 1);
                $msgRow = mysqli_fetch_array($storyDetails['rawData']);
                $data = array();
                $count = 1;
                if ($storyDetailsTemp) {
                    mysqli_data_seek($storyDetailsTemp, $lineNumber - 2);
                    $msgRowTemp = mysqli_fetch_array($storyDetailsTemp);
                    for ($j = 1; $j <= $totalChars; $j++) {
                        $data[1][$count][$j] = array('id' => $msgRowTemp[$j . '_msgId'], 'msg' => $msgRowTemp[$j . '_message'], 'active' => $msgRowTemp['active'], 'action' => $msgRowTemp[$j . '_act']);
                        $count = $count + 1;
                    }
                    $count = 1;
                    for ($j = 1; $j <= $totalChars; $j++) {
                        $data[2][$count][$j] = array('id' => $msgRow[$j . '_msgId'], 'msg' => $msgRow[$j . '_message'], 'active' => $msgRow['active'], 'action' => $msgRow[$j . '_act']);
                        $count = $count + 1;
                    }
                } else {
                    for ($j = 1; $j <= $totalChars; $j++) {
                        $data[$count][$j] = array('id' => $msgRow[$j . '_msgId'], 'msg' => $msgRow[$j . '_message'], 'active' => $msgRow['active'], 'action' => $msgRow[$j . '_act']);
                        $count = $count + 1;
                    }
                }

                echo json_encode(array('userID' => $msgRow['active'], "data" => $data, 'totalLines' =>  $lineNumber, 'charsData' => $allCharDetails));
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
