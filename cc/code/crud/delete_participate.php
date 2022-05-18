<?php
    include("./dbconfig.php");
    echo json_encode("");
    
        $q = $db->prepare("DELETE FROM cc_groups_users WHERE group_id=$last_group_id AND user_id=?;");
        $q->bind_param("s",$_POST["id"]);
        $q->execute();
        
    
    
    include("./dbclose.php");
?>