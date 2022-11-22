<?php
ini_set('display_errors', '1');

session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
// include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalChars.php");

$cid = $_SESSION['cid'];
// $send =  mysqli_real_escape_string($conn, $_POST['sendAs']);
// $msg = mysqli_real_escape_string($conn, $_POST['msg']);
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$rowToReplace = mysqli_real_escape_string($conn, $_POST['lineNumber']);


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
        $chapter = tableName($chapter, $storyDbConn);

        $storyDetails = fetchStory($storyDbConn, $chapter);
        $data = array();
        while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {
            $data[$msgRow['lineNumber']] = json_decode($msgRow['dialogue'], true);
        }
        $totalChars = totalChars($storyDbConn);
        $active = false;
        $activeCol = null;
        for ($i = 1; $i <= (int)$totalChars; $i++) {
            if (isset($data[$rowToReplace][$i]) && $data[$rowToReplace][$i]['details']['category'] == "act") {
                $active = true;
                break;
            }
        }

        if ($active) {
            $rowToReplace = $rowToReplace + 2;
        } else {
            $rowToReplace = $rowToReplace + 1;
        }

        $newRow = insertingRow($storyDbConn, $chapter, $rowToReplace, '{}');
        if ($newRow) {
            $results['success'] = "Hurrah 1jj!";
            echo json_encode($results);
        } else {
            $results['msg'] = $storyData['msg'];
            $results['errorCode'] = $storyData['errorCode'];
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



function insertingRow($conn, $chapter, $lineNumber, $dialogue)
{
    $updatingLineNum = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber+1 WHERE lineNumber > " . (int)$lineNumber - 1 . " ORDER BY lineNumber ASC");
    $lineCode = rand(time(), 100000000);
    $insertingRow = mysqli_query($conn, "INSERT INTO story_" . $chapter . " (lineNumber,lineCode,dialogue,status) VALUES ('{$lineNumber}','{$lineCode}','{$dialogue}',1)");
    if ($insertingRow && $updatingLineNum) {
        return true;
    } else {
        return false;
    }
}
