
<?php

function tableName($chapter, $conn)
{
    $result = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true, 'universalData' => null);

    $allTables = mysqli_query($conn, 'SHOW tables');
    while ($table = mysqli_fetch_array($allTables)) {
        $story = explode("_", $table[0]);
        if ($story[0] == 'story') {
            if ($story[1] == $chapter) {

                $tableName = $chapter . "_" . $story[2];
                return $tableName;
                break;
            }
        }
    }
    return $result;
}
