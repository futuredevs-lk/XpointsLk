<?php
    include("./dbconfig.php");
    
    // $last_msg_id = 0;
    if(isset($_REQUEST['id'])){
        $last_msg_id = $_REQUEST['id'];
    }
    $sql="
        SELECT cc_groups_messages.* , users.image, users.username, users.email 
        FROM cc_groups_messages 
        JOIN users ON 
        cc_groups_messages.user_id=users.id 
        WHERE group_id IN (
            SELECT group_id FROM cc_groups_users 
            WHERE user_id=$current_user_id
            ) 
        AND 
        cc_groups_messages.id > $last_msg_id 
        AND
        cc_groups_messages.group_id = $last_group_id 
        ORDER BY cc_groups_messages.id ASC";
    $result = mysqli_query($db,$sql);
    
    // echo '<script>console.log("'.mysqli_num_rows($result).'");</script>';
    if(isset($_SESSION['last_msg_id']) && $_SESSION['last_msg_id'] == $last_msg_id && mysqli_num_rows($result) == 0){
        null;
    }else{
    
    $_SESSION['last_msg_id'] = $last_msg_id;
            
    while($row = mysqli_fetch_array($result)) {
        if($row['user_id'] == $current_user_id){
            if($row['file_url'] == null){
            echo '  <div class="chat-message-wrap">
                        <div class="chat-message sent">
                            <div class="chat-message-body">
                                '.$row['text'].'
                            </div>
                            <span class="chat-message-time">
                                '.$row['time'].'
                            </span>
                        </div>
                    </div>';
            }
            elseif(explode('.',$row['file_url'])[2] == 'jpg' ||explode('.',$row['file_url'])[2] == 'png' || explode('.',$row['file_url'])[2] == 'gif'){
                echo '
                    <div class="chat-message-wrap">
                        <div class="chat-message sent">
                            <div class="chat-message-body">
                                <a href="'.$row['file_url'].'" target="_blank">
                                    <img src="'.$row['file_url'].'" alt="">
                                </a>
                            </div>
                            <span class="chat-message-time">'.$row['time'].'</span>
                        </div>
                    </div>
                ';
            }
            else{
                 echo '
                    <div class="chat-message-wrap">
                        <div class="chat-message sent">
                            <div class="chat-message-body">
                                <a href="'.$row['file_url'].'" target="_blank">
                                    <span class="cc_external_file">'.end(explode('/',$row['file_url'])).'</span>
                                </a>
                            </div>
                            <span class="chat-message-time">'.$row['time'].'</span>
                        </div>
                    </div>
                ';
            }
        }
        else{
            if($row['file_url'] == null){
                echo '
                    <div class="chat-message-wrap">
                        <div class="chat-message recieved">
                            <div class="chat-message-sender">
                                <img class="chat-message-sender-pic" src="'.$row['image'].'" alt="">
                                <div class="chat-message-sender-name">'.$row['username'].'</div>
                                <div> </div>
                            </div>
                            <div class="chat-message-body">'.$row['text'].'
                            </div>
                            <span class="chat-message-time">'.$row['time'].'</span>
                        </div>
                    </div>
                ';
            }
            elseif(explode('.',$row['file_url'])[2] == 'jpg' ||explode('.',$row['file_url'])[2] == 'png' || explode('.',$row['file_url'])[2] == 'gif'){
                echo '
                    <div class="chat-message-wrap">
                        <div class="chat-message recieved">
                            <div class="chat-message-sender">
                                <img class="chat-message-sender-pic" src="'.$row['image'].'" alt="">
                                <div class="chat-message-sender-name">'.$row['username'].'</div>
                                <div> </div>
                            </div>
                            <div class="chat-message-body">
                                <a href="'.$row['file_url'].'" target="_blank">
                                    <img src="'.$row['file_url'].'" alt="">
                                </a>
                            </div>
                            <span class="chat-message-time">'.$row['time'].'</span>
                        </div>
                    </div>
                ';
            }
            else{
                echo '
                    <div class="chat-message-wrap">
                        <div class="chat-message recieved">
                            <div class="chat-message-sender">
                                <img class="chat-message-sender-pic" src="'.$row['image'].'" alt="">
                                <div class="chat-message-sender-name">'.$row['username'].'</div>
                                <div></div>
                            </div>
                            <div class="chat-message-body">
                                <a href="'.$row['file_url'].'" target="_blank">
                                    <span class="cc_external_file">'.end(explode('/',$row['file_url'])).'</span>
                                </a>
                            </div>
                            <span class="chat-message-time">'.$row['time'].'</span>
                        </div>
                    </div>
                ';
            }
        }
        echo '<script>last_msg_id ='.$row['id'].';
        chathistelemnt.scrollTo(0,chathistelemnt.scrollHeight);</script>';
    }
}

    include("./dbclose.php");

?>