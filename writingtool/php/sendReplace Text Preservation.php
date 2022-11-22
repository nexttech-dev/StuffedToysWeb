<?php


session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
// include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalChars.php");

// echo phpinfo();
ini_set('display_errors', '1');
$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
// $msg = mysqli_real_escape_string($conn, $_POST['msg']);
$msg = $_POST['msg'];
$msg = preg_replace("/\n/m", '\n', $msg);
$col = mysqli_real_escape_string($conn, $_POST['col']);
$row = mysqli_real_escape_string($conn, $_POST['row']);
$send = mysqli_real_escape_string($conn, $_POST['sendAs']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$autoFeed = mysqli_real_escape_string($conn, $_POST['autoFeed']);

$autoFeed = filter_var($autoFeed, FILTER_VALIDATE_BOOLEAN);
$autoFeed = (int)$autoFeed;


if ($send == "sa") {
    $msg = "~~" . $msg;
} else if ($send == "aux") {
    $msg = "*" . $msg;
}

$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

$userData = getCharDetailsByCid($cid, $conn);

if ($userData['result'] == true) {

    $userId = $userData['data']['charUID'];
    $userName = $userData['data']['charFullName'];

    include_once("../connections/userDb.php");

    $storyData = getUserStoriesByStoryId($storyId, $userDbConn);
    if ($storyData['result'] == true) {

        $storyChars = $storyData['data']['storyChars'];
        $storyDbName = $storyData['data']['storyDbName'];


        include_once("../connections/storyDb.php");

        $chapter = tableName($chapter, $storyDbConn);
        $data = array();
        $storyDetails = fetchStory($storyDbConn, $chapter);
        while ($msgRow = mysqli_fetch_assoc($storyDetails['rawData'])) {
            // $diaUTF8 = utf8_decode($msgRow['dialogue']);
            $data[$msgRow['lineNumber']] = json_decode($msgRow['dialogue'], true);
        }
        // if ($data[$row]) {
        //INSERTING AND REPLACING DIALOGUE
        //REPACING REQUIRED
        $dateTime = (new DateTime())->format("Y-m-d H:i:s");

        if ($send == "act") {
            if (isset($data[$row]) && isset($data[$row][$col])) {
                if ($data[$row][$col]['details']['category'] == "dir" ||  $data[$row][$col]['details']['category'] == "iv" || $data[$row][$col]['details']['category'] == "oth") {
                    $totalChars = totalChars($storyDbConn);
                    $actInRow = false;
                    $actInRowCol = null;

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if (isset($data[$row][$i]) && $data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        unset($data[$row][$actInRowCol]);
                        $generatedSpecificCell = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell, $row, false);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                            if ($generatedRow) {
                                $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, ($row + 1), true);
                                if ($updatingCell) {
                                    $results['success'] = "X-S001";
                                    echo json_encode($results);
                                } else {
                                    $results['success'] = "X-F001";
                                    echo json_encode($results);
                                }
                            }
                        }
                    } else {
                        $generatedSpecificCell = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell, $row, false);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                            if ($generatedRow) {
                                $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                                if ($insertingNewRow) {
                                    $results['success'] = "X-S002";
                                    echo json_encode($results);
                                } else {
                                    $results['success'] = "X-S002";
                                    echo json_encode($results);
                                }
                            }
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "act") {
                    $totalChars = totalChars($storyDbConn);

                    $generatedSpecificCell1 = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row, false);
                    if ($updatingCell) {

                        $generatedSpecificCell = generatingDialogueForSpecificCell($row + 1, $col, $msg, 'human', $data[$row + 1], $totalChars, true, $conn);
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell, $row + 1, true);
                        if ($updatingCell) {
                            $results['success'] = "X-S003";
                            echo json_encode($results);
                        } else {
                            $results['success'] = "X-F003";
                            echo json_encode($results);
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "human") {

                    $generatedSpecificCell = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell, $row, false);

                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                            if ($insertingNewRow) {
                                $totalChars = totalChars($storyDbConn);

                                for ($i = 1; $i <= (int)$totalChars; $i++) {
                                    if (isset($data[$row - 1][$i]['details']['category']) && $data[$row - 1][$i]['details']['category'] == "act") {
                                        unset($data[$row - 1][$i]);
                                        $generatedSpecificCell1 = generatingDialogueForSpecificCell($row - 1, $col, $msg, 'human', $data[$row - 1], $totalChars, false, $conn);
                                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row - 1, true);
                                        if ($updatingCell) {
                                            //SOMETIMES ROW WILL BE EMPTY
                                            $results['success'] = "X-S004";
                                            echo json_encode($results);
                                        } else {
                                            $results['success'] = "X-F003";
                                            echo json_encode($results);
                                        }
                                        break;
                                    }
                                }
                            } else {
                                $results['success'] = "X-F003";
                                echo json_encode($results);
                            }
                        }
                    } else {
                    }
                }
            } else if (isset($data[$row]) && !isset($data[$row][$col])) {
                $totalChars = totalChars($storyDbConn);
                $active = false;
                $activeCol = null;
                $catArray = array();
                for ($i = 1; $i <= (int)$totalChars; $i++) {
                    if (isset($data[$row][$i])) {
                        $active = true;
                        $catArray[$data[$row][$i]['details']['category']] = $i;
                    }
                }
                if (isset($catArray['sa']) || isset($catArray['aux'])) {
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                            if ($insertingNewRow) {
                                $results['success'] = "X-S005";
                                echo json_encode($results);
                            } else {
                                $results['success'] = "X-F005";
                                echo json_encode($results);
                            }
                        }
                    } else {
                        $results['success'] = "X-F005";
                        echo json_encode($results);
                    }
                } else if (isset($catArray['human'])) {
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                            if ($insertingNewRow) {
                                for ($i = 1; $i <= (int)$totalChars; $i++) {
                                    if ($data[$row - 1][$i]['details']['category'] == "act") {
                                        unset($data[$row - 1][$i]);
                                        $generatedSpecificCell1 = generatingDialogueForSpecificCell($row - 1, $col, $msg, 'human', $data[$row - 1], $totalChars, false, $conn);
                                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row - 1, true);
                                        if ($updatingCell) {
                                            //SOMETIMES ROW WILL BE EMPTY
                                            $results['success'] = "X-S006";
                                            echo json_encode($results);
                                        } else {
                                            $results['success'] = "X-F006";
                                            echo json_encode($results);
                                        }
                                        break;
                                    }
                                }
                            } else {
                                echo json_encode($results);
                            }
                        }
                    } else {
                        echo json_encode($results);
                    }
                } else {
                    $actInRow = false;
                    $actInRowCol = null;

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if (isset($data[$row][$i]) && $data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        if (isset($data[$row][$actInRowCol])) {
                            unset($data[$row][$actInRowCol]);
                        }

                        $generatedSpecificCell1 = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row, false);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                            if ($generatedRow) {
                                $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, ($row + 1), true);
                                if ($updatingCell) {
                                    $results['success'] = "X-S007";
                                    echo json_encode($results);
                                } else {
                                    $results['success'] = "X-F007";
                                    echo json_encode($results);
                                }
                            }
                        }
                    } else {
                        $totalRows = (int)$storyDetails['totalRows'];
                        if ($totalRows < $row && ($totalRows + 1) != $row) {
                            $row = $totalRows + 1;
                        }
                        if ($totalRows < $row) {
                            # code...
                            $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                            if ($insertingNewRow) {
                                $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                                if ($generatedRow) {
                                    $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                                    if ($insertingNewRow) {
                                        $results['success'] = "X-S008";
                                        echo json_encode($results);
                                    } else {
                                        $results['success'] = "X-F008";
                                        echo json_encode($results);
                                    }
                                }
                            }
                        } else {

                            $generatedSpecificCell1 = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row, false);
                            if ($updatingCell) {
                                $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                                if ($generatedRow) {
                                    $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow, true);
                                    if ($insertingNewRow) {
                                        $results['success'] = "X-S009";
                                        echo json_encode($results);
                                    } else {
                                        $results['success'] = "X-F009";
                                        echo json_encode($results);
                                    }
                                } else {
                                    $results['success'] = "X-F009";
                                    echo json_encode($results);
                                }
                            } else {
                                $results['success'] = "X-F009";
                                echo json_encode($results);
                            }
                        }
                    }
                }
            } else if (!isset($data[$row])) {
                $totalRows = (int)$storyDetails['totalRows'];
                if ($totalRows < $row && ($totalRows + 1) != $row && $autoFeed) {
                    $row = $totalRows + 1;
                }
                if ($totalRows < $row) {


                    if ($autoFeed) {
                        $generatedRow1 =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow1 = insertingRow($storyDbConn, $chapter, $row, $generatedRow1, false);
                        $generatedRow2 =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                        $insertingNewRow2 = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow2, true);

                        if ($insertingNewRow1 && $insertingNewRow2) {
                            $results['success'] = "X-S0010";
                            echo json_encode($results);
                        } else {
                            $results['success'] = "X-F0010";
                            echo json_encode($results);
                        }
                    } else {
                        $start = $totalRows + 1;

                        for ($i = (int)$start; $i < $row; $i++) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $i, "{}", false);
                        }

                        $generatedRow1 =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow1 = insertingRow($storyDbConn, $chapter, $row, $generatedRow1, false);
                        $generatedRow2 =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                        $insertingNewRow2 = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow2, true);

                        if ($insertingNewRow1 && $insertingNewRow2) {
                            $results['success'] = "X-S0011";
                            echo json_encode($results);
                        } else {
                            $results['success'] = "X-F0011";
                            echo json_encode($results);
                        }
                    }
                } else {
                    $generatedRow1 =  generatingDialogue($row, $col, $msg, $send, $conn);
                    $insertingNewRow1 = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow1, $row, false);
                    $generatedRow2 =  generatingDialogue($row + 1, $col, $msg, 'human', $conn);
                    $insertingNewRow2 = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow2, true);

                    if ($insertingNewRow1 && $insertingNewRow2) {
                        $results['success'] = "X-S0012";
                        echo json_encode($results);
                    } else {
                        $results['success'] = "X-F0012";
                        echo json_encode($results);
                    }
                }
            }
        } else if ($send == "sa" || $send == "aux") {
            if (isset($data[$row]) &&  isset($data[$row][$col])) {
                if ($data[$row][$col]['details']['category'] == "sa" || $data[$row][$col]['details']['category'] == "aux") {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                        if ($updatingCell) {
                            $results['success'] = "X-S0013";
                            echo json_encode($results);
                        } else {
                            $results['success'] = "X-F0013";
                            echo json_encode($results);
                        }
                    }
                }
            } else if (isset($data[$row]) && !isset($data[$row][$col])) {
                $totalChars = totalChars($storyDbConn);
                $active = false;
                $activeCol = null;
                $catArray = array();
                for ($i = 1; $i <= (int)$totalChars; $i++) {
                    if (isset($data[$row][$i])) {
                        $active = true;
                        $catArray[$data[$row][$i]['details']['category']] = $i;
                    }
                }

                if (isset($catArray['sa']) || isset($catArray['aux'])) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                        if ($updatingCell) {
                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } else if (isset($catArray['human'])) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                        if ($updatingCell) {
                            $totalChars = totalChars($storyDbConn);
                            for ($i = 1; $i <= (int)$totalChars; $i++) {
                                if (isset($data[$row - 1][$i]) && $data[$row - 1][$i]['details']['category'] == "act") {
                                    unset($data[$row - 1][$i]);
                                    $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                                    if ($updatingCell) {
                                        //SOMETIMES ROW WILL BE EMPTY
                                        echo json_encode($results);
                                    } else {
                                        echo json_encode($results);
                                    }
                                    break;
                                }
                            }
                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } else {
                    $totalChars = totalChars($storyDbConn);
                    $actInRow = false;
                    $actInRowCol = null;
                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if (isset($data[$row][$i]) && $data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                        if ($generatedRow) {
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                            if ($updatingCell) {
                                $deletingRow = deletingRow($storyDbConn, $chapter, $row + 1);
                                if ($deletingRow) {
                                    echo json_encode($results);
                                } else {
                                    echo json_encode($results);
                                }
                            }
                        }
                    } else {
                        $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                        if ($generatedRow) {
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                            if ($updatingCell) {
                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        }
                    }
                }
            } else if (!isset($data[$row])) {
                $totalRows = (int)$storyDetails['totalRows'];
                if ($totalRows < $row && ($totalRows + 1) != $row && $autoFeed) {
                    $row = $totalRows + 1;
                }
                if ($totalRows < $row) {
                    if ($autoFeed) {

                        $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoghjji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    } else {
                        $start = $totalRows + 1;

                        for ($i = (int)$start; $i < $row; $i++) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $i, "{}", false);
                        }

                        $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoghjji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    }
                } else {
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                    $insertingNewRow = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                    if ($insertingNewRow) {
                        $results['success'] = "Hurrah 1!jigjghjghjjoji";
                        $results['ar'] = json_encode($catArray);
                        echo json_encode($results);
                    }
                }
            }
        } else if ($send == "dir" || $send == "iv" || $send == "oth") {

            if (isset($data[$row]) && isset($data[$row][$col])) {

                if ($data[$row][$col]['details']['category'] == "human") {
                    $totalChars = totalChars($storyDbConn);

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if (isset($data[$row - 1][$i]) && $data[$row - 1][$i]['details']['category'] == "act") {
                            unset($data[$row - 1][$i]);
                            $generatedSpecificCell1 = generatingDialogueForSpecificCell($row - 1, $col, $msg, 'human', $data[$row - 1], $totalChars, false, $conn);
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row - 1, true);
                            // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                            if ($updatingCell) {
                                //SOMETIMES ROW WILL BE EMPTY
                                $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                                if ($generatedRow) {
                                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                                    if ($updatingCell) {
                                        $results['fuckuu'] = "gbg";
                                        echo json_encode($results);
                                    } else {
                                        $results['fuckuu'] = "gsfddsfbg";

                                        echo json_encode($results);
                                    }
                                }
                            } else {
                                $results['fuckuu'] = "gbsfeg";

                                echo json_encode($results);
                            }
                            break;
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "act") {
                    $totalChars = totalChars($storyDbConn);


                    $generatedSpecificCell1 = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row, false);
                    // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $deletingRow = deletingRow($storyDbConn, $chapter, $row + 1);
                        if ($deletingRow) {
                            $results['success'] = $generatedSpecificCell1;

                            echo json_encode($results);
                        } else {
                            $results['fuckuu'] = "gbsdvdsvsfeg";

                            echo json_encode($results);
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "dir" || $data[$row][$col]['details']['category'] == "iv" || $data[$row][$col]['details']['category'] == "oth") {

                    $totalChars = totalChars($storyDbConn);
                    $generatedSpecificCell1 = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row, false);
                    // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $results['fuckuu'] = "gbsfereeg";

                        echo json_encode($results);
                    } else {
                        $results['fuckuu'] = "gbsfedfsvfdvg";

                        echo json_encode($results);
                    }
                }
            } else if (isset($data[$row]) && !isset($data[$row][$col])) {
                # code...
                $totalChars = totalChars($storyDbConn);
                $active = false;
                $activeCol = null;
                $catArray = array();
                for ($i = 1; $i <= (int)$totalChars; $i++) {
                    if (isset($data[$row][$i])) {
                        $active = true;
                        $catArray[$data[$row][$i]['details']['category']] = $i;
                    }
                }
                if (isset($catArray['sa']) || isset($catArray['aux'])) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                        if ($updatingCell) {
                            $results['gggy'] = "fuckkbdfbgdfkkkk";

                            echo json_encode($results);
                        } else {
                            $results['gggy'] = "fuckkbdfbgktyu45t45gdfkkkk";

                            echo json_encode($results);
                        }
                    }
                } else if (isset($catArray['human'])) {
                    $totalChars = totalChars($storyDbConn);

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if ($data[$row - 1][$i]['details']['category'] == "act") {
                            unset($data[$row - 1][$i]);
                            $generatedSpecificCell1 = generatingDialogueForSpecificCell($row - 1, $col, $msg, $send, $data[$row - 1], $totalChars, false, $conn);
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedSpecificCell1, $row - 1, false);
                            // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                            if ($updatingCell) {
                                //SOMETIMES ROW WILL BE EMPTY
                                $generatedRow = generatingDialogue($row, $col, $msg, $send, $conn);
                                if ($generatedRow) {
                                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                                    if ($updatingCell) {
                                        $results['gggy'] = "fuckkkkkk";
                                        echo json_encode($results);
                                    } else {
                                        $results['gggy'] = "fuckkbdfbgdfkkrtgeijvnkdfnvkdfkk";

                                        echo json_encode($results);
                                    }
                                }
                                // echo json_encode($results);
                            } else {
                                $results['gggy'] = "fuckkbdfvvioerieonfvbgdfkkkk";

                                echo json_encode($results);
                            }
                            break;
                        }
                    }
                } else {

                    $totalRows = (int)$storyDetails['totalRows'];
                    if ($totalRows < $row && ($totalRows + 1) != $row) {
                        $row = $totalRows + 1;
                    }
                    if ($totalRows < $row) {
                        # code...
                        $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                        if ($insertingNewRow) {
                            $results['success'] = json_encode($data);
                            $results['success'] = "Hell yeah";

                            echo $results;
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    } else {

                        $generateCell = generatingDialogueForSpecificCell($row, $col, $msg, $send, $data[$row], $totalChars, true, $conn);
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generateCell, $row, false);


                        if ($updatingCell) {
                            $results['success'] = json_encode($updatingCell);
                            $results['ar'] = $updatingCell;
                            echo json_encode($results);
                        }
                    }
                }
            } else if (!isset($data[$row])) {
                $totalRows = (int)$storyDetails['totalRows'];
                if ($totalRows < $row && ($totalRows + 1) != $row && $autoFeed) {
                    $row = $totalRows + 1;
                }
                if ($totalRows < $row) {
                    if ($autoFeed) {

                        $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijgjghjoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    } else {
                        $start = $totalRows + 1;

                        for ($i = (int)$start; $i < $row; $i++) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $i, "{}", false);
                        }

                        $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow, false);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijgjghjoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    }
                    # code...

                } else {
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send, $conn);
                    $insertingNewRow = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row, false);
                    if ($insertingNewRow) {
                        $results['success'] = "Hudgfdfgrrah 1!jijoji";
                        $results['ar'] = json_encode($catArray);
                        echo json_encode($results);
                    }
                }
            }
        }
    }
} else {
    $results['msg'] = $userData['msg'];
    $results['errorCode'] = $userData['errorCode'];
    echo json_encode($results);
}

function generatingDialogue($row, $col, $dialogueText, $cat, $conn)
{

    $id = rand(time(), 100000000);
    $dateTime = (new DateTime())->format("Y-m-d H:i:s");
    $numOfWords = str_word_count($dialogueText);
    $dialogue = "";
    $dialogue = '{
        "' . $col . '" : {
          "charCode" :  ' . $col . ',
          "details" : {
            "id" : ' . $id . ',
            "dateTime" : "' . $dateTime . '",
            "comment" : null,
            "dialogue" : "' .  $dialogueText . '",
            "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
            "numOfWords" : ' . $numOfWords . ',
            "category" : "' . $cat . '",
            "active" : ' . $col . '
          }
        }
      }';

    return $dialogue;
}

function generatingDialogueForSpecificCell($row, $col, $dialogueText, $cat, $dataRow, $totalChars, $flag, $conn)
{

    // $dataArray = array();
    // for ($i = 1; $i <= (int)$totalChars; $i++) {
    //     if (isset($dataRow[$i]) && $i != $col && $flag) {
    //         $dataArray[$i] = array('charCode' => $i,  'id' =>  $dataRow[$i]['details']['id'], 'dateTime' => $dataRow[$i]['details']['dateTime'], 'comment' => $dataRow[$i]['details']['comment'], 'dialogue' => $dataRow[$i]['details']['dialogue'], 'fileName' => $dataRow[$i]['details']['fileName'], 'numOfWords' => $dataRow[$i]['details']['numOfWords'], 'cat' => $dataRow[$i]['details']['category'], 'active' => $dataRow[$i]['details']['active']);
    //     } else if (isset($dataRow[$i]) && $flag === false) {
    //         $dataArray[$i] = array('charCode' => $i,  'id' =>  $dataRow[$i]['details']['id'], 'dateTime' => $dataRow[$i]['details']['dateTime'], 'comment' => $dataRow[$i]['details']['comment'], 'dialogue' => $dataRow[$i]['details']['dialogue'], 'fileName' => $dataRow[$i]['details']['fileName'], 'numOfWords' => $dataRow[$i]['details']['numOfWords'], 'cat' => $dataRow[$i]['details']['category'], 'active' => $dataRow[$i]['details']['active']);
    //     }
    // }

    $chars = array_keys($dataRow);
    $len = count($chars);
    $currentCharDialogue = false;
    $dialogue = '{';
    $counter = 1;
    if ($len == 0) {
        $id = rand(time(), 100000000);
        $dateTime = (new DateTime())->format("Y-m-d H:i:s");
        $numOfWords = str_word_count($dialogueText);
        $dialogue .= '"' . $col . '":{
              "charCode" :  ' . $col . ',
              "details" : {
                "id" : ' . $id . ',
                "dateTime" : "' . $dateTime . '",
                "comment" : null,
                "dialogue" : "' . $dialogueText . '",
                "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
                "numOfWords" : ' . $numOfWords . ',
                "category" : "' . $cat . '",
                "active" : ' . $col . '
              }
            }}';
    } else {


        foreach ($chars as $value) {
            if ($value === $col) {
                $currentCharDialogue = true;
                $id = rand(time(), 100000000);
                $dateTime = (new DateTime())->format("Y-m-d H:i:s");
                $numOfWords = str_word_count($dialogueText);
                $dialogue .= '"' . $col . '":{
              "charCode" :  ' . $col . ',
              "details" : {
                "id" : ' . $id . ',
                "dateTime" : "' . $dateTime . '",
                "comment" : null,
                "dialogue" : "' . $dialogueText . '",
                "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
                "numOfWords" : ' . $numOfWords . ',
                "category" : "' . $cat . '",
                "active" : ' . $col . '
              }
            }';
            } else {
                // $dia = str_replace(["\r", "\n"], "\n", $dataRow[$value]['details']['dialogue']);
                // $dia = mysqli_real_escape_string($conn, $dataRow[$value]['details']['dialogue']);
                $dia = $dataRow[$value]['details']['dialogue'];
                $dia = preg_replace("/\n/m", '\n', $dia);

                $dialogue .= '"' . $value . '":{
                "charCode" :  ' . $value . ',
                "details" : {
                  "id" : ' . $dataRow[$value]['details']['id']  . ',
                  "dateTime" : "' . $dataRow[$value]['details']['dateTime']  . '",
                  "comment" : "' . $dataRow[$value]['details']['comment'] . '",
                  "dialogue" : "' . $dia  . '",
                  "fileName" : "' . $dataRow[$value]['details']['fileName'] . '",
                  "numOfWords" : ' . $dataRow[$value]['details']['numOfWords']  . ',
                  "category" : "' . $dataRow[$value]['details']['category'] . '",
                  "active" : ' . $dataRow[$value]['details']['active'] . '
                }
              }';
            }


            if ($counter !== $len) {
                $dialogue .= ",";
            } else if ($counter === $len) {
                if ($currentCharDialogue === false) {
                    $id = rand(time(), 100000000);
                    $dateTime = (new DateTime())->format("Y-m-d H:i:s");
                    $numOfWords = str_word_count($dialogueText);
                    $dialogue .= ",";
                    $dialogue .= '"' . $col . '":{
                    "charCode" :  ' . $col . ',
                    "details" : {
                      "id" : ' . $id . ',
                      "dateTime" : "' . $dateTime . '",
                      "comment" : null,
                      "dialogue" : "' .   $dialogueText . '",
                      "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
                      "numOfWords" : ' . $numOfWords . ',
                      "category" : "' . $cat . '",
                      "active" : ' . $col . '
                    }
                  }}';
                } else {
                    $dialogue .= "}";
                }
            }
            $counter = $counter + 1;
        }
    }
    return $dialogue;
}
function newRow($conn, $chapter, $lineNumber, $dialogue)
{
    $lineCode = rand(time(), 100000000);
    $newRow = "INSERT INTO story_" . $chapter . " (lineNumber,lineCode,dialogue,status) VALUES ('{$lineNumber}','{$lineCode}','{$dialogue}',1)";
    $sqlNewRow = mysqli_query($conn, $newRow);
    if ($sqlNewRow) {
        return true;
    } else {
        return false;
    }
}
function insertingRow($conn, $chapter, $lineNumber, $dialogue, $humanFlag)
{

    $updatingLineNum = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber+1 WHERE lineNumber > " . (int)$lineNumber - 1 . " ORDER BY lineNumber ASC");
    $lineCode = rand(time(), 100000000);
    if ($humanFlag) {
        $updatingCell = $conn->prepare("INSERT INTO story_" . $chapter . " (lineNumber,lineCode,dialogue,status) VALUES (?,?,?,1)");
    } else {

        $updatingCell = $conn->prepare("INSERT INTO story_" . $chapter . " (lineNumber,lineCode,dialogue,status) VALUES (?,?,?,0)");
    }
    $updatingCell->bind_param('iis', $lineNumber, $lineCode, $dialogue);
    $updatingCell->execute();
    if ($updatingCell->error || !$updatingLineNum) {
        echo "FAILURE!!! " . $updatingCell->error;
        return false;
    } else {
        echo "Updated {$updatingCell->affected_rows} rows";
        return true;
    }
}

function deletingRow($conn, $chapter, $row)
{
    $deletingRow = mysqli_query($conn, "DELETE FROM story_" . $chapter . " WHERE lineNumber='{$row}'");
    $updatingLineNum = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber-1 WHERE lineNumber > " . (int)$row - 1  . " ORDER BY lineNumber ASC");
    if ($deletingRow && $updatingLineNum) {
        return true;
    } else {
        return false;
    }
}
function updatingCell($conn, $chapter, $dialogue, $row)
{
    $dialogue = json_encode($dialogue);
    $lineCode = rand(time(), 100000000);

    $updatingCell = $conn->prepare("UPDATE story_" . $chapter . " SET dialogue=?,lineCode=? WHERE lineNumber=?");
    $updatingCell->bind_param('sis', $dialogue, $lineCode, $row);
    $updatingCell->execute();

    if ($updatingCell->error) {
        echo "FAILURE!!! " . $updatingCell->error;
        return false;
    } else {
        echo "Updated {$updatingCell->affected_rows} rows";
        return true;
    }
}
function updatingCellWithOutEncode($conn, $chapter, $dialogue, $row, $humanFlag)
{
    // $sql = "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'";
    if ($humanFlag) {
        $lineCode = rand(time(), 100000000);
        $updatingCell = $conn->prepare("UPDATE story_" . $chapter . " SET dialogue=?,lineCode=?,status=1 WHERE lineNumber=?");
        $updatingCell->bind_param('sis', $dialogue, $lineCode, $row);
        $updatingCell->execute();
        if ($updatingCell->error) {
            echo "FAILURE!!! " . $updatingCell->error;
            return false;
        } else {
            echo "Updated {$updatingCell->affected_rows} rows";
            return true;
        }
    } else {
        $lineCode = rand(time(), 100000000);

        $updatingCell = $conn->prepare("UPDATE story_" . $chapter . " SET dialogue=?,lineCode=? WHERE lineNumber=?");
        $updatingCell->bind_param('sis', $dialogue, $lineCode, $row);
        $updatingCell->execute();
        if ($updatingCell->error) {
            echo "FAILURE!!! " . $updatingCell->error;
            return false;
        } else {
            echo "Updated {$updatingCell->affected_rows} rows";
            return true;
        }
    }


    // return $sql;
}
