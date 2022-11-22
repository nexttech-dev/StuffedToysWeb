<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);

// $char = mysqli_real_escape_string($conn, $_POST['char']);

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
        $storyChars = $story['storyChars'];

        include_once("../connections/storyDb.php");
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");
        if ($storiesInfoTable) {
            $charDetails = getCharDetails($storiesInfoTable, $userId);
            if ($charDetails['result'] == true) {
                $char = $charDetails['char'];
                $charName = $charDetails['charName'];
                $charColor = $charDetails['charColor'];
                $i =  (int)$storyChars;
            } else {
            }
        } else {
        }

        $storyDetails = mysqli_query($storyDbConn, "SELECT * FROM story");
        if ($storyDetails) {
            if (mysqli_num_rows($storyDetails) >= 0) {
                $totalChars = (int)$storyChars;
                $userChar = (int)$char;
                $totalMessages =  mysqli_num_rows($storyDetails);
                $output = "";
                $onlyMsgs = array();
                // $msgRow = mysqli_fetch_assoc($storyDetails);
                mysqli_data_seek($storyDetails, $totalMessages - 1);
                $msgRow = mysqli_fetch_array($storyDetails);

                // echo json_encode(array('userID' => mysqli_fetch_assoc($storyDetails), "userName" => $userName));
                if ($msgRow[(string)$userChar . '_msgCat'] == "1") {
                    array_push($onlyMsgs, array($msgRow[$userChar . '_msgId'] => $msgRow[$userChar . '_message']));
                    $output .= '<div class="chatRow">
                                        <div class="chat chatRight">
                                            <div class="chatDetails">
                                                <div class="msgId' . $msgRow[$userChar . '_msgId'] . ' textAndOptions">
                                                    <textarea onkeyup="textAreaAdjust(this)" class="chatText chatTextRight chatText1" type="text">' . $msgRow[$userChar . '_message'] . '</textarea>
                                                </div>
                                                <div class="menu__item--meatball rightOpt" tabindex="5" onclick="moreOptions(this)">
                                                    <div class="circle"></div>
                                                    <div class="circle"></div>
                                                    <div class="circle"></div>
                                                </div>
                                                <div class="moreOptions" style="display: none;">
                                                    <div>
                                                        <button>Delete</button>
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
                            $outgoing = '<div class="chat chatRight greyedOut">
                                                <div class="chatDetails">
                                                    <div class="msgId' . $msgRow[$i . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this)" class="chatText chatTextRight chatText1 " type="text" placeholder="' . $msgRow[$i . '_message'] . '"></textarea>
                                                    </div>
                                                    <div class="menu__item--meatball rightOpt" tabindex="5" onclick="moreOptions(this)">
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                    </div>
                                                    <div class="moreOptions" style="display: none;">
                                                        <div>
                                                            <button>Delete</button>
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
                            $incoming = '<div class="chat chatLeft">
                                                <div class="chatDetails">
                                                    <div class="msgId' . $msgRow[$i . '_msgId'] . ' textAndOptions">
                                                        <textarea onkeyup="textAreaAdjust(this)" class="chatText chatTextLeft chatText1" type="text">' . $msgRow[$i . '_message'] . '</textarea>
                                                    </div>
                                                    <div class="menu__item--meatball leftOpt" tabindex="5" onclick="moreOptions(this)">
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                        <div class="circle"></div>
                                                    </div>
                                                    <div class="moreOptions" style="display: none;">
                                                        <div>
                                                            <button>Delete</button>
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

                            $output .= '<div class="chatRow">' . $incoming . $outgoing . '</div>';
                        }
                    }
                }

                echo json_encode(array('userID' => $output, "onlyMsgs" => $onlyMsgs));

                //     } else {
                //         echo json_encode(array('error' => "No data in requests incomming found are found or more than one item has been found!"));

            } else {
                echo json_encode(array('error' => "failed to access requestsIncomming"));
            }
        } else {
            echo json_encode(array('error' => "failed to access story"));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
function getCharDetails($storiesInfoTable, $userId)
{
    $result = array('result' => false, 'char' => null, 'charName' => null, 'charColor' => null);
    $charCode = 1;
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
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
