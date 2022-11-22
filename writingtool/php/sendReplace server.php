<?php

// echo phpinfo();
ini_set('display_errors', '1');
session_start();
include_once("../connections/main.php");
include_once("../php/functions/getCharDetails.php");
include_once("../php/functions/getUserStoriesByStoryId.php");
include_once("../php/functions/fetchStoryDb.php");
// include_once("../php/storyDb.php");
include_once("../php/functions/tableName.php");
include_once("../php/functions/totalChars.php");

$cid = $_SESSION['cid'];
$storyId = mysqli_real_escape_string($conn, $_POST['storyId']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);
$msg = mysqli_real_escape_string($conn, $_POST['msg']);
$col = mysqli_real_escape_string($conn, $_POST['col']);
$row = mysqli_real_escape_string($conn, $_POST['row']);
$send = mysqli_real_escape_string($conn, $_POST['sendAs']);
$chapter = mysqli_real_escape_string($conn, $_POST['chapter']);

// if (str_contains($msg, '~~')) {
//     $msg = trim($msg, "~~");
// }
// if (str_contains($msg, '*')) {
//     $msg = trim($msg, "*");
// }

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
            $data[$msgRow['lineNumber']] = json_decode($msgRow['dialogue'], true);
        }

        // if ($data[$row]) {
        //INSERTING AND REPLACING DIALOGUE
        //REPACING REQUIRED
        $dateTime = (new DateTime())->format("Y-m-d H:i:s");

        if ($send == "act") {
            if (!isset($data[$row])) {
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
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                            if ($insertingNewRow) {
                                $results['success'] = "Hurrah 1hhjh!";
                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        }
                    } else {
                        echo json_encode($results);
                    }
                } else if (isset($catArray['human'])) {
                    $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                            if ($insertingNewRow) {
                                for ($i = 1; $i <= (int)$totalChars; $i++) {
                                    if ($data[$row - 1][$i]['details']['category'] == "act") {
                                        unset($data[$row - 1][$i]);
                                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                                        if ($updatingCell) {
                                            //SOMETIMES ROW WILL BE EMPTY
                                            $results['success'] = "Hurrah 1jj!";
                                            echo json_encode($results);
                                        } else {
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
                        if (isset($data[$row][$i]['details']['category']) && $data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        if (isset($data[$row][$actInRowCol])) {
                            unset($data[$row][$actInRowCol]);
                        }
                        $data[$row][$col]['charCode'] = $col;
                        $data[$row][$col]['details']['row'] = $row;
                        $data[$row][$col]['details']['id'] = rand(time(), 100000000);
                        $data[$row][$col]['details']['comment'] = null;
                        $data[$row][$col]['details']['active'] = $col;
                        $data[$row][$col]['details']['category'] = $send;
                        $data[$row][$col]['details']['dateTime'] = $dateTime;
                        $data[$row][$col]['details']['dialogue'] = $msg;
                        $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                        $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                            if ($generatedRow) {
                                $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, ($row + 1));
                                if ($updatingCell) {
                                    $results['success'] = $updatingCell;
                                    $results['gen'] = $generatedRow;
                                    echo json_encode($results);
                                } else {
                                    echo json_encode($results);
                                }
                            }
                            // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row + 1], $row + 1);
                        }
                    } else {
                        $totalRows = (int)$storyDetails['totalRows'];
                        if ($totalRows < $row && ($totalRows + 1) != $row) {
                            $row = $totalRows + 1;
                        }
                        if ($totalRows < $row) {
                            # code...
                            $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow);
                            if ($insertingNewRow) {
                                $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                                if ($generatedRow) {
                                    $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                                    if ($insertingNewRow) {
                                        $results['success'] = "Hurrah 1!jijoji";
                                        $results['ar'] = json_encode($catArray);
                                        echo json_encode($results);
                                    } else {
                                        echo json_encode($results);
                                    }
                                }
                            }
                        } else {
                            $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                            $insertingNewRow = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                            if ($insertingNewRow) {
                                $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                                if ($generatedRow) {
                                    $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                                    if ($insertingNewRow) {
                                        $results['success'] = "Hurrah 1!jijoji";
                                        $results['ar'] = json_encode($catArray);
                                        echo json_encode($results);
                                    } else {
                                        echo json_encode($results);
                                    }
                                }
                            }
                        }
                        // $data[$row][$col]['charCode'] = $col;
                        // $data[$row][$col]['details']['row'] = $row;
                        // $data[$row][$col]['details']['id'] = rand(time(), 100000000);
                        // $data[$row][$col]['details']['comment'] = null;
                        // $data[$row][$col]['details']['active'] = $col;
                        // $data[$row][$col]['details']['category'] = $send;
                        // $data[$row][$col]['details']['dateTime'] = $dateTime;
                        // $data[$row][$col]['details']['dialogue'] = $msg;
                        // $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                        // $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                        // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);

                    }
                }
            } else {
                if ($data[$row][$col]['details']['category'] == "dir" ||  $data[$row][$col]['details']['category'] == "iv" || $data[$row][$col]['details']['category'] == "oth") {
                    $totalChars = totalChars($storyDbConn);
                    $actInRow = false;
                    $actInRowCol = null;

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if ($data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        unset($data[$row][$actInRowCol]);
                        $data[$row][$col]['details']['category'] = $send;
                        $data[$row][$col]['details']['dateTime'] = $dateTime;
                        $data[$row][$col]['details']['dialogue'] = $msg;
                        $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                        $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                            if ($generatedRow) {
                                $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, ($row + 1));
                                if ($updatingCell) {
                                    $results['success'] = $updatingCell;
                                    $results['gen'] = $generatedRow;
                                    echo json_encode($results);
                                } else {
                                    echo json_encode($results);
                                }
                            }
                            // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row + 1], $row + 1);
                        }
                    } else {
                        $data[$row][$col]['details']['category'] = $send;
                        $data[$row][$col]['details']['dateTime'] = $dateTime;
                        $data[$row][$col]['details']['dialogue'] = $msg;
                        $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                        $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                        if ($updatingCell) {
                            $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                            if ($generatedRow) {
                                $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                                if ($insertingNewRow) {
                                    $results['success'] = "Hurrah 1!kkuu";

                                    echo json_encode($results);
                                } else {
                                    echo json_encode($results);
                                }
                            }
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "act") {
                    $data[$row][$col]['details']['category'] = $send;
                    $data[$row][$col]['details']['dateTime'] = $dateTime;
                    $data[$row][$col]['details']['dialogue'] = $msg;
                    $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                    $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $data[$row + 1][$col]['details']['category'] = 'human';
                        $data[$row + 1][$col]['details']['dateTime'] = $dateTime;
                        $data[$row + 1][$col]['details']['dialogue'] = $msg;
                        $data[$row + 1][$col]['details']['fileName'] = $col . '_Char_human.mp3';
                        $data[$row + 1][$col]['details']['numOfWords'] = str_word_count($msg);
                        $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row + 1], $row + 1);
                        if ($updatingCell) {
                            $results['success'] = "Hurrah!";
                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "human") {
                    $data[$row][$col]['details']['category'] = $send;
                    $data[$row][$col]['details']['dateTime'] = $dateTime;
                    $data[$row][$col]['details']['dialogue'] = $msg;
                    $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                    $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                            if ($insertingNewRow) {
                                $totalChars = totalChars($storyDbConn);

                                for ($i = 1; $i <= (int)$totalChars; $i++) {
                                    if ($data[$row - 1][$i]['details']['category'] == "act") {
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
                            } else {
                                echo json_encode($results);
                            }
                        }
                    } else {
                    }
                } else if ($data[$row][$col]['details']['category'] == "sa" || $data[$row][$col]['details']['category'] == "aux") {
                    // $data[$row][$col]['details']['category'] = $send;
                    // $data[$row][$col]['details']['dateTime'] = $dateTime;
                    // $data[$row][$col]['details']['dialogue'] = $msg;
                    // $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    // $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);


                    $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                    if ($updatingCell) {
                        $generatedRow =  generatingDialogue($row + 1, $col, $msg, 'human');
                        if ($generatedRow) {
                            $insertingNewRow = insertingRow($storyDbConn, $chapter, $row + 1, $generatedRow);
                            if ($insertingNewRow) {
                                $results['success'] = "Hurrah 1oiuo!";

                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        }
                    } else {
                        echo json_encode($results);
                    }
                }
            }
        } else if ($send == "sa" || $send == "aux") {
            if (!isset($data[$row])) {
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

                if ($catArray['sa'] || $catArray['aux']) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } else if ($catArray['human']) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            $totalChars = totalChars($storyDbConn);
                            for ($i = 1; $i <= (int)$totalChars; $i++) {
                                if ($data[$row - 1][$i]['details']['category'] == "act") {
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


                    $totalRows = (int)$storyDetails['totalRows'];
                    if ($totalRows < $row && ($totalRows + 1) != $row) {
                        $row = $totalRows + 1;
                    }
                    if ($totalRows < $row) {
                        # code...
                        $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    } else {
                        $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                        $insertingNewRow = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    }


                    // $totalChars = totalChars($storyDbConn);
                    // $actInRow = false;
                    // $actInRowCol = null;
                    // for ($i = 1; $i <= (int)$totalChars; $i++) {
                    //     if ($data[$row][$i]['details']['category'] == "act") {
                    //         $actInRow = true;
                    //         $actInRowCol = $i;
                    //         break;
                    //     }
                    // }
                    // if ($actInRow) {
                    //     $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    //     if ($generatedRow) {
                    //         $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                    //         if ($updatingCell) {
                    //             $deletingRow = deletingRow($storyDbConn, $chapter, $row + 1);
                    //             if ($deletingRow) {
                    //                 echo json_encode($results);
                    //             } else {
                    //                 echo json_encode($results);
                    //             }
                    //         }
                    //     }
                    // } else {
                    //     $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    //     if ($generatedRow) {
                    //         $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                    //         if ($updatingCell) {
                    //             echo json_encode($results);
                    //         } else {
                    //             echo json_encode($results);
                    //         }
                    //     }
                    // }
                }
            } else {
                if ($data[$row][$col]['details']['category'] == "sa" || $data[$row][$col]['details']['category'] == "aux") {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } //NOT POSSIBLE 
                else if ($data[$row][$col]['details']['category'] == "human") {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            $totalChars = totalChars($storyDbConn);
                            for ($i = 1; $i <= (int)$totalChars; $i++) {
                                if ($data[$row - 1][$i]['details']['category'] == "act") {
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
                    //NOT POSSIBLE
                } else if ($data[$row][$col]['details']['category'] == "act") {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            $deletingRow = deletingRow($storyDbConn, $chapter, $row + 1);
                            if ($deletingRow) {
                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "dir" || $data[$row][$col]['details']['category'] == "iv" || $data[$row][$col]['details']['category'] == "oth") {
                    $totalChars = totalChars($storyDbConn);
                    $actInRow = false;
                    $actInRowCol = null;
                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if ($data[$row][$i]['details']['category'] == "act") {
                            $actInRow = true;
                            $actInRowCol = $i;
                            break;
                        }
                    }
                    if ($actInRow) {
                        $generatedRow = generatingDialogue($row, $col, $msg, $send);
                        if ($generatedRow) {
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
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
                        $generatedRow = generatingDialogue($row, $col, $msg, $send);
                        if ($generatedRow) {
                            $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                            if ($updatingCell) {
                                echo json_encode($results);
                            } else {
                                echo json_encode($results);
                            }
                        }
                    }
                }
            }
        } else if ($send == "dir" || $send == "iv" || $send == "oth") {
            if (!isset($data[$row])) {
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
                if ($catArray['sa'] || $catArray['aux']) {
                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            $results['gggy'] = "fuckkbdfbgdfkkkk";

                            echo json_encode($results);
                        } else {
                            $results['gggy'] = "fuckkbdfbgktyu45t45gdfkkkk";

                            echo json_encode($results);
                        }
                    }
                } else if ($catArray['human']) {
                    $totalChars = totalChars($storyDbConn);

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if ($data[$row - 1][$i]['details']['category'] == "act") {
                            unset($data[$row - 1][$i]);
                            $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                            if ($updatingCell) {
                                //SOMETIMES ROW WILL BE EMPTY
                                $generatedRow = generatingDialogue($row, $col, $msg, $send);
                                if ($generatedRow) {
                                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
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
                        $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                        $insertingNewRow = insertingRow($storyDbConn, $chapter, $row, $generatedRow);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    } else {
                        $generatedRow =  generatingDialogue($row, $col, $msg, $send);
                        $insertingNewRow = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($insertingNewRow) {
                            $results['success'] = "Hurrah 1!jijoji";
                            $results['ar'] = json_encode($catArray);
                            echo json_encode($results);
                        }
                    }


                    // $data[$row][$col]['charCode'] = $col;
                    // $data[$row][$col]['details']['row'] = $row;
                    // $data[$row][$col]['details']['id'] = rand(time(), 100000000);
                    // $data[$row][$col]['details']['comment'] = null;
                    // $data[$row][$col]['details']['active'] = $col;
                    // $data[$row][$col]['details']['category'] = $send;
                    // $data[$row][$col]['details']['dateTime'] = $dateTime;
                    // $data[$row][$col]['details']['dialogue'] = $msg;
                    // $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    // $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                    // $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    // if ($updatingCell) {
                    //     $results['gggy'] = "fuckkfdbdfgkkkk";

                    //     echo json_encode($results);
                    // } else {
                    //     $results['gggy'] = "fuckkbdfbgdfkergwuwueruiwukkk";

                    //     echo json_encode($results);
                    // }
                }
            } else {
                //NOT POSSIBLE
                if ($data[$row][$col]['details']['category'] == "sa" || $data[$row][$col]['details']['category'] == "aux") {

                    $generatedRow = generatingDialogue($row, $col, $msg, $send);
                    if ($generatedRow) {
                        $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
                        if ($updatingCell) {
                            $results['fuckuu'] = "gbsdfnnerklvdsfkvg";

                            echo json_encode($results);
                        } else {
                            echo json_encode($results);
                        }
                    }
                } //NOT POSSIBLE
                else if ($data[$row][$col]['details']['category'] == "human") {
                    $totalChars = totalChars($storyDbConn);

                    for ($i = 1; $i <= (int)$totalChars; $i++) {
                        if ($data[$row - 1][$i]['details']['category'] == "act") {
                            unset($data[$row - 1][$i]);
                            $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row - 1], $row - 1);
                            if ($updatingCell) {
                                //SOMETIMES ROW WILL BE EMPTY
                                $generatedRow = generatingDialogue($row, $col, $msg, $send);
                                if ($generatedRow) {
                                    $updatingCell = updatingCellWithOutEncode($storyDbConn, $chapter, $generatedRow, $row);
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

                    $data[$row][$col]['details']['category'] = $send;
                    $data[$row][$col]['details']['dateTime'] = $dateTime;
                    $data[$row][$col]['details']['dialogue'] = $msg;
                    $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                    $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $deletingRow = deletingRow($storyDbConn, $chapter, $row + 1);
                        if ($deletingRow) {
                            $results['fuckuu'] = "gbdvdfvsfeg";

                            echo json_encode($results);
                        } else {
                            $results['fuckuu'] = "gbsdvdsvsfeg";

                            echo json_encode($results);
                        }
                    }
                } else if ($data[$row][$col]['details']['category'] == "dir" || $data[$row][$col]['details']['category'] == "iv" || $data[$row][$col]['details']['category'] == "oth") {
                    $data[$row][$col]['details']['category'] = $send;
                    $data[$row][$col]['details']['dateTime'] = $dateTime;
                    $data[$row][$col]['details']['dialogue'] = $msg;
                    $data[$row][$col]['details']['fileName'] = $col . '_Char_' . $send . '.mp3';
                    $data[$row][$col]['details']['numOfWords'] = str_word_count($msg);
                    $updatingCell = updatingCell($storyDbConn, $chapter, $data[$row], $row);
                    if ($updatingCell) {
                        $results['fuckuu'] = "gbsfereeg";

                        echo json_encode($results);
                    } else {
                        $results['fuckuu'] = "gbsfedfsvfdvg";

                        echo json_encode($results);
                    }
                }
            }
        }
        // } else {
        //     //NEW DIALOGUE
        //     if ($send == "act") {
        //         $dialogueAct = generatingDialogue($row, $col, $msg, $send);
        //         $dialogueHuman = generatingDialogue($row + 1, $col, $msg, 'human');
        //         $newRowAct = newRow($storyDbConn, $chapter, $storyDetails['totalRows'] + 1, $dialogueAct);
        //         $newRowHuman = newRow($storyDbConn, $chapter, $storyDetails['totalRows'] + 2, $dialogueHuman);
        //         if ($newRowAct && $newRowHuman) {
        //             $results['gggy'] = "fuckkbdfbgddfgdfgbdfkkkk";

        //             echo json_encode($results);
        //         } else {
        //             echo json_encode($results);
        //         }
        //     } else {
        //         $dialogue = generatingDialogue($row, $col, $msg, $send);
        //         $newRow = newRow($storyDbConn, $chapter, $storyDetails['totalRows'] + 1, $dialogue);
        //         if ($newRow) {
        //             $results['gggy'] = $data[$row];

        //             echo json_encode($results);
        //         } else {
        //             echo json_encode($results);
        //         }
        //     }
        // }
    }
} else {
    $results['msg'] = $userData['msg'];
    $results['errorCode'] = $userData['errorCode'];
    echo json_encode($results);
}
function updatingDialogue()
{
}
function generatingDialogue($row, $col, $dialogueText, $cat)
{

    $id = rand(time(), 100000000);
    $dateTime = (new DateTime())->format("Y-m-d H:i:s");
    $numOfWords = str_word_count($dialogueText);
    $dialogue = "";
    $dialogue = '{
        "' . $col . '" : {
          "charCode" :  ' . $col . ',
          "details" : {
            "row" : ' . $row . ',
            "id" : ' . $id . ',
            "dateTime" : "' . $dateTime . '",
            "comment" : null,
            "dialogue" : "' . $dialogueText . '",
            "fileName" : "' . $col . '_Char_' . $cat . '.mp3",
            "numOfWords" : ' . $numOfWords . ',
            "category" : "' . $cat . '",
            "active" : ' . $col . '
          }
        }
      }';

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
function insertingRow($conn, $chapter, $lineNumber, $dialogue)
{
    $updatingLineNum = mysqli_query($conn, "UPDATE story_" . $chapter . " SET lineNumber=lineNumber+1 WHERE lineNumber > " . (int)$lineNumber - 1 . " ORDER BY lineNumber ASC");
    $lineCode = rand(time(), 100000000);
    $insertingRow = mysqli_query($conn, "INSERT INTO story_" . $chapter . " (lineNumber,lineCode,dialogue,status) VALUES ('{$lineNumber}','{$lineCode}','{$dialogue}',1)");
    if ($insertingRow && $updatingLineNum) {
        return true;
    } else {
        return false;
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
    $updatingCell = mysqli_query($conn, "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'");
    if ($updatingCell) {
        return true;
    } else {
        return false;
    }
}
function updatingCellWithOutEncode($conn, $chapter, $dialogue, $row)
{
    // $sql = "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'";
    $updatingCell = mysqli_query($conn, "UPDATE story_" . $chapter . " SET dialogue='{$dialogue}' WHERE lineNumber='{$row}'");
    if ($updatingCell) {
        return true;
    } else {
        return false;
    }
}
