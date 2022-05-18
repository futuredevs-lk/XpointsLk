<?php
    include("./dbconfig.php");
    echo json_encode('');
    
    $target_file = "";
    
    if (file_exists($_FILES['write_chat_attah_file']['tmp_name']) && is_uploaded_file($_FILES['write_chat_attah_file']['tmp_name'])) {
        $target_file  = time().".".pathinfo($_FILES["write_chat_attah_file"]["name"], PATHINFO_EXTENSION);
        
        move_uploaded_file($_FILES["write_chat_attah_file"]["tmp_name"], "../assets/group/media/".$target_file);
        
        $target_file  = "https://xpoints.lk/cc/code/assets/group/media/".$target_file;
    }
    
    $q = $db->prepare("INSERT INTO cc_groups_messages(
                                            group_id,
                                            user_id,
                                            text,
                                            file_url,
                                            time
                                            ) VALUES (?,?,?,?,?)");
    $q->bind_param("sssss",
                    $last_group_id,
                    $current_user_id,
                    $_POST["chat_text"],
                    $target_file,
                    date("H:i")
                    );
    $q->execute();
        
    
    
    include("./dbclose.php");
?>