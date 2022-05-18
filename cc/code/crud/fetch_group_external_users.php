<?php
    include("./dbconfig.php");
    
    $sql="SELECT * FROM   users WHERE id NOT IN (SELECT user_id FROM cc_groups_users WHERE cc_groups_users.group_id=$last_group_id)";
    $result = mysqli_query($db,$sql);
            
    while($row = mysqli_fetch_array($result)) {
        echo '  <label style="display:block;">
                    <input type="checkbox" name="add_participate" value="'.$row['id'].'" style="display:none;">
                    <div class="chat-contact">
                        <span class="chat-details-pic">
                            <img src="'.$row['image'].'" alt="">
                        </span>
                        <div class="chat-details-name">'.$row['username'].'</div>
                        <div class="chat-details-email">'.$row['email'].'</div>
                    </div>
                </label>';
    }


    include("./dbclose.php");

?>