
<?php

function totalChars($conn)
{

    $configs = mysqli_query($conn, 'SELECT * FROM config');
    while ($totalCharsRow = mysqli_fetch_array($configs)) {
        if ($totalCharsRow['configName'] == 'totalChars') {
            $totalChars = json_decode($totalCharsRow['configDetails'], true);
            return $totalChars['totalChars'];
            break;
        }
    }
    // return $result;
}
