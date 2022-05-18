<?php
    include("./dbconfig.php");
    
    $lastbidid = $_REQUEST['id'];
    
    $sql = "SELECT
             cc_bettings_bids.*, users.image, users.username, users.email
            FROM `cc_bettings_bids`
            JOIN users 
            ON cc_bettings_bids.user_id=users.id
            WHERE betting_id=$lastbetid
            AND cc_bettings_bids.id > $lastbidid
            ORDER BY cc_bettings_bids.id ASC;";
    $result = mysqli_query($db,$sql);
    while($row = mysqli_fetch_array($result)) {
        // echo $row['id']."-$lastbidid";
        if(($row['user_id'] == $current_user_id)&&($lastbidid == 0)){
            echo '<div class="chat-message-wrap">
                        <div class="chat-message sent">
                            <div class="chat-message-body">
                                '.$row['amount'].'
                            </div>
                            <span class="chat-message-time">
                                '.$row['time'].'
                            </span>
                        </div>
                    </div>
                    <script>document.getElementsByClassName("bet-ask-history")[0].scrollTo(0,document.getElementsByClassName("bet-ask-history")[0].scrollHeight);</script>';
        }
        elseif($row['user_id'] != $current_user_id){
            echo '  <div class="chat-message-wrap">
                    <div class="chat-message recieved">
                        <div class="chat-message-sender">
                            <img class="chat-message-sender-pic" src="'.$row['image'].'" alt="">
                            <div class="chat-message-sender-name" style="padding-bottom:5px;">'.$row['username'].'</div>
                            
                        </div>
                        <div class="chat-message-body">'.$row['amount'].'</div>
                        <span class="chat-message-time">'.$row['time'].'</span>
                    </div>
                </div>
                <script>document.getElementsByClassName("bet-ask-history")[0].scrollTo(0,document.getElementsByClassName("bet-ask-history")[0].scrollHeight);</script>';
        }
        echo '<script>last_bid_id = '.$row['id'].';</script>';
    }


    include("./dbclose.php");

?>