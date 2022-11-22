<?php
// session_start();
// include_once("../connections/main.php");
// include_once("../php/functions/getCharDetails.php");
// include_once("../php/functions/getUserStoriesByStoryId.php");
// include_once("../php/functions/fetchStoryDb.php");
// include_once("../php/storyDb.php");
// include_once("../php/functions/tableName.php");

// $cid = $_SESSION['cid'];
// $storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
// $chapter = mysqli_real_escape_string($conn, $_POST['chapter']);



// $results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

// $userData = getCharDetailsByCid($cid, $conn);

// if ($userData['result'] == true) {

//     $userId = $userData['data']['charUID'];
//     $userName = $userData['data']['charFullName'];

//     include_once("../connections/userDb.php");

//     $storyData = getUserStoriesByStoryId($storyId, $userDbConn);
//     if ($storyData['result'] == true) {

//         $storyChars = $storyData['data']['storyChars'];
//         $storyDbName = $storyData['data']['storyDbName'];
//         $storyName = $storyData['data']['storyName'];

//         include_once("../connections/storyDb.php");

//         $storyDbData = fetchStoriesInfo($storyDbConn);

//         $chapter = tableName($chapter, $storyDbConn);

//         if ($storyDbData['result'] == true) {
//             $storyDetails = fetchStory($storyDbConn, $chapter);

//             $allTables = mysqli_query($storyDbConn, 'SHOW tables');
//             $allChapters = array();
//             while ($table = mysqli_fetch_array($allTables)) {
//                 $story = explode("_", $table[0]);
//                 if ($story[0] == 'story') {
//                     $allChapters[] = $table[0];
//                 }
//             }
//             if ($storyDetails['result'] == true) {
//                 $allTables = mysqli_query($storyDbConn, 'SHOW tables');

//                 $totalMessages = $storyDetails['data']['totalNumberOfLines'];
//                 $totalChars = $storyDbData['totalChars'];
//                 $data = array();
//                 $lineCodesOnly = array();
//                 $lineCodesWithNum = array();
//                 $msgsIdOnly = array();
//                 $count = 0;
//                 while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {

//                     $count = $count + 1;
//                     array_push($lineCodesOnly, $msgRow['lineCode']);
//                     array_push($lineCodesWithNum, array("lineCode" => $msgRow['lineCode'], "lineNumber" => $msgRow['lineNumber']));
//                     $data[$msgRow['lineNumber']]['lineCode'] =  $msgRow['lineCode'];
//                     for ($j = 1; $j <= (int)$totalChars; $j++) {
//                         $data[$msgRow['lineNumber']][$j] = array('id' => $msgRow[$j . '_msgId'], 'msg' => $msgRow[$j . '_message'], 'active' => $msgRow['active'], 'action' => $msgRow[$j . '_act'], 'comment' => $msgRow[$j . '_comment']);
//                     }
//                 }
//                 $config = mysqli_query($storyDbConn, "SELECT * FROM config");

//                 while ($configRow = mysqli_fetch_assoc($config)) {
//                     $configData[$configRow['configName']] = $configRow['status'];
//                 }
//                 echo json_encode(array('msg' => $output, "result" => true, 'error' => false, 'totalLines' => $totalMessages, 'totalChars' => $totalChars, 'data' => $data, 'charsData' => $storyDbData['data'], 'config' => $configData, 'allChapters' => $allChapters, 'storyTitle' => $storyName . " - " . $chapter, "lineCodesOnly" => $lineCodesOnly, "lineCodesWithNum" => $lineCodesWithNum));
//             }
//         }
//     } else {
//         $results['msg'] = $storyData['msg'];
//         $results['errorCode'] = $storyData['errorCode'];
//         echo json_encode($results);
//     }
// } else {
//     $results['msg'] = $userData['msg'];
//     $results['errorCode'] = $userData['errorCode'];
//     echo json_encode($results);
// }
