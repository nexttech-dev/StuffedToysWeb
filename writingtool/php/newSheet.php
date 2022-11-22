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
        $storyName = $storyData['data']['storyName'];

        include_once("../connections/storyDb.php");

        $storyDbData = fetchStoriesInfo($storyDbConn);
        $chapter = tableName('1', $storyDbConn);

        if ($storyDbData['result'] == true) {
            $allTables = mysqli_query($storyDbConn, 'SHOW tables');
            $allChapters = array();
            while ($table = mysqli_fetch_array($allTables)) {
                $story = explode("_", $table[0]);
                if ($story[0] == 'story') {
                    $allChapters[] = $table[0];
                }
            }

            $makingNewSheet = mysqli_query($storyDbConn, "CREATE TABLE story_" . (sizeof($allChapters) + 1) . "_Chapter" . (sizeof($allChapters) + 1) . " LIKE story_" . $chapter . "");
            if ($makingNewSheet) {
                $results['result'] = true;
                $results['error'] = false;
                $results['errorCode'] = false;
                $results['data'] = sizeof($allChapters) + 1;
                echo json_encode($results);
            } else {
                $results['msg'] = "Failed to make new sheet";
                $results['errorCode'] = "NSX001";
                echo json_encode($results);
            }
        }
    } else {
    }
} else {
    $results['msg'] = $userData['msg'];
    $results['errorCode'] = $userData['errorCode'];
    echo json_encode($results);
}
