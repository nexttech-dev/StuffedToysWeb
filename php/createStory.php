<?php

session_start();
include_once("connections/main.php");

$cid = $_SESSION['cid'];
$storyName =  mysqli_real_escape_string($conn, $_POST['storyName']);

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

$results = array('result' => false, 'msg' => null, 'data' => null, 'errorCode' => null, 'error' => true);

if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);

    $userId = $row['uid'];
    $userName = $row['fullName'];
    $storyId = rand(time(), 100000000);
    $storyDbName = $storyName . "-" . $storyId;
    $storyChars = 2;
    $dateTime = (new DateTime($test))->format("Y-m-d H:i:s");
    $configDetails_sheet = '{
        "autoFeed": false,
        "pointer": true,
        "typing": true,
        "extendedView": false
      }
      ';
    $configDetails_totalChars = '{
        "totalChars": 2
      }
      ';

    $charDetails_1 = '{
          "charCode": "1",
          "charName": "Char 1",
          "charColor": "#340459",
          "charCat" : "mainChar",
          "fontStyle": {
            "color": {
                "act" : "#FFFFFF",
                "human" : "#FFFFFF",
                "dir" : "#FFFFFF",
                "iv" : "#FFFFFF",
                "sa" : "#FFFFFF",
                "aa" : "#FFFFFF"
            },
            "size": "14",
            "bold": "normal",
            "italic": "normal",
            "underline": "none",
            "family" : "Noto Sans"
          }
        }
        ';
    $charDetails_2 = '{
        "charCode": "2",
        "charName": "Char 2",
        "charColor": "#200563",
        "charCat" : "mainChar",
        "fontStyle": {
            "color": {
                "act" : "#FFFFFF",
                "human" : "#FFFFFF",
                "dir" : "#FFFFFF",
                "iv" : "#FFFFFF",
                "sa" : "#FFFFFF",
                "aa" : "#FFFFFF"
            },
            "size": "14",
            "bold": "normal",
            "italic": "normal",
            "underline": "none",
            "family" : "Noto Sans"
        }
        }
        ';


    include_once("connections/server.php");

    $creatingStoryDb =  mysqli_query($backEndConn, "CREATE DATABASE IF NOT EXISTS `$storyDbName`");
    if ($creatingStoryDb) {
        include_once("connections/storyDb.php");

        $sheetTable =  creatingSheetTable($storyDbConn);
        $charInfoTable =  creatingCharInfoTable($storyDbConn);
        $configTable =  creatingConfigTable($storyDbConn);
        $pointerTable =  creatingPointerTable($storyDbConn);

        if ($sheetTable && $charInfoTable && $configTable && $pointerTable) {



            $configurations_sheet = configTableInput($storyDbConn, 'sheetConfigs', $configDetails_sheet);
            $configurations_totalChars = configTableInput($storyDbConn, 'totalChars', $configDetails_totalChars);
            $configurations_totalChars = configTableInput($storyDbConn, 'saves', '{}');



            $charInfoUpdate_1 = charInfoTableInput($storyDbConn, $storyName, '1', $storyId, $charDetails_1, $dateTime);
            $charInfoUpdate_2 = charInfoTableInput($storyDbConn, $storyName, '2', $storyId, $charDetails_2, $dateTime);

            if ($configurations_sheet && $configurations_totalChars && $charInfoUpdate_1 && $charInfoUpdate_2) {
                include_once("connections/userDb.php");
                $userStoryUpdate = userStoriesTableInput($userDbConn, $storyName, '2', $storyId, $userName, $dateTime, $storyDbName);
                if ($userStoryUpdate) {
                    $results['msg'] = "Story created successfully!";
                    $results['errorCode'] = null;
                    $results['result'] = true;
                    $results['data'] =  $storyId;
                    echo json_encode($results);
                } else {

                    $results['msg'] = "Story creation failed! Please try again.";
                    $results['errorCode'] = "CSX005";
                    echo json_encode($results);
                }
            } else {
                $results['msg'] = "Story creation failed! Please try again.";
                $results['errorCode'] = "CSX004";
                $results['ss'] = $configurations_sheet . $configurations_totalChars . $charInfoUpdate_1 . $charInfoUpdate_2;

                echo json_encode($results);
            }
        } else {
            $results['msg'] = "Story creation failed! Please try again.";
            $results['errorCode'] = "CSX003";
            echo json_encode($results);
        }
    } else {
        $results['msg'] = "Story creation failed! Please try again.";
        $results['errorCode'] = "CSX002";
        echo json_encode($results);
    }
} else {
    $results['msg'] = "No user found against this cid";
    $results['errorCode'] = "CSX001";
    echo json_encode($results);
}




////////TABLE CREATIONS - START/////////
////////TABLE CREATIONS - START/////////
////////TABLE CREATIONS - START/////////
////////TABLE CREATIONS - START/////////
////////TABLE CREATIONS - START/////////

function creatingConfigTable($conn)
{
    $creatingConfigTable = "CREATE TABLE config (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        configName VARCHAR(255),
        configDetails JSON,
        status  INT(11))";
    $creatingConfigTable = mysqli_query($conn, $creatingConfigTable);

    if ($creatingConfigTable) {
        return true;
    } else {
        return false;
    }
}
function creatingPointerTable($conn)
{
    $creatingPointerTable = "CREATE TABLE pointer (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        charCode VARCHAR(255),
        `row`  INT(11),
        `col`  INT(11),
        text  LONGTEXT,
        chapter VARCHAR(255),
        comment BOOLEAN,
        status BOOLEAN,
        unique key(charCode))";
    $creatingPointerTable = mysqli_query($conn, $creatingPointerTable);

    if ($creatingPointerTable) {
        return true;
    } else {
        return false;
    }
}
function creatingCharInfoTable($conn)
{
    $creatingCharInfoTable = mysqli_query($conn, "CREATE TABLE charInfo (
        id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        storyName VARCHAR(255) NOT NULL,
        storyId INT(11) NOT NULL,
        dateTime VARCHAR(255) NOT NULL,
        charCode VARCHAR(255),
        charDetails  JSON,
        status BOOLEAN)");

    if ($creatingCharInfoTable) {
        return true;
    } else {
        return false;
    }
}
function creatingSheetTable($conn)
{
    $creatingSheet = mysqli_query($conn, "CREATE TABLE story_1_Chapter1 (`id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY, `lineNumber` INT(11) NOT NULL, `lineCode` INT(11) NOT NULL,`dialogue` JSON,`status` BOOLEAN,`owner` VARCHAR(255)) ");
    if ($creatingSheet) {
        return true;
    } else {
        return false;
    }
}



////////TABLE CREATIONS - END/////////
////////TABLE CREATIONS - END/////////
////////TABLE CREATIONS - END/////////
////////TABLE CREATIONS - END/////////
////////TABLE CREATIONS - END/////////



////////TABLE INPUTS - START/////////
////////TABLE INPUTS - START/////////
////////TABLE INPUTS - START/////////
////////TABLE INPUTS - START/////////
////////TABLE INPUTS - START/////////

function userStoriesTableInput($conn, $storyName, $totalChars, $storyId, $userName, $dateTime, $storyDbName)
{
    $storyInputUserDb = mysqli_query($conn, "INSERT INTO stories (storyName,storyChars,storyId,startedBy,date,time,status,storyDbName) VALUES ('{$storyName}','{$totalChars}','{$storyId}','{$userName}','{$dateTime}','{$dateTime}',1,'{$storyDbName}')");

    if ($storyInputUserDb) {
        return true;
    } else {
        return false;
    }
}

function charInfoTableInput($conn, $storyName, $charCode, $storyId, $charDetails, $dateTime)
{
    $addingChar = mysqli_query($conn, "INSERT INTO charInfo (storyName,storyId,dateTime,charCode,charDetails,status) VALUES ('{$storyName}','{$storyId}','{$dateTime}','{$charCode}','{$charDetails}',1)");
    if ($addingChar) {
        return true;
    } else {
        return false;
    }
}

function configTableInput($conn, $configName, $configDetails)
{
    $addingConfigurations = mysqli_query($conn, "INSERT INTO config (configName,configDetails,status) VALUES ('{$configName}','{$configDetails}',1)");
    if ($addingConfigurations) {
        return true;
    } else {
        return false;
    }
}
////////TABLE INPUTS - END/////////
////////TABLE INPUTS - END/////////
////////TABLE INPUTS - END/////////
////////TABLE INPUTS - END/////////
////////TABLE INPUTS - END/////////
