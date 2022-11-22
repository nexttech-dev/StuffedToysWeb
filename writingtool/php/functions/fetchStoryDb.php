<?php

function fetchStoriesInfo($conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'totalChars' => null);
    $storiesInfoData = mysqli_query($conn, "SELECT * FROM charInfo");
    if ($storiesInfoData) {
        while ($storiesInfoRow = mysqli_fetch_assoc($storiesInfoData)) {
            $result['result'] = true;
            $result['msg'] = "Chars Found!";
            $result['error'] = false;
            $result['data'][$storiesInfoRow['charCode']] = json_decode($storiesInfoRow['charDetails']);
        }
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "FSDFSIX001";
    }
    return $result;
}

function fetchStory($conn, $chapter)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'rawData' => null);

    $tbName = 'story_' . $chapter;

    $storyData = mysqli_query($conn, "SELECT * FROM $tbName ORDER BY lineNumber ASC");

    if ($storyData) {
        $result['result'] = true;
        $result['msg'] = "Story!";
        $result['error'] = false;
        $result['totalRows'] = mysqli_num_rows($storyData);
        $result['rawData'] = $storyData;
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "FSDFSX001";
    }
    return $result;
}
