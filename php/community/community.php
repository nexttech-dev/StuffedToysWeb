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
    </style>
    <div class="col-lg-12">
        <div class="row mb-4 mt-4">
            <div class="col-md-12">

            </div>
        </div>
        <div class="row">
            <!-- FORM Panel -->

            <!-- Table Panel -->
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <b>Topic List</b>
                        <span class="">

							<button class="btn btn-primary btn-block btn-sm col-sm-2 float-right text-white" type="button" onclick="popUpCreateTopic()" id="new_topic">
					<i class="fa fa-plus"></i> Create Topic</button>
				</span>
                    </div>
                    <div class="card-body">
                        <ul class="w-100 list-group" id="topic-list">
                            <?php
                            $tag = $conn->query("SELECT * FROM categories order by name asc");
                            if($tag->num_rows>0){
                                while($row= $tag->fetch_assoc()):
                                    $tags[$row['id']] = $row['name'];
                                endwhile;
                            }

                            $topic = $conn->query("SELECT t.*,u.fullName FROM topics t inner join personalinfo u on u.uid = t.user_id order by unix_timestamp(date_created) desc");
                            if($topic->num_rows>0):
                            while($row= $topic->fetch_assoc()):
                                $trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
                                unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
                                $desc = strtr(html_entity_decode($row['content']),$trans);
                                $desc=str_replace(array("<li>","</li>",'&amp;'), array("",",","&"), $desc);
                                $view = $conn->query("SELECT * FROM forum_views where topic_id=".$row['id'])->num_rows;
                                $comments = $conn->query("SELECT * FROM comments where topic_id=".$row['id'])->num_rows;
//                                $replies = $conn->query("SELECT * FROM replies where comment_id in (SELECT id FROM comments where topic_id=".$row['id'].")")->num_rows;
//                                echo $userId .'----'.$row['user_id'];
                                ?>
                                <li class="list-group-item mb-4">
                                    <div>
                                        <a href="javascript:view_topic(<?php echo $row['id'] ?>);" onclick="view_topic(<?php echo $row['id'] ?>);"
                                           class=" filter-text"><?php echo $row['title'] ?></a>
                                        <?php if($userId == $row['user_id'] ): ?>
                                            <button class="btn edit_topic" data-id="<?php echo $row['id'] ?>" onclick="javascript:void(0)">Edit</button>
                                            <button class="btn  delete_topic" data-id="<?php echo $row['id'] ?>" onclick="delete_topic(<?php echo $row['id'] ?>)">Delete</button>
                                        <?php endif; ?>
                                        <span class="float-right mr-4"><small><i>Created: <?php echo date('M d, Y h:i A',strtotime($row['date_created'])) ?></i></small></span>

                                    </div>
                                    <hr>
                                    <p class="truncate filter-text m-5"><?php echo strip_tags($desc) ?></p>
                                    <p class="row justify-content-left mb-4"><span class="badge badge-success text-white"><i>Posted By: <?php echo $row['fullName'] ?></i></span></p>
                                    <hr>

                                    <span class="float-left badge badge-secondary text-white mt-1"><?php echo number_format($view) ?> view/s</span>
                                    <span class="float-left badge badge-primary text-white ml-2 mt-1"><i class="fa fa-comments"></i> <?php echo number_format($comments) ?> comment/s  </span>
                                </li>
                            <?php endwhile;
                            endif;
                            ?>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- Table Panel -->
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