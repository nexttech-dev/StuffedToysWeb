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
        include_once("../connections/storyDb.php");
        $config = mysqli_query($storyDbConn, "SELECT * FROM config");

        while ($configRow = mysqli_fetch_assoc($config)) {
            if ($configRow['status']) {
                $configData[$configRow['configName']] = json_decode($configRow['configDetails']);
            }
        }

        $chapDetails = mysqli_query($storyDbConn, 'SHOW tables');
        $allChaps = array();
        while ($table = mysqli_fetch_array($chapDetails)) {
            $story = explode("_", $table[0]);
            if ($story[0] == 'story') {
                $allChaps[] = $table[0];
            }
        }
        echo json_encode(array('msg' => "success", "result" => true, 'error' => false, 'config' => json_encode($configData), 'chapDetails' => json_encode($allChaps)));
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
