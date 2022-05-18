<?php
    if(!isset($_SESSION['last_group_id'])){
        $_SESSION['last_group_id'] = 0;
    }
    if(!isset($_SESSION['cc_last_bet_id'])){
        $_SESSION['cc_last_bet_id'] = 0;
    }
    if(!isset($_SESSION['user']['id'])){
        $current_user_id = 0;
        $current_user_name = 0;
        $current_user_role = 0;
    }
    else{
        $current_user_id = $_SESSION['user']['id'];
        $current_user_name = $_SESSION['user']['username']; //User Name
        $current_user_role = $_SESSION['user']['admin']; //User Rolle
    }
    
    date_default_timezone_set('Asia/Kolkata');
    
    $last_group_id = $_SESSION['last_group_id'];    //Last Opened Group ID
    $lastbetid = $_SESSION['cc_last_bet_id'];    //Last Opened Group ID
?>