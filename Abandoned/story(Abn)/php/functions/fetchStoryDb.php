<?php

function fetchStoriesInfo($conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'rawData' => null);

    $storiesInfoData = mysqli_query($conn, "SELECT * FROM storiesInfo");

    if ($storiesInfoData) {
        $result['result'] = true;
        $result['msg'] = "Stories Info!";
        $result['error'] = false;
        $result['data'] = array("totalChars" => mysqli_num_rows($storiesInfoData));
        $result['rawData'] = $storiesInfoData;
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "FSDFSIX001";
    }
    return $result;
}

function fetchStory($conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'rawData' => null);

    $storyData = mysqli_query($conn, "SELECT * FROM story");

    if ($storyData) {
        $result['result'] = true;
        $result['msg'] = "Story!";
        $result['error'] = false;
        $result['data'] = array('totalNumberOfLines' => mysqli_num_rows($storyData));
        $result['rawData'] = $storyData;
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "FSDFSX001";
    }
    return $result;
}
