<?php

session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

// echo $storyId, $charName, $charNewName;
if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];
    include_once("../connections/userDb.php");
    $accessingStoryDB = mysqli_query($userDbConn, "SELECT * FROM stories");
    if (mysqli_num_rows($accessingStoryDB) >> 0) {
        $totalWords = 0;
        $totalStories = 0;
        $totalCompletedStories = 0;
        while ($stories = mysqli_fetch_assoc($accessingStoryDB)) {
            $totalStories = $totalStories + 1;
            $char = "";

            $storyDbName = $stories['storyDbName'];
            $storyChars = $stories['storyChars'];
            if ($stories['status'] == 0) {
                $totalCompletedStories = $totalCompletedStories + 1;
            }
            include("../connections/storyDb.php");
            $storiesInfoTable = mysqli_query($storyDbConn, "SELECT * FROM storiesInfo");
            if (mysqli_num_rows($storiesInfoTable) >= 0) {
                $storiesInfoRow =  mysqli_fetch_assoc($storiesInfoTable);
                // echo $storiesInfoRow['storyName'];
                for ($i = 1; $i <= (int)$storyChars; $i++) {
                    if ($storiesInfoRow[(string)$i . '_charController'] == $userId) {
                        $char = (string)$i;
                        $charName = $storiesInfoRow[(string)$i . '_charName'];
                        $charColor = $storiesInfoRow[(string)$i . '_color'];
                        $i =  (int)$storyChars;
                    }
                }
                if ($char) {
                    $storyDetails = mysqli_query($storyDbConn, "SELECT * FROM story");
                    if ($storyDetails) {
                        if (mysqli_num_rows($storyDetails) >> 0) {
                            while ($msgRow = mysqli_fetch_assoc($storyDetails)) {
                                $totalWords = $totalWords + (int)$msgRow[$char . '_totalNumberOfWords'];
                            }
                        } else {
                            $totalWords = $totalWords + 0;
                        }
                    }
                } else {
                    $totalWords = $totalWords + 0;
                }
            } else {
                $totalWords = $totalWords + 0;
            }
        }
        echo json_encode(array('result' => $totalWords, 'totalStories' => $totalStories, 'totalCompletedStories' => $totalCompletedStories, "success" => true, 'error' => false));
    }
} else {
    echo json_encode(array('result' => "No user found against this cid", "success" => false, 'error' => true));
}
