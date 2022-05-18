<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");



if(isset($_POST['decrease_water'])){
    $old_level = dbget("SELECT quantity FROM tg_user_wallets WHERE user_id =".$_SESSION['user']['id']." and wallet_id = 1 limit 1")[0]['quantity'];

    $new_level = $old_level-$_POST['decrease_water'];
    if($new_level<0){
        dbcmd("UPDATE tg_user_wallets SET quantity=0,updated_at=now(),in_progress=0 WHERE user_id =".$_SESSION['user']['id']." and wallet_id = 1");
        echo 0;
        exit;
    }

    dbcmd("UPDATE tg_user_wallets SET quantity = $new_level,updated_at=now(),in_progress =1 WHERE user_id =".$_SESSION['user']['id']." and wallet_id = 1");
    echo $new_level;

}





require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
?>
