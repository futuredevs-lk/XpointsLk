<?php 
    $cur_page = $_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]; 
    $current_user = $_SESSION['user']['id'];
    
    // if($current_user = null){
        dbcmd("UPDATE user_notification SET seen='1' WHERE page='$cur_page' AND user_id='$current_user'");
    // }
    
    function set_notification($user_id, $text, $page){
        $date_time = new Date('Y-m-d h:i');
        dbcmd("INSERT INTO `user_notification` (`user_id`, `text`, `page`, `seen`, `datetime`) 
                                        VALUES ('$user_id', '$text', '$page', '0', '$date_time');");
    }
    // set_notification(5,"Game Boosted","/game/index.html");
    echo "<script>console.log('$cur_page')</script>";
    
?>