<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");

$cid = $_SESSION['cid'];
$send =  mysqli_real_escape_string($conn, $_POST['sendAs']);
$msg = mysqli_real_escape_string($conn, $_POST['msg']);
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);
$aux = false;

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

        if ($storyDbData['result'] == true) {
            $charDetails = getCharDetailsByUserId($storyDbData['rawData'], $userId, $aux);


            if ($charDetails['result'] == true) {
                if ($charCode == "null") {
                    $charCode = $charDetails['data']['charCode'];
                }
                $totalChars = $charDetails['data']['totalChars'];

                $storyDetails = fetchStory($storyDbConn);

                if ($storyDetails['result'] == true) {



                    $lineNumber = $storyDetails['data']['totalNumberOfLines'];
                    $date = date("d.m.Y");
                    $time = time();
                    $comment = "None";
                    $totalChars = (int)$totalChars;
                    $userCharCode = (int)$charCode;

                    $headings = "id,";
                    $values = "NULL,";

                    for ($i = 1; $i <= $totalChars; $i++) {
                        if ($i == $userCharCode) {
                            $headings .= $i . "_msgId," . $i . "_lineNumber," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
                            $values .=  rand(time(), 100000000) . "," . $lineNumber . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $msg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3'," . (string)str_word_count($msg) . "," . 1 . ",'" . $send . "',1";
                        } else {
                            $defaultMsg = "Char " . $userCharCode . " is listening to his " . $send . ". You might want to add someting to listen in the meantime.";
                            $headings .= $i . "_msgId," . $i . "_lineNumber," . $i . "_date," . $i . "_time," . $i . "_comment," . $i . "_message," . $i . "_fileName," . $i . "_totalNumberOfWords," . $i . "_msgCat," . $i . "_act," . $i . "_show";
                            $values .=  rand(time(), 100000000) . "," . $lineNumber . ",'" . $date . "'," . $time . ",'" . $comment . "'," . "'" . $defaultMsg . "'" . ",'" . (string)$lineNumber . '_Char_' . $i . ".mp3',0,"  . 0 . ",'" . $send . "',0";
                        }

                        if ($i == $totalChars) {
                            $headings .= ",active,status";
                            $values .= "," . (string)$userCharCode . "," . 1;
                            $sqlQuery = "INSERT INTO story ({$headings}) VALUES ({$values})";
                            $insertingMsg = mysqli_query($storyDbConn, $sqlQuery);
                            if ($insertingMsg) {
                                $results['result'] = true;
                                $results['msg'] = "Message Sent!";
                                $results['error'] = false;
                                echo json_encode($results);
                            } else {
                                $results['msg'] = 'Message Failed!';
                                $results['errorCode'] = 'SMX001';
                                echo json_encode($results);
                            }
                        } else {
                            $headings .= ",";
                            $values .= ",";
                        }
                    }
                } else {
                    $results['msg'] = $storyDetails['msg'];
                    $results['errorCode'] = $storyDetails['errorCode'];
                    echo json_encode($results);
                }
            } else {
                $results['msg'] = $charDetails['msg'];
                $results['errorCode'] = $charDetails['errorCode'];
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
