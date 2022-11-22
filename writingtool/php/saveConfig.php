<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];

$_POST = json_decode(file_get_contents('php://input'), true);
$arr = [];
$arr[sizeof($arr)] = $_POST;
header("Content-Type: application/json");
// echo json_encode($arr[0]);

$storyId = $arr[0]['storyId'];
$characters = $arr[0]['totalChars'];
$chapter = $arr[0]['chapter'];
$typing = $arr[0]['typing'];
$pointer = $arr[0]['pointer'];
$extendedView = $arr[0]['extended'];

$pointer = filter_var($pointer, FILTER_VALIDATE_BOOLEAN);
$typing = filter_var($typing, FILTER_VALIDATE_BOOLEAN);
$extendedView = filter_var($extendedView, FILTER_VALIDATE_BOOLEAN);



$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);


$colorPalette = array('#340459' => '#96A600', '#200563' => '#B0A200', '#01004D' => '#997E00', '#051C63' => '#B07F00', '#043059' => '#A66700', '#5E0505' => '#00AB30', '#690535' => '#00B502', '#520051' => '#359E00', '#4E0569' => '#7DB500', '#2E055E' => '#ABA900', '#872C07' => '#078772', '#911B07' => '#079157', '#7B0000' => '#007A23', '#91074C' => '#079107', '#870787' => '#348707', '#A64F00' => '#0084A6', '#B03A00' => '#00B0A4', '#9A2107' => '#089965', '#B00700' => '#00B03D', '#A6003E' => '#00A60D', '#A88400' => '#000FA8', '#B37A00' => '#003FB3', '#9C5E08' => '#08639C', '#B34E00' => '#009EB3', '#A83100' => '#00A88C');

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
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM charInfo");


        $charInfo = array();
        while ($charDetails = mysqli_fetch_assoc($storiesInfoTable)) {
            $charInfo[$charDetails['charCode']] = json_decode($charDetails['charDetails'], true);
        }
        if ($storiesInfoTable) {
            for ($i = 1; $i <= (int)$characters; $i++) {

                $charInfo[$i]['charColor'] = $arr[0][$i]['charColor'];
                // $charInfo[$i]['fontStyle']['color']['act'] =  $colorPalette[$arr[0][$i]['charColor']];
                $charInfo[$i]['fontStyle']['size'] =  $arr[0][$i]['fontSize'];
                $charInfo[$i]['fontStyle']['bold'] =  $arr[0][$i]['bold'];
                $charInfo[$i]['fontStyle']['italic'] =  $arr[0][$i]['italic'];
                $charInfo[$i]['fontStyle']['underline'] =  $arr[0][$i]['underline'];
                $charInfo[$i]['fontStyle']['family'] =  $arr[0][$i]['fontFamily'];

                $jsonChar = json_encode($charInfo[$i]);
                $updatingColors = mysqli_query($storyDbConn, "UPDATE charInfo SET `charDetails`='{$jsonChar}' WHERE `charCode`= {$i}");
            }

            // $autoFeed = $autoFeed;
            $pointer = (int)$pointer;
            $typing = (int)$typing;
            $extendedView = (int)$extendedView;

            $configDetails = '{"autoFeed": false,"pointer": ' . $pointer . ',"typing": ' . $typing . ',"extendedView": ' . $extendedView . '}';
            // $configDetails = json_encode($configDetails);
            // $sql = "UPDATE config SET `configDetails`='{$configDetails}' WHERE `configName` = 'sheetConfigs'";
            $updatingConfigs =  mysqli_query($storyDbConn, "UPDATE config SET `configDetails`='{$configDetails}' WHERE `configName` = 'sheetConfigs'");


            if ($updatingColors && $updatingConfigs) {
                $results['result'] = true;
                $results['msg'] = "Configs Updated!";
                $results['errorCode'] = "AAAX003";
                echo json_encode($results);
            } else {
                $results['result'] = false;
                $results['msg'] = "Charactor already exisits!";
                $results['errorCode'] = "AAAX003";
                // $results['data'] = $sql;
                echo json_encode($results);
            }
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "result" => false));
    }
} else {
    $results['result'] = false;
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
