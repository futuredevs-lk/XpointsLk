<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['generate_xp'])) {

    $water_to_xp_rate =  dbget(
        "SELECT 
        wallet_2_rate 
    FROM 
        tg_wallet_exchange_rates 
    WHERE 
        wallet_1_id=1 and 
        wallet_2_id=2 and 
        level=" . $_SESSION['user']['level'] . "
    "
    )[0]['wallet_2_rate'];


    $XP_wallet = dbget(
        "SELECT 
        quantity,
        tg_wallets_levels.icon_path 
    FROM
        tg_user_wallets 
    JOIN
        tg_wallets_levels
    ON
        tg_wallets_levels.wallet_id = 
        tg_user_wallets.wallet_id
    WHERE 
        user_id = " . $_SESSION['user']['id'] . " and 
        tg_user_wallets.wallet_id=2
    "
    )[0];
    $old_xp_balance = $XP_wallet['quantity'];

    $new_xp_balance = $old_xp_balance + $water_to_xp_rate;

    $xp_capacity = dbget(
        "SELECT capacity FROM tg_wallets_levels WHERE id=2"
    )[0]['capacity'];
    if ($new_xp_balance > $xp_capacity) {
        echo json_encode(array(
            "error" => "Wallet capacity limit ($xp_capacity) is exceeded for your XP Bag (Level {$_SESSION['user']['level']}). Complete tasks for unlock new levels."
        ));
        exit;
    }

    dbcmd(
        "UPDATE tg_user_wallets 
    SET 
        quantity=$new_xp_balance 
    WHERE 
        user_id = " . $_SESSION['user']['id'] . " and 
        wallet_id=2
    "
    );
    include_once("./task-user-update.php");
    updateTasks($_SESSION['user']['id'], 'watering');

    $xp_icon = $XP_wallet['icon_path'];
    echo json_encode(array(
        "success" => "You've received $water_to_xp_rate XPoints.<br>Your new XP balance : {$new_xp_balance}",
        "image"   => $xp_icon
    ));
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
