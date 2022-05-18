<?php
    include("./dbconfig.php");
    echo json_encode("");
    
    $q = $db->prepare("INSERT INTO cc_pollings_answered(user_id,polling_id,option_id) VALUES(?,?,?);");
    $q->bind_param("sss",
                $current_user_id,
                $_POST['q'],
                $_POST['a']
            );
    $q->execute();
        
    
    
    include("./dbclose.php");
?>