<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

if(isset($_POST['get_time'])){
    //get updated at and quantity
    $wallet_water = dbget("SELECT quantity,updated_at FROM tg_user_wallets WHERE user_id=".$_SESSION['user']['id']." and wallet_id = 1")[0];

    $water_wallet_updated_at = strtotime($wallet_water['updated_at']);

    //get water_to_xp time from tg_maintain
    $water_to_xp_time = dbget(
        "SELECT
            water_to_xp_time
        FROM
            tg_maintain
        WHERE 
            level = {$_SESSION['user']['level']}"
    )[0]['water_to_xp_time'];

    $water_time_gone = time()-$water_wallet_updated_at;//how many sec passed.


    $water_time_left = $water_to_xp_time - $water_time_gone;
    

    $Response = array('water_time_left' => $water_time_left,'water_level'=> $wallet_water['quantity']);
    echo json_encode($Response);
}



require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>