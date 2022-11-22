<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
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
                $charName = $charDetails['data']['charName'];
                $charCode = $charDetails['data']['charCode'];
                $charColor = $charDetails['data']['charColor'];

                $storyDetails = fetchStory($storyDbConn);

                if ($storyDetails['result'] == true) {
                    $storiesInfoTable = fetchStoriesInfo($storyDbConn);
                    $allCharDetails = getAllCharDetails($storiesInfoTable['rawData']);
                    $lineNumber = $storyDetails['data']['totalNumberOfLines'];
                    $date = date("d.m.Y");
                    $time = time();
                    $comment = "None";
                    $totalChars = (int)$totalChars;
                    $userCharCode = (int)$charCode;

                    $output = "";
                    $onlyMsgs = array();
                    // $msgRow = mysqli_fetch_assoc($storyDetails);
                    mysqli_data_seek($storyDetails['rawData'], $lineNumber - 1);
                    $msgRow = mysqli_fetch_array($storyDetails['rawData']);

                    if ($msgRow['active'] == (string)$userCharCode) {
                        array_push($onlyMsgs, array($msgRow[$userCharCode . '_msgId'] => $msgRow[$userCharCode . '_message']));
                        $output .= '<div class="chatRow">
                                            <div class="chat chatRight">
                                                <div class="chatDetails"  style="background:' . $charColor . '">
                                                    <div class="msgId' . $msgRow[$userCharCode . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1" type="text">' . $msgRow[$userCharCode . '_message'] . '</textarea>
                                                        <button class="delete delId' .  $msgRow[$userCharCode . '_msgId']  . '" id="' .  $msgRow[$userCharCode . '_msgId']  . '" onclick="delMsg(this)"><i class="fa-solid fa-trash"></i></button>

                                                        </div>
                                                    <div class="userInfo">Char' . $userCharCode . ' : ' . $msgRow[$userCharCode . '_act'] . ' : ' . $msgRow[$userCharCode . '_time'] . '</div>
                                                </div>
                                            </div>
                                        </div>';
                    } else {
                        array_push($onlyMsgs, array($msgRow[$userCharCode . '_msgId'] => $msgRow[$userCharCode . '_message']));
                        $outgoing = '<div class="chat chatRight greyedOut">
                                                    <div class="chatDetails">
                                                        <div class="msgId' . $msgRow[$userCharCode . '_msgId'] . ' textAndOptions">
                                                            <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1 " type="text"></textarea>
                                                        </div>

                                                        <div class="userInfo">Char' . $userCharCode . ' : ' . $msgRow[$userCharCode . '_act'] . ' : ' . $msgRow[$userCharCode . '_time'] . '</div>
                                                    </div>
                                                </div>  
                                ';
                        array_push($onlyMsgs, array($msgRow[$msgRow['active']  . '_msgId'] => $msgRow[$msgRow['active']  . '_message']));
                        $incoming = '<div class="chat chatLeft">
                                                    <div class="chatDetails" style="background : ' . $allCharDetails[$msgRow['active']]['charColor'] . ';">
                                                        <div class="msgId' . $msgRow[$msgRow['active']  . '_msgId'] . ' textAndOptions">
                                                            <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextLeft chatText1" type="text">' . $msgRow[$msgRow['active']  . '_message'] . '</textarea>
                                                            <button class="delete delId' .  $msgRow[$msgRow['active'] . '_msgId']  . '" id="' .  $msgRow[$msgRow['active'] . '_msgId']  . '" onclick="delMsg(this)"><i class="fa-solid fa-trash"></i></button>
                                                        </div>
                                            
                                                        <div class="userInfo">Char' . $msgRow['active']  . ' : ' . $msgRow[$msgRow['active']  . '_act'] . ' : ' . $msgRow[$msgRow['active']  . '_time'] . '</div>
                                                    </div>
                                                </div>
                                    ';

                        $output .= '<div class="chatRow">' . $incoming . $outgoing . '</div>';
                    }
                    echo json_encode(array('userID' => $output, "onlyMsgs" => $onlyMsgs));
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
