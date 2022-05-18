<?php
    include("./dbconfig.php");
    echo json_encode($_POST['id']);
    
    $q = $db->prepare("DELETE FROM cc_pollings WHERE id=?;");
    $q->bind_param("i",$_POST['id']);
    $q->execute();
    
    $q = $db->prepare("DELETE FROM cc_pollings_options WHERE polling_id=?;");
    $q->bind_param("i",$_POST['id']);
    $q->execute();
    
    $q = $db->prepare("DELETE FROM cc_pollings_answered WHERE polling_id=?;");
    $q->bind_param("i",$_POST['id']);
    $q->execute();
    
    
    include("./dbclose.php");
?>