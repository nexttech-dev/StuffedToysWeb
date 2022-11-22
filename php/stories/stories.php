<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];

    $userProfile = '
    <div class="details">
        <span>' . $userName . '</span>
    </div>';

    include_once("../connections/userDb.php");
    $accessingStoriesDb = mysqli_query($userDbConn, "SELECT * FROM stories");

    $numberOfRows = mysqli_num_rows($accessingStoriesDb);

    $count = 1;

    if (mysqli_num_rows($accessingStoriesDb) >> 0) {
        while ($stories = mysqli_fetch_assoc($accessingStoriesDb)) {
            $offline = "offline";
            $output .= '<tr>
        <td>' . $stories['storyName'] . '</td>
        <td><span class="status inprogress">In Progress</span></td>
        <td><button class="createStoryButton" id="' . $stories['storyId'] . '" onclick="continueStory(this)">Continue Story</button></td>
        <td><button class="deleteStory" id="' . $stories['storyId'] . '" onclick="deleteStory(this)">Delete</button></td>
    </tr>';
            if ($count == $numberOfRows) {
                echo json_encode(array('profile' => $userProfile, 'stories' => $output));
            }
            $count++;
        }
    } else {
        echo "No stories are found";
    }
} else {
    echo "No user found against this cid";
}
