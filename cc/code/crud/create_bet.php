<?php
    include("./dbconfig.php");
    
    if(1){
        
        $sql = "SHOW TABLE STATUS LIKE 'cc_bettings'";
        $result=$db->query($sql);
        $row = $result->fetch_assoc();
        $ai = $row['Auto_increment'];
        
        if (!file_exists($_FILES['bet_new_img']['tmp_name']) || !is_uploaded_file($_FILES['bet_new_img']['tmp_name'])) {
            $target_file = "";
        }else{
            $target_file  = "$ai.".pathinfo($_FILES["bet_new_img"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["bet_new_img"]["tmp_name"], "../assets/bet/".$target_file);
            
            $target_file  = "<img src='https://xpoints.lk/cc/code/assets/bet/".$target_file."' alt=''>";
        }
        
        $bet = $target_file.$_POST["bet_new_desc"];
        
        $q = $db->prepare("INSERT INTO cc_bettings( text,
                                                    min_amount,
                                                    max_amount,
                                                    expires_on
                                                    )
                                                    VALUES 
                                                    (?,?,?,?)");
        $q->bind_param("ssss",
                        $bet,
                        $_POST["bet_new_amount_min"],
                        $_POST["bet_new_amount_max"],
                        $_POST["bet_new_time"]
                        );
        $q->execute();
    }
        
    echo json_encode("");
    
    
    include("./dbclose.php");
?>