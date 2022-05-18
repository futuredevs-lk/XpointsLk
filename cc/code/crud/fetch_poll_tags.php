<?php
    include("./dbconfig.php");
    
    $sql="SELECT DISTINCT tag FROM cc_pollings;";
    $result = mysqli_query($db,$sql);
        
    echo '<span class="poll-tag">All</span>';
    while($row = mysqli_fetch_array($result)) {
        echo '<span class="poll-tag">'.strtolower($row['tag']).'</span>';
    }


    include("./dbclose.php");

?>