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
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);
$rowToCheck = mysqli_real_escape_string($conn, $_POST['lineNumber']);
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

            $storyDetails = fetchStory($storyDbConn, $chapter);

            if ($storyDetails['result'] == true) {
                $lineNumber = $storyDetails['data']['totalNumberOfLines'];
                $totalChars = (int)$storyDbData['totalChars'];

                if ((int)$lineNumber < (int)$rowToCheck) {
                    $results['result'] = true;
                    $results['msg'] = "newMessage";
                    $results['error'] = false;
                    $results['data'] = null;
                    echo json_encode($results);
                } else {
                    while ($row = mysqli_fetch_assoc($storyDetails['rawData'])) {
                        if ($row['lineNumber'] == $rowToCheck) {
                            $results['result'] = true;
                            $results['msg'] = "insertMessage";
                            $results['error'] = false;
                            for ($i = 1; $i <= $totalChars; $i++) {
                                $results['data'][$i] = array('msgId' => $row[$i . '_msgId'], 'msgAction' => $row[$i . '_act'], 'msg' => $row[$i . '_message']);
                            }
                            $results['data']['totalChars'] = $totalChars;
                            $results['data']['active'] = $row['active'];
                            echo json_encode($results);
                            break;
                        }
                    }
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
