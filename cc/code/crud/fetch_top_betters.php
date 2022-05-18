<?php
    include("./dbconfig.php");
    
    $sql = "SELECT * FROM `cc_bettings_bids`
            JOIN users 
            ON cc_bettings_bids.user_id=users.id 
            WHERE betting_id=$lastbetid
            ORDER BY cc_bettings_bids.amount + 0 DESC 
            LIMIT 3";
    $result = mysqli_query($db,$sql);
    while($row = mysqli_fetch_array($result)) {
        echo '<div class="betters-card">
                    <img src="'.$row['image'].'" alt="">
                    <div class="betting-toper-info">
                        <div class="better-name">
                            '.$row['username'].'
                        </div>
                        <span>'.$row['amount'].'</span>
                    </div>
                </div>';
    }


    include("./dbclose.php");

?>