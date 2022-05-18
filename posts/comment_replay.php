<?php 
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbconfig.php");
echo json_encode('');
$post_id = $_POST['post_id'];
$cmt_id = $_POST['comment_id'];
$cur_user= $_SESSION['user']['id'];
$comment = $_POST['comment'];
if(1){
        $q = $db->prepare("INSERT INTO comment(post_id,comment_id,user_id,comment) 
                                                    VALUES
                                                    (?,?,?,?)");
        $q->bind_param("iisi",
                        $post_id,
                        $cmt_id,
                        $cur_user,
                        $comment
                        );
        $q->execute();
}
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
?>