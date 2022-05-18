<?php 
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

if(isset($_POST['submit_ur_post'])){
    $post_file = uploadFiles($_FILES['post_file'],"__user_uploads/user__".$_SESSION['user']['username']."/posts");

    
    if(dbcmd("INSERT INTO user_post(
        user_id,
        main_cat_id,
        sub_cat_id,
        post_file,
        post_title,
        post_desc
        ) 
        VALUES(
        ".$_SESSION['user']['id'].",
        ".$_POST['main_cat_id'].",
        ".$_POST['sub_cat_id'].",
        '$post_file',
        '".$_POST['post_title']."',
        '".$_POST['post_desc']."')
    ")){
        echo "user-post-submit: success;";
    }
}
?>