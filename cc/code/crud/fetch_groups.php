<?php
    include("./dbconfig.php");
    
    $sql = "SELECT * FROM cc_groups_users JOIN cc_groups ON cc_groups_users.group_id = cc_groups.id WHERE cc_groups_users.user_id = $current_user_id AND cc_groups_users.group_id != $last_group_id";
    $result = mysqli_query($db,$sql);
            
    while($row = mysqli_fetch_array($result)) {
        echo '  <div class="chat-contact" onclick="viewcontact('.$row['id'].')">
                    <span class="chat-new-chat"></span>
                    <span class="chat-details-pic">
                        <img src="'.$row['group_pic'].'" alt="">
                    </span>
                    <div class="chat-details-name">'.$row['group_name'].'</div>
                    <div class="chat-details-email">'.$row['group_created'].'</div>
                </div>';
    }


    include("./dbclose.php");

?>