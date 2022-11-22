<?php

session_start();
include_once "config.php";
$outgoing_id = $_SESSION['cid'];
$searchTerm = mysqli_real_escape_string($conn, $_POST['searchTerm']);

$sql = "SELECT * FROM personalInfo WHERE NOT cid = {$outgoing_id} AND (fullName LIKE '%{$searchTerm}%' OR userName LIKE '%{$searchTerm}%') ";
$query = mysqli_query($conn, $sql);
$output = "";
if (mysqli_num_rows($query) == 0) {
    $output .= "<tr><td>No users are available to create story</td><td></td><td></td><td></td></tr>";
} elseif (mysqli_num_rows($query) > 0) {
    $count = 0;
    while ($row = mysqli_fetch_assoc($query)) {
        $count++;

        $output .= '<tr>
    <td>' . $row['fullName'] . '</td>
    <td>' . $row['userName'] . '</td>
    <td><span class="status delivered">Availible</span></td>
    <td><button class="createStoryButton" id="' . $count . '"  onclick="createStoryButton(this)">Create Story</button></td>
</tr>
<tr class="storyInfo" id="storyInfo' . $count . '" style="display:none;">
    <td><input type="text" placeholder="Story Name" id="storyName' . $count . '"></td>
    <td><input type="numbers" placeholder="Total Number of Characters" id="totalChars' . $count . '"></td>
    <td><button class="createStory" onclick="sendReq(' . $row['uid'] . ',' . $count . ')">Send Request</button></td>
    <td></td>
</tr>
';
    }
}
echo $output;
