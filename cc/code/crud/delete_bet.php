<?php
    include("./dbconfig.php");
    echo json_encode('');
    
    $q = $db->prepare("DELETE FROM cc_bettings WHERE id=?;");
    $q->bind_param("i",$_POST['id']);
    $q->execute();
    
    $q = $db->prepare("DELETE FROM cc_bettings_bids WHERE betting_id=?;");
    $q->bind_param("i",$_POST['id']);
    $q->execute();
    
    
    include("./dbclose.php");
?>