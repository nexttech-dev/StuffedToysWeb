<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);

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
        $storyName = $storyData['data']['storyName'];

        include_once("../connections/storyDb.php");

        $storyDbData = fetchStoriesInfo($storyDbConn);
        $storiesInfoTable = $storyDbData['rawData'];

        if ($storyDbData['result'] == true) {
            $charDetails = getCharDetailsByUserId($storyDbData['rawData'], $userId, $aux);

            if ($charDetails['result'] == true) {
                $charCode = $charDetails['data']['charCode'];
                $charName = $charDetails['data']['charName'];
                $charColor = $charDetails['data']['charColor'];
            } else {
                $addingCharDetails = addingCharDetails($charDetails['universalData'], $userId, $storyDbConn);
                if ($addingCharDetails['result'] == true) {
                    $charCode = $addingCharDetails['data']['charCode'];
                    $charName = $addingCharDetails['data']['charName'];
                    $charColor = $addingCharDetails['data']['charColor'];
                } else {
                }
            }

            $storyDetails = fetchStory($storyDbConn);

            if ($storyDetails['result'] == true) {
                $totalMessages = $storyDetails['data']['totalNumberOfLines'];
                $totalChars = $storyDbData['data']['totalChars'];
                $userCharCode = (int)$charCode;

                $storiesInfoTable = fetchStoriesInfo($storyDbConn);
                if ($storiesInfoTable['result'] == true) {
                    $allCharDetails = getAllCharDetails($storiesInfoTable['rawData']);


                    if ($allCharDetails['result'] == true) {

                        $output = '<div class="chatSelection">';
                        for ($i = 1; $i <  (int)$totalChars; $i++) {
                            if ($i != $userCharCode) {
                                $output .= '<input type="text" name="" onkeyup="updateChar(event,this)" id="' . $allCharDetails[$i]['charName'] . '" placeholder="' . $allCharDetails[$i]['charName'] . '" style="background : ' . $allCharDetails[$i]['charColor'] . ';">';
                            }
                        }
                        $output .= '<input type="text" name="" onkeyup="updateChar(event,this)" id="' . $allCharDetails[$userCharCode]['charName'] . '" placeholder="' . $allCharDetails[$userCharCode]['charName'] . '" style="background : ' . $allCharDetails[$userCharCode]['charColor'] . ';">';
                        $output .= '</div>';

                        $onlyMsgs = array();
                        $msgsWithIndex = array();

                        while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {

                            if ($msgRow['active'] == (string)$userCharCode) {
                                array_push($onlyMsgs, array($msgRow[$userCharCode . '_msgId'] => $msgRow[$userCharCode . '_message']));
                                array_push($msgsWithIndex, array("msgId" => $msgRow[$userCharCode . '_msgId'], "msg" =>  $msgRow[$userCharCode . '_message']));
                                $output .= '<div class="chatRow msg' . $msgRow[$userCharCode . '_msgId'] . '">
                                            <div class="chat chatRight">
                                                <div class="chatDetails" style="background:' . $charColor . '">
                                                    <div class="msgId' . $msgRow[$userCharCode . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1" type="text" id="chatId' .  $msgRow[$userCharCode . '_msgId']  . '">' . $msgRow[$userCharCode . '_message'] . '</textarea>
                                                        <button class="delete delId' .  $msgRow[$userCharCode . '_msgId']  . '" id="' .  $msgRow[$userCharCode . '_msgId']  . '" onclick="delMsg(this)"><i class="fa-solid fa-trash"></i></button>
                                                    </div>
                                                    <div class="userInfo">Char' . $userCharCode . ' : ' . $msgRow[$userCharCode . '_act'] . ' : ' . $msgRow[$userCharCode . '_time'] . '</div>
                                                </div>
                                            </div>
                                        </div>';
                            } else {
                                array_push($onlyMsgs, array($msgRow[$userCharCode . '_msgId'] => $msgRow[$userCharCode . '_message']));
                                array_push($msgsWithIndex, array("msgId" => $msgRow[$userCharCode . '_msgId'], "msg" =>  $msgRow[$userCharCode . '_message']));

                                $outgoing = '<div class="chat chatRight greyedOut">
                                                    <div class="chatDetails">
                                                        <div class="msgId' . $msgRow[$userCharCode . '_msgId'] . ' textAndOptions">
                                                            <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1 " type="text" id="chatId' .  $msgRow[$userCharCode . '_msgId']  . '"></textarea>
                                                        </div>
                                                        <div class="userInfo">Char' . $userCharCode . ' : ' . $msgRow[$userCharCode . '_act'] . ' : ' . $msgRow[$userCharCode . '_time'] . '</div>
                                                    </div>
                                                </div>';

                                array_push($onlyMsgs, array($msgRow[$msgRow['active'] . '_msgId'] => $msgRow[$msgRow['active'] . '_message']));
                                array_push($msgsWithIndex, array("msgId" => $msgRow[$msgRow['active'] . '_msgId'], "msg" =>  $msgRow[$msgRow['active'] . '_message']));

                                $incoming = '<div class="chat chatLeft">
                                                    <div class="chatDetails" style="background : ' . $allCharDetails[$msgRow['active']]['charColor'] . ';">
                                                        <div class="msgId' . $msgRow[$msgRow['active'] . '_msgId'] . ' textAndOptions">
                                                            <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextLeft chatText1" type="text" id="chatId' .  $msgRow[$msgRow['active'] . '_msgId']  . '">' . $msgRow[$msgRow['active'] . '_message'] . '</textarea>
                                                            <button class="delete delId' .  $msgRow[$msgRow['active'] . '_msgId']  . '" id="' .  $msgRow[$msgRow['active'] . '_msgId']  . '" onclick="delMsg(this)"><i class="fa-solid fa-trash"></i></button>
                                                        </div>
                                                       
                                                        <div class="userInfo">Char' . $imsgRow['active'] . ' : ' . $msgRow[$msgRow['active'] . '_act'] . ' : ' . $msgRow[$msgRow['active'] . '_time'] . '</div>
                                                    </div>
                                                </div>
                                    ';
                                $output .= '<div class="chatRow msg' . $msgRow[$userCharCode . '_msgId'] . '">' . $incoming . $outgoing . '</div>';
                            }

                            // if ($count == $totalMessages) {
                            //     echo json_encode(array('msg' => $output, "result" => true, 'error' => false, "onlyMsgs" => $onlyMsgs, "msgsWithIndex" => $msgsWithIndex, 'storyName' => $storyName));
                            // }
                        }
                        echo json_encode(array('msg' => $output, "result" => true, 'error' => false, "onlyMsgs" => $onlyMsgs, "msgsWithIndex" => $msgsWithIndex, 'storyName' => $storyName));

                        // echo json_encode($results);
                    } else {
                    }
                } else {
                }
            } else {
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
