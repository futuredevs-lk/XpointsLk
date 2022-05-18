<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
if(isset($_POST['make_me_rich'])){
    if($_SESSION['user']['admin']==2){
        $user_level = dbget("SELECT level FROM users WHERE id = {$_SESSION['user']['id']}")[0]['level'];
        dbcmd(
            "UPDATE tg_user_wallets
            SET quantity =IFNULL(
                (SELECT capacity FROM tg_wallets_levels
                WHERE 
                    level = $user_level
                    and
                    wallet_id = tg_user_wallets.wallet_id
            ),0),
            updated_at =now()
            WHERE user_id = {$_SESSION['user']['id']}"
        );
        echo "you are rich admin!!";
    }
}

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
