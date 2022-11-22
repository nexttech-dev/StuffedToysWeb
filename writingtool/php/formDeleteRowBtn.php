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
$rowToDelete = mysqli_real_escape_string($conn, $_POST['lineNumber']);


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
        $human = false;
        $actRow = null;
        $actCol = null;

        for ($i = 1; $i <= (int)$totalChars; $i++) {
            if (isset($data[$rowToDelete][$i]) && $data[$rowToDelete][$i]['details']['category'] == "act") {
                $active = true;
                break;
            }
            if (isset($data[$rowToDelete][$i]) && $data[$rowToDelete][$i]['details']['category'] == "human") {
                unset($data[$rowToDelete - 1][$i]);
                $human = true;
                break;
            }
        }

        if ($active) {
            // for ($i = 0; $i < 2; $i++) {
            $newRow = deletingRowOnly($storyDbConn, $chapter, $rowToDelete, true);
            // }
        } else if ($human) {
            $generatedSpecificCell1 = generatingDialogueForSpecificCell($rowToDelete - 1, $col, $msg, 'human', $data[$rowToDelete - 1], $totalChars, false);
            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $rowToDelete - 1);
            // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$rowToDelete - 1], $rowToDelete - 1);
            if ($updatingCell) {
                $newRow = deletingRowOnly($storyDbConn, $chapter, $rowToDelete, false);
                $results['success'] = $updatingCell;

                echo json_encode($results);
            } else {
                echo json_encode($results);
            }
        } else {

            $newRow = deletingRowOnly($storyDbConn, $chapter, $rowToDelete, false);
        }

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



function deletingRowOnly($conn, $chapter, $row, $act)
{
    if ($act) {
        $row1 = $row;
        $row2 = $row + 1;
        $deletingRow1 = mysqli_query($conn, "DELETE FROM story_" . $chapter . " WHERE lineNumber='{$row1}'");
        $updatingLineNum1 = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber-1 WHERE lineNumber > " . (int)$row1 - 1  . " ORDER BY lineNumber ASC");

        if ($deletingRow1 && $updatingLineNum1) {
            $deletingRow2 = mysqli_query($conn, "DELETE FROM story_" . $chapter . " WHERE lineNumber='{$row1}'");
            $updatingLineNum2 = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber-1 WHERE lineNumber > " . (int)$row1 - 1  . " ORDER BY lineNumber ASC");

            if ($deletingRow2 && $updatingLineNum2) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    } else {
        $deletingRow = mysqli_query($conn, "DELETE FROM story_" . $chapter . " WHERE lineNumber='{$row}'");
        $updatingLineNum = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber-1 WHERE lineNumber > " . (int)$row - 1  . " ORDER BY lineNumber ASC");
        if ($deletingRow && $updatingLineNum) {
            return true;
        } else {
            return false;
        }
    }
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

function generatingDialogueForSpecificCell($row, $col, $dialogueText, $cat, $dataRow, $totalChars, $flag)
{


    $dataArray = array();
    for ($i = 1; $i <= (int)$totalChars; $i++) {
        if (isset($dataRow[$i]) && $i != $col && $flag) {
            $dataArray[$i] = array('charCode' => $i,  'id' =>  $dataRow[$i]['details']['id'], 'dateTime' => $dataRow[$i]['details']['dateTime'], 'comment' => $dataRow[$i]['details']['comment'], 'dialogue' => $dataRow[$i]['details']['dialogue'], 'fileName' => $dataRow[$i]['details']['fileName'], 'numOfWords' => $dataRow[$i]['details']['numOfWords'], 'cat' => $dataRow[$i]['details']['category'], 'active' => $dataRow[$i]['details']['active']);
        } else if (isset($dataRow[$i]) && $flag === false) {
            $dataArray[$i] = array('charCode' => $i,  'id' =>  $dataRow[$i]['details']['id'], 'dateTime' => $dataRow[$i]['details']['dateTime'], 'comment' => $dataRow[$i]['details']['comment'], 'dialogue' => $dataRow[$i]['details']['dialogue'], 'fileName' => $dataRow[$i]['details']['fileName'], 'numOfWords' => $dataRow[$i]['details']['numOfWords'], 'cat' => $dataRow[$i]['details']['category'], 'active' => $dataRow[$i]['details']['active']);
        }
    }

    $chars = array_keys($dataArray);
    $dialogue = '{';


    $counter = 1;
    $len = count($chars);
    foreach ($chars as $value) {
        $dialogue .= '"' . $value . '":{
            "charCode" :  ' . $value . ',
            "details" : {
              "id" : ' . $dataArray[$value]['id']  . ',
              "dateTime" : "' . $dataArray[$value]['dateTime']  . '",
              "comment" : "' . $dataArray[$value]['comment'] . '",
              "dialogue" : "' . $dataArray[$value]['dialogue']  . '",
              "fileName" : "' . $dataArray[$value]['fileName'] . '",
              "numOfWords" : ' . $dataArray[$value]['numOfWords']  . ',
              "category" : "' . $dataArray[$value]['cat'] . '",
              "active" : ' . $dataArray[$value]['active'] . '
            }
          }';
        if ($counter !== $len) {
            $dialogue .= ",";
        } else if ($counter === $len && $flag) {
            $dialogue .= ",";
        }
        $counter = $counter + 1;        // if ($value !== array_key_last($chars)) {
        // }
    }


    if ($flag) {
        $id = rand(time(), 100000000);
        $dateTime = (new DateTime())->format("Y-m-d H:i:s");
        $numOfWords = str_word_count($dialogueText);
        $dialogue .= '"' . $col . '":{
          "charCode" :  ' . $col . ',
          "details" : {
            "id" : ' . $id . ',
            "dateTime" : "' . $dateTime . '",
            "comment" : null,
            "dialogue" : "' . $dialogueText . '",
            "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
            "numOfWords" : ' . $numOfWords . ',
            "category" : "' . $cat . '",
            "active" : ' . $col . '
          }
        }}';
    } else {
        $dialogue .= "}";
    }


    return $dialogue;
}
function updatingCellWithOutEncode($conn, $chapter, $dialogue, $row)
{
    // $updatingCell = "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'";
    $updatingCell = mysqli_query($conn, "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'");
    if ($updatingCell) {
        return true;
    } else {
        return false;
    }
}
