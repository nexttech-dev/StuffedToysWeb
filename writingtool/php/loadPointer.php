<?php
// <?php
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
        $pointer = mysqli_query($storyDbConn, "SELECT * FROM pointer");

        $pointersData = array();
        $count = 0;
        while ($pointerCordinates = mysqli_fetch_assoc($pointer)) {
            if (isset($pointerCordinates['charCode']) && $pointerCordinates['charCode'] != $userId) {
                $count = $count + 1;
                $pointersData[$count] =  array('row' => $pointerCordinates['row'], 'col' => $pointerCordinates['col'], 'text' =>  $pointerCordinates['text'], 'comment' => $pointerCordinates['comment']);
            }
        }
        echo json_encode(array('msg' => "success", "result" => true, 'error' => false, 'data' => json_encode($pointersData)));
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
