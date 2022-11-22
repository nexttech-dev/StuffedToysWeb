
<?php

function totalTables($chapter, $conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'universalData' => null);

    $allTables = mysqli_query($conn, 'SHOW tables');
    $count = 1;
    $allTablesList = array();
    while ($table = mysqli_fetch_array($allTables)) {
        $story = explode("_", $table[0]);
        if ($story[0] == 'story') {
            $count++;
            array_push($allTablesList, $story[1] . "_" . $story[2]);
        }
    }
    $result['result'] = true;
    $result['msg'] = 'Table Count Success!';
    $result['data'] = $count;
    $result['list'] = $allTablesList;
    $result['error'] = false;

    return $result;
}
