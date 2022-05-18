<?php
    include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbconfig.php");
    $post_id = $_REQUEST['id'];
    $sql = "SELECT * FROM comment JOIN users ON comment.user_id = users.id WHERE post_id=$post_id
            ORDER BY comment.id + 0 DESC;";
    $result = mysqli_query($db,$sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<div class="cmt-box">
        <div class="cmt-dp">
            <img class="img" src="'.$row['image'].'" alt="">
        </div>
        <div class="cmt-content">
            
            <p class="commenter-name">'.$row['username'].' <br><span class="comment">'.$row['comment'].'</span></p>
        </div>
    </div>';
    }
    
   include($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php"); 
?>