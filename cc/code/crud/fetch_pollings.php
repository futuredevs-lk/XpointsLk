<?php
    include("./dbconfig.php");
    
    $sql="
        SELECT * FROM `cc_pollings`
        ORDER BY cc_pollings.id ASC";
    $result = mysqli_query($db,$sql);
            
    while($row = mysqli_fetch_array($result)) {
        $echo =  ' <div class="poll-question-container '.strtolower($row['tag']).'"> 
                    <button class="poll-question cc-full-poll-img" style="width:100%;">
                        '.$row['question'].'
                        <div class="cc-action-btn-container" style="font-size: small;">
                            <!--div class="cc-edit-btn" style="margin-right: 10px;" onclick="toggle_poll_edit('.$row['id'].')">EDIT</div-->
                            '.($current_user_role != 0 ?'<div class="cc-cancel-btn" onclick="comfirm_action_popup(\'Are you sure do you want to delete this poll from poll list?\',\'yes\',\'no\',delete_poll ,\''.$row['id'].'\');">DELETE</div>':'').'
                        </div>
                    </button>
                    <div class="poll-option-container" id="cc_poll_q_'.$row['id'].'">
                    ';
                    
                $sql="SELECT * FROM `cc_pollings_answered` WHERE user_id =$current_user_id AND polling_id =".$row['id'];
                $result3 = mysqli_query($db,$sql);
                
                if(mysqli_num_rows($result3) != 0){
                    $sql2="SELECT *,
                            (
                                ROUND(
                                    (SELECT COUNT(*) FROM cc_pollings_answered WHERE cc_pollings_answered.option_id = cc_pollings_options.id) /
                            		(SELECT COUNT(*) FROM cc_pollings_answered WHERE cc_pollings_answered.polling_id = cc_pollings_options.polling_id) * 100
                                ,0)
                            ) AS result
                            FROM cc_pollings_options WHERE polling_id=".$row['id'];
                    $result2 = mysqli_query($db,$sql2);
                    while($row2 = mysqli_fetch_array($result2)) {
                        $echo .= '
                                    <label class="poll-option after_selected_answer">
                                        <input type="radio" name="'.$row2['polling_id'].'" value="'.$row2['option_text'].'">
                                        <span style="background:linear-gradient(90deg, rgb(67 255 82) '.$row2['result'].'%, transparent 0%);">'.$row2['option_text'].'   '.$row2['result'].'%</span>
                                    </label>
                        ';
                    }
                    $echo .= '<div style="margin-top:20px;" class="poll_option_submited">Submitted</div>';
                }
                else{
                    $sql2="SELECT * FROM `cc_pollings_options` WHERE polling_id =".$row['id'];
                    $result2 = mysqli_query($db,$sql2);
                    while($row2 = mysqli_fetch_array($result2)) {
                        $echo .= '
                                    <label class="poll-option">
                                        <input type="radio" name="'.$row2['polling_id'].'" value="'.$row2['option_text'].'">
                                        <span onclick="submit_poll_answer('.$row2['polling_id'].','.$row2['id'].');">'.$row2['option_text'].'</span>
                                    </label>
                        ';
                    }
                }
                
                $echo .=  '      </div>
                        </div>';
        
        echo $echo;
    }


    include("./dbclose.php");

?>