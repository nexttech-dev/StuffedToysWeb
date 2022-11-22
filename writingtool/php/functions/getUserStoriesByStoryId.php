<?php

function getUserStoriesByStoryId($storyId, $conn)
{

    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

    $storyData = mysqli_query($conn, "SELECT * FROM stories WHERE storyId = {$storyId}");

    if ($storyData) {
        if (mysqli_num_rows($storyData) == 1) {
            $data = mysqli_fetch_assoc($storyData);
            $result['result'] = true;
            $result['msg'] = "Story Found!";
            $result['error'] = false;
            $result['data'] = array('storyName' => $data['storyName'], 'storyChars' => $data['storyChars'], 'storyId' => $data['storyId'], 'startedBy' => $data['startedBy'], 'date' => $data['date'], 'time' => $data['time'], 'storyDbName' => $data['storyDbName']);
        } else {
            $result['msg'] = "No story or multiple stories found against this Story ID";
            $result['errorCode'] = "GCDBSIX002";
        }
    } else {
        $result['msg'] = "System Failure!";
        $result['errorCode'] = "GCDBSIX001";
    }

    return $result;
}
