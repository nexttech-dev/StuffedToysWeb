<?php
session_start();
include_once("../connections/main.php");

$results = array();

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$auxName = mysqli_real_escape_string($conn, $_POST['auxName']);

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];

    include_once("../connections/userDb.php");

    $accessingStoryDB = mysqli_query($userDbConn, "SELECT * FROM stories WHERE storyId = {$storyId}");

    if (mysqli_num_rows($accessingStoryDB) == 1) {
        $story = mysqli_fetch_assoc($accessingStoryDB);
        $storyDbName = $story['storyDbName'];
        $storyChars = $story['storyChars'];

        include_once("../connections/storyDb.php");
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");
        if ($storiesInfoTable) {
            $charCode = (string)mysqli_num_rows($storiesInfoTable);
            $charDetails = getCharDetails($storiesInfoTable, $auxName);
            if ($charDetails['result'] == false) {
                $newColor = '#' . random_color();
                $storyNameSI = $charDetails['storyName'];
                $storyCharsSI = $charDetails['storyChars'];
                $storyIdSI = (int)$charDetails['storyId'];
                $startedBySI = $charDetails['startedBy'];
                $dateSI = $charDetails['date'];
                $timeSI = $charDetails['time'];

                $sql = "INSERT INTO storiesInfo (storyName,storyChars,storyId,startedBy,date,time,charCode,charId,charName,charController,color,aux,status) VALUES ('{$storyNameSI}','{$storyCharsSI}','{$storyIdSI}','{$startedBySI}','{$dateSI}','{$timeSI}','{$charCode}','','$auxName','{$userId}','{$newColor}',1,1)";
                $updatingStoriesInfo = mysqli_query($storyDbConn, $sql);

                // // $updatingStoriesInfo = mysqli_query($storyDbConn, "INSERT INTO `storiesInfo` ( `storyName`, `storyChars`, `storyId`, `startedBy`, `date`, `time`, `charCode`, `charName`, `charController`, `color`, `status`) VALUES ('{$storyNameSI}','{$storyCharsSI}','{$storyIdSI}','{$startedBySI}','{$dateSI}','{$timeSI}','{$charCode}','{$auxName}','{$userId}','{$newColor}',1)");
                if ($updatingStoriesInfo) {
                    $char = (string)$charCode;
                    $charName = $auxName;
                    $charColor = $newColor;

                    $addingAux = addingAuxActorInStoryTable($char, $storyDbConn);

                    if ($addingAux['result'] == true) {
                        $addingAuxStories = addingAuxActorInStories($char, $storyDbConn);
                        if ($addingAuxStories['result'] == true) {
                            $results['success'] = true;
                            $results['msg'] = "Aux Actor Added!";
                            $results['errorCode'] = null;
                            $results['data'] = array('charCode' => $char, 'charName' => $charName, 'charColor' => $charColor, 'sql' => $addingAux);
                            echo json_encode($results);
                        } else {
                            $results['success'] = false;
                            $results['msg'] = "Failed to update total number of chars";
                            $results['errorCode'] = "AAAX006";
                            $results['data'] = array('charCode' => $char, 'charName' => $charName, 'charColor' => $charColor, 'sql' => $sql);
                            echo json_encode($results);
                        }
                    } else {
                        $results['success'] = false;
                        $results['msg'] = "Failed to save this character in story";
                        $results['errorCode'] = "AAAX004";
                        $results['data'] = array('charCode' => $char, 'charName' => $charName, 'charColor' => $charColor, 'sql' => $sql);
                        echo json_encode($results);
                    }
                } else {
                    $char = (string)$charCode;
                    $charName = $auxName;
                    $charColor = $newColor;
                    $results['success'] = false;
                    $results['msg'] = "Failed to save this character in storiesInfo";
                    $results['errorCode'] = "AAAX002";
                    $results['data'] = array('charCode' => $char, 'charName' => $charName, 'charColor' => $charColor, 'sql' => $sql);
                    echo json_encode($results);
                }
            } else {
                $results['success'] = false;
                $results['msg'] = "Charactor already exisits!";
                $results['errorCode'] = "AAAX003";
                $results['data'] = null;
                echo json_encode($results);
            }
        } else {
            echo json_encode(array('error' => "Cant access stories Info", "success" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    $results['success'] = false;
    $results['msg'] = "No user or more than one user found!";
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


function addingAuxActorInStoryTable($charCode, $conn)
{
    $result = array('result' => false, 'msg' => null, 'errorCode' => null, 'data' => null);

    $msgId = $charCode . '_msgId';
    $lineNumber = $charCode . '_lineNumber';
    $date = $charCode . '_date';
    $time = $charCode . '_time';
    $comment = $charCode . '_comment';
    $message = $charCode . '_message';
    $fileName = $charCode . '_fileName';
    $totalNumberOfWords = $charCode . '_totalNumberOfWords';
    $msgCategory = $charCode . '_msgCat';
    $act = $charCode . '_act';
    $toShow = $charCode . '_show';

    $sql = "ALTER TABLE story ADD " . $msgId . " INT(11), ADD " . $lineNumber . " INT(11), ADD " . $date . " VARCHAR(255), ADD " . $time . " VARCHAR(255), ADD " . $comment . " VARCHAR(255), ADD " . $message . " VARCHAR(255), ADD " . $fileName . " VARCHAR(255), ADD " . $totalNumberOfWords . " VARCHAR(255), ADD " . $msgCategory . " BOOLEAN, ADD " . $act . " VARCHAR(255), ADD " . $toShow . " BOOLEAN";
    $addingAux = mysqli_query($conn, $sql);

    if ($addingAux) {
        $result['result'] = true;
    }
    return $result;
}

function addingAuxActorInStories($totalChars, $conn)
{
    $result = array('result' => false, 'msg' => null, 'errorCode' => null, 'data' => null);

    $sql = "UPDATE storiesInfo SET storyChars=" . $totalChars;
    $addingAux = mysqli_query($conn, $sql);


    if ($addingAux) {
        $result['result'] = true;
    }
    return $result;
}
