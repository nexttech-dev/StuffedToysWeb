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

        $storyName = $storyData['data']['storyName'];
        $storyDbName = $storyData['data']['storyDbName'];
        include_once("../connections/storyDb.php");
        $config = mysqli_query($storyDbConn, "SELECT * FROM config");


        //Fetching Configurations
        while ($configRow = mysqli_fetch_assoc($config)) {
            if ($configRow['status']) {
                $configData[$configRow['configName']] = json_decode($configRow['configDetails']);
            }
        }

        //Fetching Chapters (Sheets)
        $chapDetails = mysqli_query($storyDbConn, 'SHOW tables');
        $allChaps = array();
        while ($table = mysqli_fetch_array($chapDetails)) {
            $story = explode("_", $table[0]);
            if ($story[0] == 'story') {
                $allChaps[] = $table[0];
            }
        }

        //Fetching Chars Data
        $storyDbData = fetchStoriesInfo($storyDbConn);


        //Fetching All Dialogues
        $chapter = tableName($chapter, $storyDbConn);
        $storyDetails = fetchStory($storyDbConn, $chapter);
        while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {
            $data[$msgRow['lineNumber']] = json_decode($msgRow['dialogue']);
        }

        echo json_encode(array('msg' => "success", "result" => true, 'error' => false, 'config' => json_encode($configData), 'chapDetails' => json_encode($allChaps), 'charInfo' => json_encode($storyDbData['data']), 'totalRows' => $storyDetails['totalRows'], 'dialogues' => json_encode($data), 'storyName' => $storyName));
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
