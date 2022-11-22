<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");

$cid = $_SESSION['cid'];
$send =  mysqli_real_escape_string($conn, $_POST['sendAs']);
$msg = mysqli_real_escape_string($conn, $_POST['msg']);
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$autoFeed = mysqli_real_escape_string($conn, $_POST['autoFeed']);
$rowNumber = mysqli_real_escape_string($conn, $_POST['rowId']);

$autoFeed = filter_var($autoFeed, FILTER_VALIDATE_BOOLEAN);
$autoFeed = (int)$autoFeed;



if ($send == "sa") {
    $msg = "~~" . $msg;
} else if ($send == "aux") {
    $msg = "*" . $msg;
}

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

        $storyDbData = fetchStoriesInfo($storyDbConn);
        $chapter = tableName($chapter, $storyDbConn);

        if ($storyDbData['result'] == true) {

            $totalChars = $storyDbData['totalChars'];

            $storyDetails = fetchStory($storyDbConn, $chapter);

            if ($storyDetails['result'] == true) {
                $date = date("d.m.Y");
                $time = time();
                $comment = null;
                $totalChars = (int)$totalChars;
                $userCharCode = (int)$charCode;

                if ($autoFeed) {
                    $lineNumber = $storyDetails['data']['totalNumberOfLines'] + 1;
                } else {
                    $lineNumber = $storyDetails['data']['totalNumberOfLines'] + 1;
                    $totalEmptyLines = (int)$rowNumber - (int)$lineNumber;
                    $emptyLineNum = $lineNumber;
                    $lineNumber = $rowNumber;

                    for ($i = 0; $i < $totalEmptyLines; $i++) {
                        $query = makingQueryForInserting($totalChars, null, $userCharCode, $date, $time, $comment, null, $emptyLineNum, null, null);
                        $sqlQuery = "INSERT INTO story_" . $chapter . " ({$query['heading']}) VALUES ({$query['values']})";
                        $insertingMsg = mysqli_query($storyDbConn, $sqlQuery);
                        $emptyLineNum = (string)((int)$emptyLineNum + 1);
                    }
                }

                if ($send == "act") {
                    $send_1 = $send . "_1";
                    $defaultMsg_1 = "";
                    $secondrySend = null;
                    $query_1 = makingQueryForInserting($totalChars, $send_1, $userCharCode, $date, $time, $comment, $msg, $lineNumber, $defaultMsg_1, $secondrySend);

                    $sqlQuery_1 = "INSERT INTO story_" . $chapter . " ({$query_1['heading']}) VALUES ({$query_1['values']})";
                    $insertingMsg_1 = mysqli_query($storyDbConn, $sqlQuery_1);

                    $send_2 = $send . "_2";
                    $defaultMsg_2 = $msg;
                    $secondrySend = $send_2;
                    $lineNumber_2 = $lineNumber + 1;
                    $query_2 = makingQueryForInserting($totalChars, $send_2, $userCharCode, $date, $time, $comment, $msg, $lineNumber_2, $defaultMsg_2, $secondrySend);

                    $sqlQuery_2 = "INSERT INTO story_" . $chapter . " ({$query_2['heading']}) VALUES ({$query_2['values']})";
                    $insertingMsg_2 = mysqli_query($storyDbConn, $sqlQuery_2);

                    if ($insertingMsg_2 && $insertingMsg_1) {
                        $results['result'] = true;
                        $results['msg'] = "Message Sent!";
                        $results['error'] = false;
                        echo json_encode($results);
                    }
                } else if ($send == "dir" || $send == "iv" || $send == "oth") {
                    $send = $send . '_1';
                    $defaultMsg = "";
                    $secondrySend = null;
                    $query = makingQueryForInserting($totalChars, $send, $userCharCode, $date, $time, $comment, $msg, $lineNumber, $defaultMsg, $secondrySend);

                    $sqlQuery = "INSERT INTO story_" . $chapter . " ({$query['heading']}) VALUES ({$query['values']})";
                    $insertingMsg = mysqli_query($storyDbConn, $sqlQuery);

                    if ($insertingMsg) {
                        $results['result'] = true;
                        $results['msg'] = "Message Sent!";
                        $results['error'] = false;
                        echo json_encode($results);
                    }
                } else if ($send == "sa" || $send == "aux") {
                    $send = $send . '_1';
                    $defaultMsg = $msg;
                    $secondrySend = $send;
                    $query = makingQueryForInserting($totalChars, $send, $userCharCode, $date, $time, $comment, $msg, $lineNumber, $defaultMsg, $secondrySend);


                    $sqlQuery = "INSERT INTO story_" . $chapter . " ({$query['heading']}) VALUES ({$query['values']})";
                    $insertingMsg = mysqli_query($storyDbConn, $sqlQuery);

                    if ($insertingMsg) {
                        $results['result'] = true;
                        $results['msg'] = "Message Sent!";
                        $results['error'] = false;
                        echo json_encode($results);
                    }
                }
            } else {
                $results['msg'] = $storyDetails['msg'];
                $results['errorCode'] = $storyDetails['errorCode'];
                echo json_encode($results);
            }
        } else {
            $results['msg'] = $storyDbData['msg'];
            $results['errorCode'] = $storyDbData['errorCode'];
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



function makingQueryForInserting($totalChars, $send, $userCharCode, $date, $time, $comment, $msg, $lineNumber, $defaultMsg, $secondrySend)
{
    $headings = "id,lineNumber,lineCode,";
    $values = "NULL," . (int) $lineNumber . "," . rand(time(), 100000000) . ",";
    for ($i = 1; $i <= $totalChars; $i++) {
        if ($i == $userCharCode) {
            $headings .= $i . "_msgId," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
            $values .=  rand(time(), 100000000) . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $msg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3'," . (string)str_word_count($msg) . "," . 1 . ",'" . $send . "',1";
        } else {
            $headings .= $i . "_msgId," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
            $values .=  rand(time(), 100000000) . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $defaultMsg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3',0,"  . 0 . ",'" . $secondrySend  . "',0";
        }
        if ($i == $totalChars) {
            $headings .= ",active,status";
            $values .= "," . (string)$userCharCode . "," . 1;
            return array('heading' => $headings, 'values' => $values);
        } else {
            $headings .= ",";
            $values .= ",";
        }
    }
}












// for ($i = 1; $i <= $totalChars; $i++) {
//     if ($send == "act") {
//         $headingsAct .= $i . "_msgId," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
//         $valuesAct .=  rand(time(), 100000000) . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $msg . "'" . ",'" . (string)((int)$lineNumber + 1) . '_Char_' . $i . ".mp3'," . (string)str_word_count($msg) . "," . 1 . ",'" . $send . "_2" . "',1";
//     }
//     if ($i == $userCharCode) {
//         $headings .= $i . "_msgId," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
//         $values .=  rand(time(), 100000000) . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $msg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3'," . (string)str_word_count($msg) . "," . 1 . ",'" . $send . "_1" . "',1";
//     } else {
//         $defaultMsg = "";
//         $headings .= $i . "_msgId," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
//         $values .=  rand(time(), 100000000) . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $defaultMsg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3',0,"  . 0 . ",'" . $send   . "_1" . "',0";
//     }

//     if ($i == $totalChars) {
//         $headings .= ",active,status";
//         $values .= "," . (string)$userCharCode . "," . 1;
//         $sqlQuery = "INSERT INTO story_" . $chapter . " ({$headings}) VALUES ({$values})";
//         $insertingMsg = mysqli_query($storyDbConn, $sqlQuery);

//         if ($insertingMsg) {
//             if ($send == "act") {
//                 $headingsAct .= ",active,status";
//                 $valuesAct .= "," . (string)$userCharCode . "," . 1;
//                 $sqlQueryAct = "INSERT INTO story_" . $chapter . " ({$headingsAct}) VALUES ({$valuesAct})";
//                 $insertingMsgAct = mysqli_query($storyDbConn, $sqlQueryAct);
//                 if ($insertingMsgAct) {
//                     $results['result'] = true;
//                     $results['msg'] = "Message Sent Successfully!";
//                     $results['error'] = false;
//                     echo json_encode($results);
//                 } else {
//                     $results['msg'] = "Message Sending Failed!";
//                     $results['errorCode'] = 'SMX001';
//                     echo json_encode($results);
//                 }
//             } else {
//                 $results['result'] = true;
//                 $results['msg'] = "Message Sent!";
//                 $results['error'] = false;
//                 echo json_encode($results);
//             }
//         } else {
//             $results['msg'] = 'Message Failed!';
//             $results['errorCode'] = 'SMX001';
//             echo json_encode($results);
//         }
//     } else {
//         if ($send == "act") {
//             $headingsAct .= ",";
//             $valuesAct .= ",";
//         }
//         $headings .= ",";
//         $values .= ",";
//     }
// }