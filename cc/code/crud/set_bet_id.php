<?php
    
    include("./dbconfig.php");
    
    $_SESSION['cc_last_bet_id'] = $_POST['id'];
    $lastbetid = $_SESSION['cc_last_bet_id'];
        
    include("./dbclose.php");        
        
?>