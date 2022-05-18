<?php
    include("./dbconfig.php");
    if(1){
        $q = $db->prepare("INSERT INTO cc_bettings_bids(betting_id,
                                                        user_id,
                                                        amount,
                                                        time
                                                    )
                                                    VALUES 
                                                    (?,?,?,?)");
        $q->bind_param("iiss",
                        $lastbetid,
                        $current_user_id,
                        $_POST["text"],
                        date("H:i")
                        );
        $q->execute();
    }
        
    echo json_encode("");
    
    include("./dbclose.php");
?>