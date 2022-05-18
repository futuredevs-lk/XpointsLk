<?php 
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

if(isset($_POST['submit_ur_post'])){
    $post_file = uploadFiles($_FILES['post_file'],"__user_uploads/user__".$_SESSION['user']['username']."/shop");
    
    if(dbcmd("INSERT INTO user_shop(
        seller,
        mc_id,
        sc_id,
        image,
        name,
        price,
        description
        ) 
        VALUES(
        ".$_SESSION['user']['id'].",
        ".$_POST['main_cat_id'].",
        ".$_POST['sub_cat_id'].",
        '$post_file',
        '".$_POST['post_title']."',
        ".$_POST['post_price'].",
        '".$_POST['post_desc']."')
    ")){
        echo "user-post-submit: success;";
    }
}
 require($_SERVER["DOCUMENT_ROOT"]. "/assets/php/db/dbclose.php");
?>