<?php 
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbconfig.php");
$post = 0;
$sql = "SELECT * FROM `user_likes` WHERE user_id=".$_SESSION['user']['id']." AND post_or_article_id =".$_POST['id']." AND post=$post";
    $result = mysqli_query($db,$sql);
if(mysqli_num_rows($result) < 1){
        $q = $db->prepare("INSERT INTO user_likes(user_id,post,post_or_article_id) 
                                                    VALUES
                                                    (?,?,?)");
        $q->bind_param("iii",
                        $_SESSION['user']['id'],
                        $post,
                        $_POST['id']
                        );
        $q->execute();
}
// else{
//     // echo json_encode("poda");
// }
include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
?>