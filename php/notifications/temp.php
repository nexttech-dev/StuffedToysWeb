<?php
echo date("h:i:s");
 // $creatingRequestsOutTable = mysqli_query($storyDbConn, "CREATE TABLE requestsOutgoing (
                //             id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                //             recipient VARCHAR(255) NOT NULL,
                //             recipientName VARCHAR(255) NOT NULL,
                //             storyName VARCHAR(255) NOT NULL,
                //             characters VARCHAR(255) NOT NULL,
                //             date VARCHAR(255) NOT NULL,
                //             time VARCHAR(255) NOT NULL,
                //             status VARCHAR(255) NOT NULL,
                //             storyId INT(11) NOT NULL
                //         )");
                // $creatingRequestsInTable = mysqli_query($storyDbConn, "CREATE TABLE requestsIncoming (
                //             id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                //             sender VARCHAR(255) NOT NULL,
                //             senderName VARCHAR(255) NOT NULL,
                //             storyName VARCHAR(255) NOT NULL,
                //             characters VARCHAR(255) NOT NULL,
                //             date VARCHAR(255) NOT NULL,
                //             time VARCHAR(255) NOT NULL,
                //             status VARCHAR(255) NOT NULL,
                //             storyId INT(11) NOT NULL
                //         )");

                // if ($creatingNotificationTable && $creatingRequestsOutTable && $creatingRequestsInTable) {
                //     $insert_query = mysqli_query($conn, "INSERT INTO personalInfo (uid, cid, fullName, userName, email, pwd, status)
                //             VALUES ({$uid}, '{$cid}','{$fullName}', '{$userName}', '{$email}', '{$encrypt_pass}', '{$status}')");
                //     if ($insert_query) {
                //         $insert_query_1 = mysqli_query($conn, "INSERT INTO regUsers (uid, userName,  fullName)
                //                 VALUES ({$uid}, '{$userName}', '{$fullName}')");
                //         if ($insert_query_1) {
                //             $select_sql2 = mysqli_query($conn, "SELECT * FROM personalInfo WHERE email = '{$email}'");
                //             if (mysqli_num_rows($select_sql2) == 1) {
                //                 $result = mysqli_fetch_assoc($select_sql2);
                //                 $_SESSION['cid'] = $result['cid'];
                //                 $registeringSignIn = mysqli_query($conn, "INSERT INTO signedInUsers (uid,sessionId) VALUES ('{$uid}','{$result['cid']}')");
                //                 if ($registeringSignIn) {
                //                     echo "success";
                //                 } else {
                //                     echo "Everything Fine! Just login failed.";
                //                 }
                //             } else {
                //                 echo "Something went wrong. Please contact admin!";
                //             }
                //         } else {
                //             echo "Something went wrong. Please try again!";
                //         }
                //     } else {
                //         echo "Something went wrong. Please try again!";
                //     }
                // } else {
                //     echo "Error while creating tables for users database!";
                // }