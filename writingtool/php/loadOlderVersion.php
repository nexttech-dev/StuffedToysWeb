
<?php
session_start();
include_once("../connections/main.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalTables.php");


$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chap = mysqli_real_escape_string($conn, $_POST['chap']);
$saveId = mysqli_real_escape_string($conn, $_POST['saveId']);


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
        $chapter = tableName($chap, $storyDbConn);
        $totalTables = totalTables($chap, $storyDbConn);

        $fetchingSaves = mysqli_query($storyDbConn, "SELECT * FROM config WHERE configName = 'saves'");
        if ($fetchingSaves && mysqli_num_rows($fetchingSaves) == 1) {
            $story = mysqli_fetch_assoc($fetchingSaves);
            $savesJSON = json_decode($story['configDetails'], true);


            $totalSaves = count($savesJSON);
            $saveDetails = array();
            $fullSheet = false;
            for ($i = 1; $i <= $totalSaves; $i++) {
                if ($savesJSON[$i]["id"] == $saveId) {
                    $saveDetails = $savesJSON[$i]["details"];
                    if ($savesJSON[$i]['range'] == "full") {
                        $fullSheet = true;
                    }
                    break;
                }
            }

            if ($fullSheet) {
                $detailsOfAllSaves  = count($saveDetails);
                $droppingSheets = deletingAllStories($storyDbConn, $totalTables['list']);
                if ($droppingSheets) {
                    for ($i = 1; $i <= $detailsOfAllSaves; $i++) {
                        $newName = $saveDetails[$i]['sheetName'] . "_" . $saveDetails[$i]['sheet'];
                        $creatingSheets =  restoringFullStory($storyDbConn, $newName, $saveDetails[$i]['saveId']);
                        if (!$creatingSheets) {
                            echo json_encode(array('error' => "No Saves Row", "success" => false));
                            break;
                        }
                    }
                    if ($creatingSheets) {
                        echo json_encode(array('error' => false, "success" => true, 'saves' => $totalTables, 'saveId' => $saveId));
                    }
                } else {
                    echo json_encode(array('error' => "No Saves Row", "success" => $totalTables['list']));
                }
            } else {
                $restoring = restoringSheet($storyDbConn, $chapter, $totalTables['data'], $saveDetails[1]['saveId']);
                if ($restoring) {
                    echo json_encode(array('error' => false, "success" => true, 'saves' => json_encode($saveDetails), 'saveId' => $saveId));
                } else {
                    echo json_encode(array('error' => "No Saves Row", "success" => false));
                }
            }
        } else {
            echo json_encode(array('error' => "No Saves Row", "success" => false));
        }
    } else {
        echo json_encode(array('error' => "No story with this id in user storiesDb", "success" => false));
    }
} else {
    echo json_encode(array('error' => "No user found against this cid", "success" => false));
}


function restoringFullStory($conn, $tableName, $saveId)
{


    $duplicatingSheet = mysqli_query($conn, "CREATE TABLE story_" . $tableName . " LIKE save_" . $saveId . "");

    if ($duplicatingSheet) {
        $copyingDataToNewTable = mysqli_query($conn, "INSERT story_" . $tableName  . " SELECT * FROM save_" . $saveId . "");
        if ($copyingDataToNewTable) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function restoringSheet($conn, $chapter, $newTableNumber, $saveId)
{
    $newTableName =  explode("_", $chapter);
    $newTableName = $newTableNumber . "_" . $newTableName[1];

    $duplicatingSheet = mysqli_query($conn, "CREATE TABLE story_" . $newTableName . $newTableNumber . " LIKE save_" . $saveId . "");

    if ($duplicatingSheet) {
        $copyingDataToNewTable = mysqli_query($conn, "INSERT story_" . $newTableName . $newTableNumber . " SELECT * FROM save_" . $saveId . "");
        if ($copyingDataToNewTable) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}
function deletingAllStories($conn, $allSheets)
{
    $totalSheets = count($allSheets);

    for ($i = 0; $i < $totalSheets; $i++) {
        $droppingTables = mysqli_query($conn, "DROP TABLE story_" . $allSheets[$i] . "");
        if (!$droppingTables) {
            return false;
            break;
        }
    }

    if ($droppingTables) {
        return true;
    }
}
