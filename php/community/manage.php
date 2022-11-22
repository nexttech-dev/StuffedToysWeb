<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];

$gettingUID = mysqli_query($conn, "SELECT * FROM personalInfo WHERE cid = '{$cid}'");

if (mysqli_num_rows($gettingUID) == 1) {
    $row = mysqli_fetch_assoc($gettingUID);
    $userId = $row['uid'];
    $userName = $row['fullName'];
}

//echo $userId.'--'.$userName;

function save_topic(){
    global $conn,$userId;
    extract($_POST);
    $data = " title = '$title' ";
    $data .= ", category_ids = '' ";
//    $data .= ", category_ids = '".(implode(",",$category_ids))."' ";
    $data .= ", content = '".htmlentities(addcslashes(str_replace("'","&#x2019;",$content)))."' ";

    if(empty($id)){
        $data .= ", user_id = '{$userId}' ";
        $save = $conn->query("INSERT INTO topics set ".$data);
    }else{
        $save = $conn->query("UPDATE topics set ".$data." where id=".$id);
    }
    if($save)
        return 1;
    return 0;
}
function save_comment(){
    global $conn,$userId;
    extract($_POST);
    $data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

    if(empty($id)){
        $data .= ", topic_id = '$topic_id' ";
        $data .= ", user_id = '{$userId}' ";
        $save = $conn->query("INSERT INTO comments set ".$data);
    }else{
        $save = $conn->query("UPDATE comments set ".$data." where id=".$id);
    }
    if($save)
        return 1;

    return 0;
}
function delete_topic(){
    global $conn,$userId;
    extract($_POST);
    $delete = $conn->query("DELETE FROM topics where id = ".$id);
    if($delete){
        return 1;
    }
    return 0;
}
function delete_comment(){
    global $conn,$userId;
    extract($_POST);
    $delete = $conn->query("DELETE FROM comments where id = ".$id);
    if($delete){
        return 1;
    }
    return 0;
}

if(isset($_REQUEST['action'])){
    $error='';$return=0;
    try {

        switch ($_REQUEST['action']){
            case 'save_topic':
            case 'update_topic':
//                echo json_encode($_POST);exit;
                extract($_POST);

            $data = " title = '$title' ";
            $data .= ", category_ids = '' ";
//    $data .= ", category_ids = '".(implode(",",$category_ids))."' ";
            $data .= ", content = '".htmlentities(addslashes(str_replace("'","&#x2019;",$content)))."' ";

            if(empty($id)){
                $data .= ", user_id = '{$userId}' ";
                $query="INSERT INTO topics set ".$data;
                $res['query']=$query;

                $save = $conn->query($query);
                $res['db_error']=$save;

//                echo json_encode($res);exit;
            }else{
                $save = $conn->query("UPDATE topics set ".$data." where id=".$id);
            }
            if($save) {
                $return = 1;
            }
                break;
            case 'topic_delete':
                $delete = $conn->query("DELETE FROM topics where id = ".(int)$_REQUEST['id']);
                if($delete) {
                    $return = 1;
                }
                break;
            case 'save_comment':
            case 'update_comment':
            extract($_POST);
            $data = " comment = '".htmlentities(str_replace("'","&#x2019;",$comment))."' ";

            if(empty($id)){
                $data .= ", topic_id = '$topic_id' ";
                $data .= ", user_id = '{$userId}' ";
                $save = $conn->query("INSERT INTO comments set ".$data);
            }else{
                $save = $conn->query("UPDATE comments set ".$data." where id=".$id);
            }
            if($save){
                $return=1;
            }
                break;
            case 'comment_delete':
                $delete = $conn->query("DELETE FROM comments where id = ".(int)$_REQUEST['id']);
                if($delete){
                    $return=1;
                }
                break;
        }
    }catch (Exception $e){
        $error=$e;
    }
    echo json_encode(['result'=>$return,'error'=>$error]);
    exit;
}