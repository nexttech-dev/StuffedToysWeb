<?php

$sessionId = $_SESSION['cid'];

$sessionRegValidation = mysqli_query($conn, "SELECT * FROM signedInUsers WHERE sessionId = '{$sessionId}'");

if (mysqli_num_rows($sessionRegValidation) == 1) {
} else if (mysqli_num_rows($sessionRegValidation) == 0) {
} else {
}
