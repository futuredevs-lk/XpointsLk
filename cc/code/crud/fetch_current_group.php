<?php
    include("./dbconfig.php");
    
    
    $sql = "SELECT * FROM cc_groups WHERE id = $last_group_id";
    $result = mysqli_query($db,$sql);
    // echo    "<script>console.log('".json_encode(mysqli_num_rows($result))."-$last_group_id');</script>";
    if(mysqli_num_rows($result) == 0){
        $sql = "
        SELECT * FROM cc_groups 
            WHERE id IN 
            (
                SELECT group_id FROM cc_groups_users 
                WHERE user_id=$current_user_id
            ) LIMIT 1;";
        $result = mysqli_query($db,$sql);
    }
    while($row = mysqli_fetch_array($result)) {
        echo '  <div class="chat-group-details">
                    <span class="chat-details-pic">
                        <img src="'.$row['group_pic'].'" alt="">
                    </span>
                    <div class="chat-details-name">'.$row['group_name'].'</div>
                    <div class="chat-details-email">'.$row['group_created'].'</div>
                </div>';
                
        $_SESSION['last_group_id'] = $row['id'];
    }


    include("./dbclose.php");

?>