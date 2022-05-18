<?php
    include("./dbconfig.php");
    
    
    $sql = "SELECT * FROM cc_bettings
            ORDER BY id ASC;";
    $result = mysqli_query($db,$sql);
    while($row = mysqli_fetch_array($result)) {
        echo '  <div class="betting-container show_preview_to_this" id="betting-container-'.$row['id'].'">
                    <div class="betting-info-text">
                    '.$row['text'].'
                    </div>
                    <div class="invisible" style="display:none;">
                        <span>Min:'.$row['min_amount'].'</span>|
                        <span>Max:'.$row['max_amount'].'</span>
                    </div>
                    <span class="cc-bet-id" style="display:none;">'.$row['id'].'</span>
                    <div class="betting-info-timer" id="cc_bet_timer_'.$row['id'].'">
                        <script>
                            setbettimer("'.$row['expires_on'].'","cc_bet_timer_'.$row['id'].'");
                        </script>
                    </div>
                    '.($current_user_role != 0 ?'<div class="cc-cancel-btn" onclick="comfirm_action_popup(\'Are you sure do you want to delete this Bet from Betting list?\',\'yes\',\'no\',delete_bet,'.$row['id'].')">DELETE</div>':'').'
                </div>';
    }


    include("./dbclose.php");

?>