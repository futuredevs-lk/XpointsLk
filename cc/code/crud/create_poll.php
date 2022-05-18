<?php
    include("./dbconfig.php");
    
    if(1){
        
        $sql = "SHOW TABLE STATUS LIKE 'cc_pollings'";
        $result=$db->query($sql);
        $row = $result->fetch_assoc();
        $ai = $row['Auto_increment'];   //Find AI ID
        
        // Upload File Start
        
        if (!file_exists($_FILES['poll_new_img']['tmp_name']) || !is_uploaded_file($_FILES['poll_new_img']['tmp_name'])) {
            $target_file = "";
        }else{
            $target_file  = "$ai.".pathinfo($_FILES["poll_new_img"]["name"], PATHINFO_EXTENSION);
            move_uploaded_file($_FILES["poll_new_img"]["tmp_name"], "../assets/poll/".$target_file);
            
            $target_file  = "<img src='https://xpoints.lk/cc/code/assets/poll/".$target_file."' alt=''>";
        }
        
        // Upload File End
        
        $question = $target_file.$_POST["poll_new_question"];
        
        // Insert data into group table
        $q = $db->prepare("INSERT INTO cc_pollings(question,tag) VALUES (?,?)");
        $q->bind_param("ss",
                        $question,
                        $_POST["poll_new_tag"]);
        $q->execute();
        
        // Insert Data into Group-Users table
        if(isset($_POST["poll_new_options"])){
            foreach ($_POST["poll_new_options"] as $option) {
                $q = $db->prepare("INSERT INTO cc_pollings_options(polling_id,option_text) 
                                    VALUES (?,?)");
                $q->bind_param("is",
                                    $ai,
                                    $option
                                );
                $q->execute();
            }
        }
    }
        
    echo json_encode("");
    
    
    include("./dbclose.php");
?>