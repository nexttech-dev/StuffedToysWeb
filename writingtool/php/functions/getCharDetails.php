<?php

function getCharDetailsByUserId($storiesInfoTable, $userId)
{

    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'universalData' => null);

    $totalNumberOfRows = (string)mysqli_num_rows($storiesInfoTable);

    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        $result['universalData'] = array('storyName' => $storiesInfoRow['storyName'], 'storyChars' => $storiesInfoRow['storyChars'], 'storyId' => $storiesInfoRow['storyId'], 'startedBy' => $storiesInfoRow['startedBy'], 'date' => $storiesInfoRow['date'], 'time' => $storiesInfoRow['time'], 'totalLines' => $totalNumberOfRows);
        if ($storiesInfoRow['charController'] == $userId) {
            $result['result'] = true;
            $result['msg'] = "Char Found!";
            $result['error'] = false;
            $result['data'] = array('charCode' => $storiesInfoRow['charCode'], 'charName' => $storiesInfoRow['charName'], 'charColor' => $storiesInfoRow['color'], 'totalChars' => $storiesInfoRow['storyChars']);
            break;
        }
    }
    return $result;
}

function getCharDetailsByCid($cid, $conn)
{

    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

    $charDetails = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

    if ($charDetails) {
        if (mysqli_num_rows($charDetails) == 1) {
            $data = mysqli_fetch_assoc($charDetails);
            $result['result'] = true;
            $result['msg'] = "Char Found!";
            $result['error'] = false;
            $result['data'] = array('charUID' => $data['uid'], 'charFullName' => $data['fullName'], 'charUserName' => $data['userName'], 'charEmail' => $data['email'], 'charStatus' => $data['status']);
        } else {
            $result['msg'] = "No user or multiple users found against this CID";
            $result['errorCode'] = "GCDBCX002";
        }
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "GCDBCX001";
    }

    return $result;
}

function getAllCharDetails($storiesInfoTable)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

    $charCode = 1;
    while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoTable)) {
        if ($storiesInfoRow['charCode'] == (string)$charCode) {
            $result['result'] = true;
            $result['error'] = false;
            $result['msg'] = "Chars Found!";
            $result[$charCode] = array('charCode' => $storiesInfoRow['charCode'], 'charName' => $storiesInfoRow['charName'], 'charColor' => $storiesInfoRow['color']);
            $charCode = $charCode + 1;
        }
    }
    return $result;
}

function addingCharDetails($storyData, $userId, $conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

    $newColor = '#' . random_color();
    $storyName = $storyData['storyName'];
    $storyChars = $storyData['storyChars'];
    $storyId = $storyData['storyId'];
    $startedBy = $storyData['startedBy'];
    $date = $storyData['date'];
    $time = $storyData['time'];
    $newCharCode = $storyData['totalLines'];

    $updatingStoriesInfo = mysqli_query($conn, "INSERT INTO storiesInfo (storyName,storyChars,storyId,startedBy,date,time,charCode,charId,charName,charController,color,status) VALUES ('{$storyName}','{$storyChars}','{$storyId}','{$startedBy}','{$date}','{$time}','{$newCharCode}','','Char" . (string)$newCharCode . "','{$userId}','{$newColor}',1)");

    if ($updatingStoriesInfo) {
        $result['result'] = true;
        $result['msg'] = "Char Details Added!";
        $result['error'] = false;
        $result['data'] = array('charCode' => $newCharCode, 'charName' => 'Char' . $newCharCode, 'charColor' => $newColor);
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "GCDACDX001";
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
