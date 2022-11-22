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
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);



$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

$userData = getCharDetailsByCid($cid, $conn);

if ($userData['result'] == true) {

    $userId = $userData['data']['charUID'];
    $userName = $userData['data']['charFullName'];

    include_once("../connections/userDb.php");

    $storyData = getUserStoriesByStoryId($storyId, $userDbConn);
    if ($storyData['result'] == true) {

        $storyDbName = $storyData['data']['storyDbName'];
        $storyName = $storyData['data']['storyName'];

        include_once("../connections/storyDb.php");
        $chapter = tableName($chapter, $storyDbConn);
        $storyDetails = fetchStory($storyDbConn, $chapter);
        $totalDiaRows = 0;
        $rowIds = array();
        $rowNos = array();

        while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {
            if ($msgRow['status'] == 0) {
                // $rowId = array($msgRow['lineCode'] =>  {'lineNumber' : })
                $diaWithOutHum[$msgRow['lineNumber']] = json_decode($msgRow['dialogue']);
                $totalDiaRows = $totalDiaRows + 1;
            }
            $rowNos[$msgRow['lineCode']] = $msgRow['lineNumber'];
            array_push($rowIds, $msgRow['lineCode']);
            $data[$msgRow['lineNumber']] = json_decode($msgRow['dialogue']);
        }
        echo json_encode(array('msg' => "success", "result" => true, 'error' => false, 'totalRows' => $storyDetails['totalRows'], 'dialogues' => json_encode($data), 'storyName' => $storyName, 'diaOnly' => json_encode($diaWithOutHum), 'diaOnlyTotal' => $totalDiaRows, 'rowIds' => $rowIds, 'rowNos' => $rowNos));
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
