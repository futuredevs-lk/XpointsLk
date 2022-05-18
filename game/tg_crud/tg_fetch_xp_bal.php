<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

echo  dbget(
    "SELECT 
        quantity 
    FROM 
        tg_user_wallets 
    WHERE 
        user_id = ".$_SESSION['user']['id']." and 
        wallet_id=2
    "
    )[0]['quantity'];

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>