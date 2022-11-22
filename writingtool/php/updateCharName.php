<?php


session_start();
include_once("../connections/main.php");
include_once("../php/functions/totalChars.php");
echo phpinfo();
ini_set('display_errors', '1');
$results = array();

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$charCode = mysqli_real_escape_string($conn, $_POST['charCode']);
$charNewName = mysqli_real_escape_string($conn, $_POST['charNewName']);


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
        $storyName = $story['storyName'];
        $storyChars = $story['storyChars'];

        include_once("../connections/storyDb.php");
        $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM charInfo WHERE charCode = '{$charCode}'");
        if (mysqli_num_rows($storiesInfoTable) == 1) {
            while ($charDetails = mysqli_fetch_assoc($storiesInfoTable)) {
                $charInfo[$charDetails['charCode']] = json_decode($charDetails['charDetails'], true);
                $charInfo[$charDetails['charCode']]['charName'] = $charNewName;
                $jsonChar = json_encode($charInfo[$charCode]);
                $updatingColors = mysqli_query($storyDbConn, "UPDATE charInfo SET `charDetails`='{$jsonChar}' WHERE `charCode`= {$charCode}");
            }
        }
    } else {

        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    $results['success'] = false;
    $results['msg'] = "No user or more than one user found!";
    $results['errorCode'] = "AAAX001";
    $results['data'] = null;
    echo json_encode($results);
}

function random_color()
{
    $colorPalette = array('#340459' => '#96A600', '#200563' => '#B0A200', '#01004D' => '#997E00', '#051C63' => '#B07F00', '#043059' => '#A66700', '#5E0505' => '#00AB30', '#690535' => '#00B502', '#520051' => '#359E00', '#4E0569' => '#7DB500', '#2E055E' => '#ABA900', '#872C07' => '#078772', '#911B07' => '#079157', '#7B0000' => '#007A23', '#91074C' => '#079107', '#870787' => '#348707', '#A64F00' => '#0084A6', '#B03A00' => '#00B0A4', '#9A2107' => '#089965', '#B00700' => '#00B03D', '#A6003E' => '#00A60D', '#A88400' => '#000FA8', '#B37A00' => '#003FB3', '#9C5E08' => '#08639C', '#B34E00' => '#009EB3', '#A83100' => '#00A88C');

    $key = array_rand($colorPalette);
    $value = $colorPalette[$key];

    return array('char' => $key, 'font' => $value);
}
