<?php
    include("./dbconfig.php");
    
    if($_POST["textbox"]) {
        $textb = $_POST['textbox'];
        $q = $db->prepare("INSERT INTO test_db (first_name) VALUES (?)");
        $q->bind_param("s",$fname);
        $fname = $textb;
        $q->execute();
        
        if($q){
            $array = array('data'=> $textb);
            echo json_encode($array);
        }
    }
    
    
    
  include("./dbclose.php");
?>