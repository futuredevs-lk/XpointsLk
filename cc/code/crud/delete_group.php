<?php
    include("./dbconfig.php");
    echo json_encode("");
    
    $q = $db->prepare("DELETE FROM cc_groups WHERE id=?;");
    $q->bind_param("s",$last_group_id);
    $q->execute();
    
    $q = $db->prepare("DELETE FROM cc_groups_users WHERE group_id=?;");
    $q->bind_param("s",$last_group_id);
    $q->execute();
    
    $sql = "SELECT * FROM cc_groups 
            WHERE id IN 
            (
                SELECT group_id FROM cc_groups_users 
                WHERE user_id=$current_user_id
            ) LIMIT 1;";
    $result = mysqli_query($db,$sql);
    while($row = mysqli_fetch_array($result)) {
        $_SESSION['last_group_id'] = $row['id'];
    }
        
    
    
    include("./dbclose.php");
?>