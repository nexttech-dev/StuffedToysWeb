<?php
session_start();
include_once("../connections/main.php");

$results = array();

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$email = mysqli_real_escape_string($conn, $_POST['email']);

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");
$gettingOthUser = mysqli_query($conn, "SELECT * FROM personalInfo WHERE email = '{$email}'");


if (mysqli_num_rows($gettingUID) == 1 && mysqli_num_rows($gettingOthUser) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $row1 = mysqli_fetch_assoc($gettingOthUser);
    $userId = $row['uid'];
    $userName = $row['fullName'];
    $userId1 = $row1['uid'];
    $userName1 = $row1['fullName'];

    include("../connections/userDb.php");

    $accessingStoryDB = mysqli_query($userDbConn, "SELECT * FROM stories WHERE storyId = {$storyId}");

    if (mysqli_num_rows($accessingStoryDB) == 1) {
        $story = mysqli_fetch_assoc($accessingStoryDB);
        $storyDbName = $story['storyDbName'];
        $storyChars = $story['storyChars'];
        $storyName = $story['storyName'];
        $startedBy = $story['startedBy'];
        $date = $story['date'];
        $time = $story['time'];
        $status = $story['status'];

        $userId = $userId1;
        include("../connections/userDb.php");


        $sql = "INSERT INTO stories (storyName,storyChars,storyId,startedBy,date,time,status,storyDbName) VALUES ('{$storyName}','{$storyChars}','{$storyId}','{$startedBy}','{$date}','{$time}','{$status}','{$storyDbName}')";
        $sharingStory = mysqli_query($userDbConn, $sql);

        if ($sharingStory) {
            $results['success'] = true;
            $results['msg'] = "Story Shared Successfully!";
            $results['error'] = false;
            $results['data'] = null;
            echo json_encode($results);
        } else {
            $results['success'] = false;
            $results['msg'] = "Something went wrong! Please try again later.";
            $results['errorCode'] = "AAAX005";
            $results['data'] = null;
            echo json_encode($results);
        }
    } else {
        echo json_encode(array('msg' => "Cant Share at the moment!", "success" => false));
    }
} else {
    $results['success'] = false;
    $results['msg'] = "No User Found!";
    $results['errorCode'] = "AAAX001";
    $results['data'] = null;
    echo json_encode($results);
}
function getCharDetails($storiesInfoTable, $charName)
{
    $result = array('result' => false, 'storyName' => null, 'storyChars' => null, 'storyId' => null, 'startedBy' => null, 'date' => null, 'time' => null);
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        $result['storyName'] = $storiesInfoRow['storyName'];
        $result['storyChars'] = $storiesInfoRow['storyChars'];
        $result['storyId'] = $storiesInfoRow['storyId'];
        $result['startedBy'] = $storiesInfoRow['startedBy'];
        $result['date'] = $storiesInfoRow['date'];
        $result['time'] = $storiesInfoRow['time'];
        if ($storiesInfoRow['charName'] == $charName) {
            $result['result'] = true;
            break;
        }
    }
    return $result;
}
function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}
function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}


function addingCharInStoryTable($charCode, $conn)
{
    $result = array('result' => false, 'msg' => null, 'errorCode' => null, 'data' => null);

    $msgId = $charCode . '_msgId';
    $date = $charCode . '_date';
    $time = $charCode . '_time';
    $comment = $charCode . '_comment';
    $message = $charCode . '_message';
    $fileName = $charCode . '_fileName';
    $totalNumberOfWords = $charCode . '_totalNumberOfWords';
    $msgCategory = $charCode . '_msgCat';
    $act = $charCode . '_act';
    $toShow = $charCode . '_show';
    $tables = mysqli_query($conn, "show tables");

    $allTables = array();
    while ($table = mysqli_fetch_array($tables)) {
        $allTables[] = $table[0];
    }
    for ($i = 0; $i < count($allTables); $i++) {
        $story = explode("_", $allTables[$i]);
        if ($story[0] == 'story') {
            $sql = "ALTER TABLE $allTables[$i] ADD " . $msgId . " INT(11), ADD " . $date . " VARCHAR(255), ADD " . $time . " VARCHAR(255), ADD " . $comment . " VARCHAR(255), ADD " . $message . " VARCHAR(255), ADD " . $fileName . " VARCHAR(255), ADD " . $totalNumberOfWords . " VARCHAR(255), ADD " . $msgCategory . " BOOLEAN, ADD " . $act . " VARCHAR(255), ADD " . $toShow . " BOOLEAN";
            $addingChar = mysqli_query($conn, $sql);
            if (!$addingChar) {
                break;
            }
        }
        if ($i == count($allTables) - 1) {
            $result['result'] = true;
            return $result;
        }
    }
    return ($result);
}

function addingCharInStoriesInfoTb($totalChars, $conn)
{
    $result = array('result' => false, 'msg' => null, 'errorCode' => null, 'data' => null, 'error' => true);

    $sql = "UPDATE storiesInfo SET storyChars=" . (string)$totalChars;
    $addingAux = mysqli_query($conn, $sql);
    if ($addingAux) {
        $result['result'] = true;
        $result['error'] = false;
    }
    return $result;
}
