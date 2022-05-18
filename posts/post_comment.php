<?php 
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbconfig.php");
echo json_encode('');
$post_id = $_POST['post_id'];
$cur_user= $_SESSION['user']['id'];
$comment = $_POST['comment'];
$post = 1;
if(1){
        $q = $db->prepare("INSERT INTO comment(post_id,user_id,comment,post) 
                                                    VALUES
                                                    (?,?,?,?)");
        $q->bind_param("iisi",
                        $post_id,
                        $cur_user,
                        $comment,
                        $post
                        );
        $q->execute();
}
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
?>