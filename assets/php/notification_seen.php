<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
if(isset($_POST['seen'])){
    dbcmd(
        "DELETE FROM user_notification
        WHERE id={$_POST['id']}"
    );
    echo "success";
}

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>