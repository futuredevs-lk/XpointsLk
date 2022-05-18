<?php
    include("./dbconfig.php");
    
    $sql = "SELECT * FROM cc_groups WHERE id = $last_group_id";
    $result = mysqli_query($db,$sql);
            
    while($row = mysqli_fetch_array($result)) {
        echo '
            <div class="group-image-container">
                <img src="'.$row['group_pic'].'" alt="" id="group_info_img" onclick="$(\'#group_edit_img\').click();">
                <input style="display: none;" type="file" accept="image/*" name="group_edit_img" id="group_edit_img" onchange="loadimgbeforeupload2(event);">
            </div>
            
            <script>
              var loadimgbeforeupload2 = function(event) {
                var output = document.getElementById(\'group_info_img\');
                output.src = URL.createObjectURL(event.target.files[0]);
                output.onload = function() {
                  URL.revokeObjectURL(output.src) // free memory
                }
              };
            </script>
                        
            <input class="group-name" name="edit_group_name" id="edit_group_name" type="text" placeholder="GROUP NAME" value="'.$row['group_name'].'">
                
                <input type="text" name="group_edit_participate[]" id="group_edit_participate" value="" style="display:none;">
                            
                <div class="chat-contact-add-participate" id="edit_group_participates" onclick="view_add_contact_panel(\'#edit_group_participates\',\'#group_edit_participate\');">
                    + ADD PARTICIPATES
                </div>
        ';
    }
    
    
    
    $sql = "SELECT * FROM cc_groups_users JOIN users ON cc_groups_users.user_id = users.id WHERE cc_groups_users.group_id = $last_group_id";
    $result = mysqli_query($db,$sql);
            
    while($row = mysqli_fetch_array($result)) {
        echo '  
                <div class="chat-contact" id="user'.$row['id'].'">
                    <span onclick="comfirm_action_popup(\'Are you sure do you want to remove this participate from this group?\',\'yes\',\'no\',remove_paticipate,'.$row['id'].');" class="chat-new-chat"><i class="fa fa-trash"></i></span>
                    <span class="chat-details-pic">
                        <img src="'.$row['image'].'" alt="">
                    </span>
                    <div class="chat-details-name">'.$row['username'].'</div>
                    <div class="chat-details-email">'.$row['email'].'</div>
                </div>';
    }


    include("./dbclose.php");

?>