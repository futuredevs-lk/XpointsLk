<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (isset($_POST['clear_all'])) {
    dbcmd("DELETE FROM user_notification WHERE user_id = {$_SESSION['user']['id']}");
    echo "all-notifications-cleaned.";
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");    
