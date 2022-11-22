<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
include_once("../php/storyDb.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");


if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];

    include_once("../connections/userDb.php");

    $accessingStoryDB = mysqli_query($userDbConn, "SELECT * FROM stories WHERE storyId = {$storyId}");

    if (mysqli_num_rows($accessingStoryDB) == 1) {
        // echo json_encode(array('userID' => $userId, "userName" => $userName));
        $story = mysqli_fetch_assoc($accessingStoryDB);
        $storyDbName = $story['storyDbName'];
        $storyName = $story['storyName'];
        $storyChars = $story['storyChars'];

        include_once("../connections/storyDb.php");

        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");

        $char = null;
        $charName = null;
        $charColor = null;
        if (mysqli_num_rows($storiesInfoTable) == 1) {
            $storiesInfoRowForUsingData =  mysqli_fetch_assoc($storiesInfoTable);
            $newColor = '#' . random_color();
            $storyNameSI = $storiesInfoRowForUsingData['storyName'];
            $storyCharsSI = $storiesInfoRowForUsingData['storyChars'];
            $storyIdSI = (int)$storiesInfoRowForUsingData['storyId'];
            $startedBySI = $storiesInfoRowForUsingData['startedBy'];
            $dateSI = $storiesInfoRowForUsingData['date'];
            $timeSI = $storiesInfoRowForUsingData['time'];

            $updatingStoriesInfo = mysqli_query($storyDbConn, "INSERT INTO `storiesInfo` ( `storyName`, `storyChars`, `storyId`, `startedBy`, `date`, `time`, `charCode`, `charName`, `charController`, `color`, `status`) VALUES ('{$storyNameSI}','{$storyCharsSI}','{$storyIdSI}','{$startedBySI}','{$dateSI}','{$timeSI}','1','Char1','{$userId}','{$newColor}',1)");
            if ($updatingStoriesInfo) {
                $char = '1';
                $charName = 'Char1';
                $charColor = $newColor;
            }
        } else {
            $charCode = (string)mysqli_num_rows($storiesInfoTable);
            $charDetails = getCharDetails($storiesInfoTable, $userId);
            if ($charDetails['result'] == false) {
                $color = '#' . random_color();
                $storyNameSI = $charDetails['storyName'];
                $storyCharsSI = $charDetails['storyChars'];
                $storyIdSI = (int)$charDetails['storyId'];
                $startedBySI = $charDetails['startedBy'];
                $dateSI = $charDetails['date'];
                $timeSI = $charDetails['time'];
                $updatingStoriesInfo = mysqli_query($storyDbConn, "INSERT INTO storiesInfo (storyName,storyChars,storyId,startedBy,date,time,charCode,charId,charName,charController,color,status) VALUES ('{$storyNameSI}','{$storyCharsSI}','{$storyIdSI}','{$startedBySI}','{$dateSI}','{$timeSI}','{$charCode}','','Char" . (string)$charCode . "','{$userId}','{$color}',1)");
                if ($updatingStoriesInfo) {
                    $char = (string)$charCode;
                    $charName = 'Char' . $charCode;
                    $charColor = $color;
                } else {
                    echo "Fuck Off";
                }
            } else {
                $char = $charDetails['char'];
                $charName = $charDetails['charName'];
                $charColor = $charDetails['charColor'];
            }
        }


        $storyDetails = mysqli_query($storyDbConn, "SELECT * FROM story");
        if ($storyDetails) {
            if (mysqli_num_rows($storyDetails) >> 0) {
                $totalChars = (int)$storyChars;
                $userChar = (int)$char;
                $count = 0;
                $totalMessages =  mysqli_num_rows($storyDetails);


                $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");
                $totalRawChars =  mysqli_num_rows($storiesInfoTable);

                if ($storiesInfoTable) {
                    $output = '<div class="chatSelection">';
                    $allCharDetails = getAllCharDetails($storiesInfoTable, $totalRawChars);
                    if ($allCharDetails['result'] == true) {
                        for ($i = 1; $i <  (int)$totalRawChars; $i++) {
                            if ($i != $userChar) {
                                $output .= '<input type="text" name="" onkeyup="updateChar(event,this)" id="' . $allCharDetails[$i]['charName'] . '" placeholder="' . $allCharDetails[$i]['charName'] . '" style="background : ' . $allCharDetails[$i]['charColor'] . ';">';
                            }
                        }
                        $output .= '<input type="text" name="" onkeyup="updateChar(event,this)" id="' . $allCharDetails[$userChar]['charName'] . '" placeholder="' . $allCharDetails[$userChar]['charName'] . '" style="background : ' . $allCharDetails[$userChar]['charColor'] . ';">';
                        $output .= '</div>';
                    }

                    $onlyMsgs = array();
                    $msgsWithIndex = array();
                    while ($msgRow = mysqli_fetch_assoc($storyDetails)) {
                        $count++;
                        // echo json_encode(array('userID' => mysqli_fetch_assoc($storyDetails), "userName" => $userName));
                        if ($msgRow[(string)$userChar . '_msgCat'] == "1") {
                            array_push($onlyMsgs, array($msgRow[$userChar . '_msgId'] => $msgRow[$userChar . '_message']));
                            array_push($msgsWithIndex, array("msgId" => $msgRow[$userChar . '_msgId'], "msg" =>  $msgRow[$userChar . '_message']));

                            $output .= '<div class="chatRow msg' . $msgRow[$userChar . '_msgId'] . '">
                                        <div class="chat chatRight">
                                            <div class="chatDetails">
                                                <div class="msgId' . $msgRow[$userChar . '_msgId'] . ' textAndOptions">
                                                    <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1" type="text" id="chatId' .  $msgRow[$userChar . '_msgId']  . '">' . $msgRow[$userChar . '_message'] . '</textarea>
                                                </div>
                                                <div class="menu__item--meatball rightOpt" tabindex="5" onclick="moreOptions(this)">
                                                    <div class="circle"></div>
                                                    <div class="circle"></div>
                                                    <div class="circle"></div>
                                                </div>
                                                <div class="moreOptions" style="display: none;">
                                                    <div>
                                                        <button class="delId' .  $msgRow[$userChar . '_msgId']  . '" id="' .  $msgRow[$userChar . '_msgId']  . '" onclick="delMsg(this)">Delete</button>
                                                    </div>
                                                    <div>
                                                        <button>Record</button>
                                                    </div>
                                                </div>
                                                <div class="userName">Char' . $userChar . ' : ' . $msgRow[$userChar . '_act'] . ' : ' . $msgRow[$userChar . '_time'] . '</div>
                                            </div>
                                        </div>
                                    </div>';
                        } else {
                            for ($i = 1; $i <= $totalChars; $i++) {
                                $colName = (string)$i . '_msgCat';
                                if ($i == $userChar) {
                                    array_push($onlyMsgs, array($msgRow[$i . '_msgId'] => $msgRow[$i . '_message']));
                                    array_push($msgsWithIndex, array("msgId" => $msgRow[$userChar . '_msgId'], "msg" =>  $msgRow[$userChar . '_message']));

                                    $outgoing = '<div class="chat chatRight greyedOut">
                                                <div class="chatDetails">
                                                    <div class="msgId' . $msgRow[$i . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextRight chatText1 " type="text" placeholder="' . $msgRow[$i . '_message'] . ' "id="chatId' .  $msgRow[$i . '_msgId']  . '"></textarea>
                                                    </div>
                                                    <div class="menu__item--meatball rightOpt" tabindex="5" onclick="moreOptions(this)">
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                    </div>
                                                    <div class="moreOptions" style="display: none;">
                                                        <div>
                                                            <button class="delId' .  $msgRow[$i . '_msgId']  . '" id="' .  $msgRow[$i . '_msgId']  . '" onclick="delMsg(this)">Delete</button>
                                                        </div>
                                                        <div>
                                                            <button>Record</button>
                                                        </div>
                                                    </div>
                                                    <div class="userName">Char' . $i . ' : ' . $msgRow[$i . '_act'] . ' : ' . $msgRow[$i . '_time'] . '</div>
                                                </div>
                                            </div>  
                            ';
                                } else {
                                    array_push($onlyMsgs, array($msgRow[$i . '_msgId'] => $msgRow[$i . '_message']));
                                    array_push($msgsWithIndex, array("msgId" => $msgRow[$userChar . '_msgId'], "msg" =>  $msgRow[$userChar . '_message']));

                                    $incoming = '<div class="chat chatLeft">
                                                <div class="chatDetails" style="background : ' . $storiesInfoRow[$i . '_color'] . ';">
                                                    <div class="msgId' . $msgRow[$i . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this,event)" class="chatText chatTextLeft chatText1" type="text" id="chatId' .  $msgRow[$i . '_msgId']  . '">' . $msgRow[$i . '_message'] . '</textarea>
                                                    </div>
                                                    <div class="menu__item--meatball leftOpt" tabindex="5" onclick="moreOptions(this)">
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                    </div>
                                                    <div class="moreOptions" style="display: none;">
                                                        <div>
                                                            <button class="delId' .  $msgRow[$i . '_msgId']  . '" id="' .  $msgRow[$i . '_msgId']  . '" onclick="delMsg(this)">Delete</button>
                                                        </div>
                                                        <div>
                                                            <button>Record</button>
                                                        </div>
                                                    </div>
                                                    <div class="senderName">Char' . $i . ' : ' . $msgRow[$i . '_act'] . ' : ' . $msgRow[$i . '_time'] . '</div>
                                                </div>
                                            </div>
                                ';
                                }

                                if ($i == $totalChars) {

                                    $output .= '<div class="chatRow msg' . $msgRow[$userChar . '_msgId'] . '">' . $incoming . $outgoing . '</div>';
                                }
                            }
                        }

                        if ($count == $totalMessages) {
                            echo json_encode(array('msg' => $output, "result" => true, 'error' => false, "onlyMsgs" => $onlyMsgs, "msgsWithIndex" => $msgsWithIndex, 'storyName' => $storyName));
                        }
                    }
                } else {
                }
            } else {
                echo json_encode(array('msg' => '<div class="text">No dialogues are available. Once you send, they will appear here.</div>', "result" => false, 'error' => true, 'storyName' => $storyName));
            }
        } else {
            echo json_encode(array('msg' => 'failed to access story', "result" => false, 'error' => true));
        }
    } else {
        // $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';

        echo json_encode(array('msg' => '', "result" => false, 'error' => true));
    }
} else {
    echo json_encode(array('msg' => 'No user found against this cid', "result" => false, 'error' => true));
}

function random_color_part()
{
    return str_pad(dechex(mt_rand(0, 255)), 2, '0', STR_PAD_LEFT);
}
function random_color()
{
    return random_color_part() . random_color_part() . random_color_part();
}
function assigningCharDetails()
{
}
function getCharDetails($storiesInfoTable, $userId)
{
    $result = array('result' => false, 'char' => null, 'charName' => null, 'charColor' => null);
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        $result['storyName'] = $storiesInfoRow['storyName'];
        $result['storyChars'] = $storiesInfoRow['storyChars'];
        $result['storyId'] = $storiesInfoRow['storyId'];
        $result['startedBy'] = $storiesInfoRow['startedBy'];
        $result['date'] = $storiesInfoRow['date'];
        $result['time'] = $storiesInfoRow['time'];
        if ($storiesInfoRow['charController'] == $userId) {
            $result['result'] = true;
            $result['char'] = $storiesInfoRow['charCode'];
            $result['charName'] = $storiesInfoRow['charName'];
            $result['charColor'] = $storiesInfoRow['color'];
            break;
        }
    }
    return $result;
}

// function getAllCharDetails($storiesInfoTable, $totalChars)
// {
//     $result = array('result' => false);
//     $charCode = 1;
//     while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
//         if ($storiesInfoRow['charCode'] == (string)$charCode) {
//             $result['result'] = true;
//             $result[$charCode] = array('charCode' => $storiesInfoRow['charCode'], 'charName' => $storiesInfoRow['charName'], 'charColor' => $storiesInfoRow['color']);
//             $charCode = $charCode + 1;
//         }
//     }
//     return $result;
// }
