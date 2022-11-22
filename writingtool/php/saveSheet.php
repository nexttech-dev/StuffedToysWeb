
<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalTables.php");

ini_set('display_errors', '1');

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chap = mysqli_real_escape_string($conn, $_POST['chap']);
$saveCat = mysqli_real_escape_string($conn, $_POST['saveCat']);
$saveRange = mysqli_real_escape_string($conn, $_POST['saveRange']);
$saveName = mysqli_real_escape_string($conn, $_POST['saveName']);

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
        $storyName = $story['storyName'];

        include_once("../connections/storyDb.php");
        $chapter = tableName($chap, $storyDbConn);
        $totalTables = totalTables($chap, $storyDbConn);

        $savingSheet = saveSheet($storyDbConn, $saveName, $saveCat, $saveRange, $storyName, $totalTables['list'], $chapter);
        // $savingSheet = true;

        // if ($savingSheet) {
        echo json_encode(array('msg' => "Saved Successfully", "success" => true, 'sql' => $savingSheet));
        // } else {
        // echo json_encode(array('msg' => "Saving Failed!", "success" => false, 'sql' => ""));
        // }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}
function backingUpSheets($conn, $storyId, $chapter)
{
    $duplicatingSheet = mysqli_query($conn, "CREATE TABLE save_" . $storyId . " LIKE story_" . $chapter . "");
    if ($duplicatingSheet) {
        $copyingDataToNewTable = mysqli_query($conn, "INSERT save_" . $storyId . " SELECT * FROM story_" . $chapter . "");
        if ($copyingDataToNewTable) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    // return true;
}
function backingUpAllSheets($conn, $storyId, $chapter)
{
    $duplicatingSheet = mysqli_query($conn, "CREATE TABLE save_" . $storyId . " LIKE story_" . $chapter . "");
    if ($duplicatingSheet) {
        $copyingDataToNewTable = mysqli_query($conn, "INSERT save_" . $storyId . " SELECT * FROM story_" . $chapter . "");
        if ($copyingDataToNewTable) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
    // return true;
}


function saveSheet($conn, $name, $saveCat, $saveRange, $storyName, $allTables, $currentSheet)
{
    $saveJSON = array();
    $prevSave = false;

    $configTable = mysqli_query($conn, "SELECT * FROM config");


    //ACCESSING PREVIOUS SAVES
    while ($configs = mysqli_fetch_assoc($configTable)) {
        if (isset($configs['configName']) && $configs['configName'] == 'saves') {
            $saveJSON = json_decode($configs['configDetails'], true);
            $prevSave = true;
            break;
        }
    }


    //CHECKING IF THERE IS SAVE ROW OR NOT
    if ($prevSave) {
        $totalSaves = count($saveJSON);
        if ($totalSaves >> 0) {
            $newSaves = "{";
            for ($i = 1; $i <= $totalSaves; $i++) {

                $newSaves .= '"' . $i . '":{
                    "id" :  ' . $saveJSON[$i]["id"] . ',
                    "name" : "' . $saveJSON[$i]["name"] . '",
                    "date" : "' . $saveJSON[$i]["date"] . '",
                    "time" : "' . $saveJSON[$i]["time"] . '",
                    "saveCat" : "' . $saveJSON[$i]["saveCat"] . '",
                    "details" : ' . json_encode($saveJSON[$i]['details']) . ',
                    "range" : "' . $saveJSON[$i]['range'] . '",
                    "storyName" : "' . $saveJSON[$i]['storyName'] . '",
                    "status" : "' . $saveJSON[$i]["status"] . '"
                  },';
            }


            if ($name === "null") {
                $name = $storyName . "(V " . $totalSaves + 1 . ")";
            }
            $tablesList = "{";
            if ($saveRange == "full") {
                for ($i = 0; $i < count($allTables); $i++) {

                    $tableSavingId = rand(time(), 100000000);

                    $makingBackup = backingUpSheets($conn, $tableSavingId, $allTables[$i]);
                    if ($makingBackup) {
                        $table =  explode("_", $allTables[$i]);
                        $tablesList .= '"' . $i + 1 . '":{
                            "saveId" : ' . $tableSavingId . ',
                            "sheet" : "' . $table[1] . '",
                            "sheetName" : "' . $table[0] . '"
                        }';
                        if ($i !== count($allTables) - 1) {
                            $tablesList .= ",";
                        } else {
                            $tablesList .= "}";
                        }
                    } else {
                        return false;
                        break;
                    }
                }
            } else {

                $tableSavingId = rand(time(), 100000000);
                $makingBackup = backingUpSheets($conn, $tableSavingId, $currentSheet);
                if ($makingBackup) {
                    $currentSheet =  explode("_", $currentSheet);
                    $tablesList = '{"' . 1 . '":{
                    "saveId" : ' . $tableSavingId . ',
                    "sheet" : "' . $currentSheet[1] . '",
                    "sheetName" : "' . $currentSheet[0] . '"
                }}';
                } else {
                    return false;
                }
            }
            $saveId = rand(time(), 100000000);
            $dateTime = (new DateTime())->format("Y-m-d H:i:s");

            $newSaves .=  '"' . $totalSaves + 1 . '":{
                "id" :  ' . $saveId . ',
                "name" : "' . $name . '",
                "date" : "' . $dateTime . '",
                "time" : "' . $dateTime . '",
                "saveCat" : "' . $saveCat . '",
                "details" : ' . $tablesList . ',
                "range" : "' . $saveRange . '",
                "storyName" : "' . $storyName . '",
                "status" : 1
              }}';
            // $newSaves = "";
            // $sql = "UPDATE config SET `configDetails`='{$newSaves}' WHERE `configName` = 'saves'";
            $insertingRow = mysqli_query($conn, "UPDATE config SET `configDetails`='{$newSaves}' WHERE `configName` = 'saves'");
            if ($insertingRow) {
                return true;
            } else {
                return false;
            }
        } else {
            $saveId = rand(time(), 100000000);
            $dateTime = (new DateTime())->format("Y-m-d H:i:s");
            if ($name === "null") {
                $name = $storyName . "(V " . $totalSaves + 1 . ")";
            }
            if ($saveRange === "full") {
                $tablesList = "{";

                for ($i = 0; $i < count($allTables); $i++) {
                    $tableSavingId = rand(time(), 100000000);
                    $makingBackup = backingUpSheets($conn, $tableSavingId, $allTables[$i]);
                    if ($makingBackup) {
                        $table =  explode("_", $allTables[$i]);
                        $tablesList .= '"' . $i + 1 . '":{
                            "saveId" : ' . $tableSavingId . ',
                            "sheet" : "' . $table[1] . '",
                            "sheetName" : "' . $table[0] . '"
                        }';
                        if ($i !== count($allTables) - 1) {
                            $tablesList .= ",";
                        } else {
                            $tablesList .= "}";
                        }
                    } else {
                        return false;
                        break;
                    }
                }
            } else {
                $tableSavingId = rand(time(), 100000000);
                $makingBackup = backingUpSheets($conn, $tableSavingId, $currentSheet);
                if ($makingBackup) {
                    $currentSheet =  explode("_", $currentSheet);
                    $tablesList = '{"' . 1 . '":{
                    "saveId" : ' . $tableSavingId . ',
                    "sheet" : "' . $currentSheet[1] . '",
                    "sheetName" : "' . $currentSheet[0] . '"
                }}';
                } else {
                    return false;
                }
            }
            $newSaves = '{"' . 1 . '":{
                "id" :  ' . $saveId . ',
                "name" : "' . $name . '",
                "date" : "' . $dateTime . '",
                "time" : "' . $dateTime . '",
                "saveCat" : "' . $saveCat . '",
                "details" : ' . $tablesList . ',
                "range" : "' . $saveRange . '",
                "storyName" : "' . $storyName . '",
                "status" : 1
              }}';
            $insertingRow = mysqli_query($conn, "UPDATE config SET `configDetails`='{$newSaves}' WHERE `configName` = 'saves'");
            if ($insertingRow) {
                return true;
            } else {
                return false;
            }
        }
    } else {
        return false;
    }
}
