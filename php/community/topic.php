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
if(isset($_REQUEST['id'])){
    $topic_id=$_REQUEST['id'];
$qry = $conn->query("SELECT t.*,u.fullName FROM topics t inner join personalInfo u on u.uid = t.user_id where t.id= ".$topic_id);
foreach($qry->fetch_array() as $k => $val){
    $$k=$val;
}
$comments = $conn->query("SELECT c.*,u.fullName,u.username FROM comments c inner join personalInfo u on u.uid = c.user_id where c.topic_id= ".$topic_id." order by unix_timestamp(c.date_created) asc");
$com_arr= array();
while($row= $comments->fetch_assoc()){
    $com_arr[] = $row;
}
//$replies = $conn->query("SELECT r.*,u.fullName,u.username FROM replies r inner join personalInfo u on u.id = r.user_id where r.comment_id in (SELECT id FROM comments where topic_id= ".$topic_id.") order by unix_timestamp(r.date_created) asc");
//$rep_arr= array();
//while($row= $replies->fetch_assoc()){
//    $rep_arr[$row['comment_id']][] = $row;
//}
if($user_id != $userId){
    $chk = $conn->query("SELECT * FROM forum_views where  topic_id=$id and user_id='{$userId}' ")->num_rows;
    if($chk <= 0){
        $conn->query("INSERT INTO forum_views set  topic_id=$id , user_id='{$userId}' ");
    }
}
$view = $conn->query("SELECT * FROM forum_views where topic_id=$id")->num_rows;
ob_start();
?>
<div class="container-fluid">
    <style>
        input[type=checkbox]
        {
            /* Double-sized Checkboxes */
            -ms-transform: scale(1.5); /* IE */
            -moz-transform: scale(1.5); /* FF */
            -webkit-transform: scale(1.5); /* Safari and Chrome */
            -o-transform: scale(1.5); /* Opera */
            transform: scale(1.5);
            padding: 10px;
        }
        .list-group-item +  .list-group-item {
            border-top-width: 1px !important;
        }

        .avatar {
            display: flex;
            border-radius: 100%;
            width: 100px;
            height: 100px;
            align-items: center;
            justify-content: center;
            border: 3px solid;
            padding: 5px;
        }
        .avatar img {
            max-width: calc(100%);
            max-height: calc(100%);
            border-radius: 100%;
        }
        p{
            margin:unset;
        }
        #content{
            max-height: 60vh;
            overflow: auto;
        }
        #content pre	{
            background: #80808091;
            padding:5px;
        }
    </style>
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">
                <button type="button" class="createStoryButton" onclick="loadCommunity()">Back To list</button>
            </div>
        </div>
        <div class="row">
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <h3 class=" filter-text" style="display: inline"><?php echo $title ?></h3>
                            <?php if($userId == $user_id ): ?>
<!--                                <button class="btn edit_topic" data-id="--><?php //echo $id ?><!--" onclick="javascript:void(0)">Edit</button>-->
                                <button class="btn  delete_topic" style="display: inline" data-id="<?php echo $id ?>" onclick="delete_topic(<?php echo $id ?>)">Delete</button>
                            <?php endif; ?>
                            <span class="float-right mr-4" style="display: inline"><small><i>Created: <?php echo date('M d, Y h:i A',strtotime($date_created)) ?></i></small></span>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="truncate filter-text m-5"><?php echo html_entity_decode($content) ?></p>
                        <p class="row justify-content-left mb-4"><span class="badge badge-success text-white"><i>Posted By: <?php echo $fullName ?></i></span></p>
                        <hr>

                        <span class="float-left badge badge-secondary text-white mt-1"><?php echo number_format($view) ?> view/s</span>
                        <span class="float-left badge badge-primary text-white ml-2 mt-1"><i class="fa fa-comments"></i> <?php echo number_format($view) ?> comment/s  </span>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div class="row">
                            <h3><b> <i class="fa fa-comments"></i> Comment/s</b></h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-lg-12">
<!--                            <hr class="divider" style="max-width: 100%">-->
                            <?php
                            foreach($com_arr as $row):
                                ?>
                                <div class="form-group comment mt-1 mb-4">
                                    <p class="mb-0" style="display: inline"><large><b><?php echo $row['fullName'] ?></b></large> </p>
                                    <?php if($userId == $row['user_id']): ?>
<!--                                                <a class="dropdown-item edit_comment" data-id="--><?php //echo $row['id'] ?><!--" href="javascript:void(0)">Edit</a>-->
                                                <a class="dropdown-item delete_comment " style="display: inline" data-id="<?php echo $row['id'] ?>" href="javascript:delete_comment(<?php echo $row['id'] ?>,<?php echo $id ?>)">Delete this Comment</a>
                                    <?php endif; ?>
                                    <span class="float-right mr-4" style="display: inline"><small><i>Created: <?php echo date('M d, Y h:i A',strtotime($row['date_created'])) ?></i></small></span>
                                    <br>
                                    <p class="my-4" style="margin-top: 1.75rem">
                                        <?php echo html_entity_decode($row['comment']) ?>
                                    </p>
                                </div>
                                <hr>
                            <?php endforeach; ?>
                        </div>
                        <hr class="divider" style="max-width: 100%">
                        <div class="col-lg-12">
                            <form action="" id="manage-comment">
                                <div class="form-group">
                                    <input type="hidden" name="id" value="">
                                    <input type="hidden" name="action" value="save_comment">
                                    <input type="hidden" name="topic_id" value="<?php echo isset($id) ? $id : '' ?>">
                                    <textarea class="form-control text-jqte" id="comment-txt" name="comment" cols="30" rows="5" placeholder="New Comment"></textarea>
                                </div>
                                <button class="btn btn-primary" type="button" onclick="saveComment('manage-comment',<?php echo isset($id) ? $id : '' ?>)">Save Comment</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
    $output=ob_get_contents();
    ob_clean();
    ob_flush();

    $arr['community']=$output;
    $arr['status']=200;

    echo json_encode($arr);

exit;
}

$arr['community']='Topic Not Found';
$arr['status']=200;

echo json_encode($arr);
exit;

?>