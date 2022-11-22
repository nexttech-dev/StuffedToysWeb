<?php
session_start();
include_once("../connections/main.php");

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
            echo json_encode(array('success' => false, 'msg' => 'No aux found', "data" => null, 'errorCode' => null, 'error' => false));
        } else {
            $auxChars = getCharDetails($storiesInfoTable, $userId);

            if ($auxChars['result'] == true) {
                echo json_encode(array('success' => true, 'msg' => 'Aux Chars Found!', "data" => $auxChars, 'errorCode' => null, 'error' => false));
            } else {
                echo json_encode(array('success' => false, 'msg' => 'No aux found throughout table', "data" => null, 'errorCode' => null, 'error' => false));
            }
        }
    } else {
        echo json_encode(array('success' => false, 'msg' => 'Unable to access stories table', "data" => null, 'errorCode' => 'FAACX001', 'error' => true));
    }
} else {
    echo json_encode(array('success' => false, 'msg' => 'No user found against this cid', "data" => null, 'errorCode' => 'FAACX002', 'error' => true));
}


function getCharDetails($storiesInfoTable, $userId)
{
    $result = array('result' => false, 'totalAuxChars' => null);
    $count = 0;
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        if ($storiesInfoRow['aux'] == true) {
            $count = $count + 1;
            $result['result'] = true;
            $result['totalAuxChars'] = $count;
            $result[$count] = array('charCode' => $storiesInfoRow['charCode'], 'charName' => $storiesInfoRow['charName'], 'charColor' =>  $storiesInfoRow['color'], 'charController' =>  $storiesInfoRow['charController']);
        }
    }
    return $result;
}
