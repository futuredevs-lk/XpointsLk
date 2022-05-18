<?php
    include("./dbconfig.php");
    echo json_encode('');
    
    if(1){
        
        if(file_exists($_FILES['group_edit_img']['tmp_name']) && is_uploaded_file($_FILES['group_edit_img']['tmp_name'])) {
            $target_file  = "$last_group_id.".pathinfo($_FILES["group_edit_img"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["group_edit_img"]["tmp_name"], "../assets/group/profile/".$target_file);
            
            $target_file  = "https://xpoints.lk/cc/code/assets/group/profile/".$target_file;
            
            $q = $db->prepare("UPDATE `cc_groups` SET `group_pic`=? WHERE id=$last_group_id");
            $q->bind_param("s",$target_file);
            $q->execute();
        }
        
        $q = $db->prepare("UPDATE `cc_groups` SET `group_name`=? WHERE id=$last_group_id");
        $q->bind_param("s",
                        $_POST["edit_group_name"]);
        $q->execute();
        
        if(isset($_POST["group_edit_participate"])&&($_POST["group_edit_participate"][0] != 0)){
            foreach (explode(",",$_POST["group_edit_participate"][0]) as $user) {
                $q = $db->prepare("INSERT INTO cc_groups_users(group_id,user_id) 
                                    VALUES (?,?)");
                $q->bind_param("ii",
                                    $last_group_id,
                                    $user
                                );
                $q->execute();
            }
        }
        
        
    //     $sql = "SHOW TABLE STATUS LIKE 'cc_groups'";
    //     $result=$db->query($sql);
    //     $row = $result->fetch_assoc();
    //     $ai = $row['Auto_increment'];   //Find AI ID
        
    //     // Upload File Start
        
    //     if (!file_exists($_FILES['group_edit_img']['tmp_name']) || !is_uploaded_file($_FILES['group_edit_img']['tmp_name'])) {
    //         $target_file = "https://xpoints.lk/cc/code/assets/group/profile/group.png";
    //     }else{
    //         $target_file  = "$ai.".pathinfo($_FILES["group_edit_img"]["name"], PATHINFO_EXTENSION);
    //         move_uploaded_file($_FILES["group_edit_img"]["tmp_name"], "../assets/group/profile/".$target_file);
            
    //         $target_file  = "https://xpoints.lk/cc/code/assets/group/profile/".$target_file;
    //     }
        
    //     // Upload File End
        
    //     // Insert data into group table
    //     $q = $db->prepare("INSERT INTO cc_groups(group_name,group_created,group_pic) VALUES (?,?,?)");
    //     $q->bind_param("sss",
    //                     $_POST["group_name"],
    //                     date("Y-m-d h:i:s"),
    //                     $target_file);
    //     $q->execute();
        
    //     // Insert Data into Group-Users table
    }
        
    
    
    include("./dbclose.php");
?>