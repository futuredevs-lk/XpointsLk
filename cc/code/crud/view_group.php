<?php
    include("./dbconfig.php");
    
    $_SESSION['last_group_id'] = $_POST['id'];
    
    include("./dbclose.php");
?>