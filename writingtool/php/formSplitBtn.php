<?php


// $send =  mysqli_real_escape_string($conn, $_POST['sendAs']);

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
$firstHalf = mysqli_real_escape_string($conn, $_POST['firstHalf']);
$secondHalf = mysqli_real_escape_string($conn, $_POST['secondHalf']);
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

        if ($data[$rowToReplace][$charCode] && $data[$rowToReplace][$charCode]['details']['category'] == "act") {
            $generatedRow1 = generatingDialogue($rowToReplace + 2, $charCode, $secondHalf, 'act');
            $generatedRow2 = generatingDialogue($rowToReplace + 3, $charCode, $secondHalf, 'human');
            if ($generatedRow1) {
                $insertingNewRow = insertingRow($storyDbConn, $chapter, $rowToReplace + 2, $generatedRow1);
                if ($insertingNewRow) {
                    $insertingNewRow = insertingRow($storyDbConn, $chapter, $rowToReplace + 3, $generatedRow2);
                    if ($insertingNewRow) {
                        $dateTime = (new DateTime())->format("Y-m-d H:i:s");

                        $data[$rowToReplace][$charCode]['details']['category'] = 'act';
                        $data[$rowToReplace][$charCode]['details']['dateTime'] = $dateTime;
                        $data[$rowToReplace][$charCode]['details']['dialogue'] = $firstHalf;
                        $data[$rowToReplace][$charCode]['details']['numOfWords'] = str_word_count($firstHalf);
                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$rowToReplace], $rowToReplace);
                        if ($updatingCell) {
                            $data[$rowToReplace + 1][$charCode]['details']['category'] = 'human';
                            $data[$rowToReplace + 1][$charCode]['details']['dateTime'] = $dateTime;
                            $data[$rowToReplace + 1][$charCode]['details']['dialogue'] = $firstHalf;
                            $data[$rowToReplace + 1][$charCode]['details']['numOfWords'] = str_word_count($firstHalf);
                            $updatingCell = updatingCell($storyDbConn, $chapter, $data[$rowToReplace + 1], $rowToReplace + 1);
                            if ($updatingCell) {
                                $results['success'] = "Hurrah!";
                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        } else {
                            $results['msg'] = 'Error';
                            $results['errorCode'] = "SD230X3dsfds2";
                            echo json_encode($results);
                        }
                    } else {
                        $results['msg'] = 'Error';
                        $results['errorCode'] = "SD230X3dsfsd2";
                        echo json_encode($results);
                    }
                } else {
                    $results['msg'] = 'Error';
                    $results['errorCode'] = "SD230dsfX32";
                    echo json_encode($results);
                }
            } else {
                $results['msg'] = 'Error';
                $results['errorCode'] = "SD230X32sdfsd";
                echo json_encode($results);
            }
        } else {
            $results['msg'] = 'Error';
            $results['errorCode'] = "SD230Xdfsf32";
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
function generatingDialogue($row, $col, $dialogueText, $cat)
{

    $id = rand(time(), 100000000);
    $dateTime = (new DateTime())->format("Y-m-d H:i:s");
    $numOfWords = str_word_count($dialogueText);
    $dialogue = "";
    $dialogue = '{
        "' . $col . '" : {
          "charCode" :  ' . $col . ',
          "details" : {
            "row" : ' . $row . ',
            "id" : ' . $id . ',
            "dateTime" : "' . $dateTime . '",
            "comment" : null,
            "dialogue" : "' . $dialogueText . '",
            "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
            "numOfWords" : ' . $numOfWords . ',
            "category" : "' . $cat . '",
            "active" : ' . $col . '
          }
        }
      }';

    return $dialogue;
}
function updatingCell($conn, $chapter, $dialogue, $row)
{
    $dialogue = json_encode($dialogue);
    $updatingCell = mysqli_query($conn, "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'");
    if ($updatingCell) {
        return true;
    } else {
        return false;
    }
}
