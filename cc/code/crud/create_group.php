<?php
    include("./dbconfig.php");
    
    if(1){
        
        $sql = "SHOW TABLE STATUS LIKE 'cc_groups'";
        $result=$db->query($sql);
        $row = $result->fetch_assoc();
        $ai = $row['Auto_increment'];   //Find AI ID
        
        // Upload File Start
        
        if (!file_exists($_FILES['new_group_image']['tmp_name']) || !is_uploaded_file($_FILES['new_group_image']['tmp_name'])) {
            $target_file = "https://xpoints.lk/cc/code/assets/group/profile/group.png";
        }else{
            $target_file  = "$ai.".pathinfo($_FILES["new_group_image"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["new_group_image"]["tmp_name"], "../assets/group/profile/".$target_file);
            
            $target_file  = "https://xpoints.lk/cc/code/assets/group/profile/".$target_file;
        }
        
        // Upload File End
        
        // Insert data into group table
        $q = $db->prepare("INSERT INTO cc_groups(group_name,group_created,group_pic) VALUES (?,?,?)");
        $q->bind_param("sss",
                        $_POST["group_name"],
                        date("Y-m-d H:i:s"),
                        $target_file);
        $q->execute();
        
        // Insert Data into Group-Users table
        if(isset($_POST["new_group_participate"])&&($_POST["new_group_participate"][0] != 0)){
            foreach (explode(",",$_POST["new_group_participate"][0]) as $user) {
                $q = $db->prepare("INSERT INTO cc_groups_users(group_id,user_id) 
                                    VALUES (?,?)");
                $q->bind_param("ii",
                                    $ai,
                                    $user
                                );
                $q->execute();
            }
        }
    }
        
    echo json_encode("");
    
    
    include("./dbclose.php");
?>