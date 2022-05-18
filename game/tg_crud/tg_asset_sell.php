<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

//POST => tg_sell_quantity tg_sell_price wallet_id,tg_sell_currency

if (isset($_POST['tg_sell_quantity'])) {
    //check balance in user_wallets
    $asset_balance = dbget(
        "SELECT quantity 
        FROM tg_user_wallets 
        WHERE 
            wallet_id=" . $_POST['wallet_id'] . " and
            user_id = " . $_SESSION['user']['id'] . "
        "
    )[0]['quantity'];
    if (($asset_balance - $_POST['tg_sell_quantity']) < 0) {
        echo json_encode(array(
            "error" => "You don't have enough asset in this wallet!"
        ));
        exit;
    }
    dbcmd(
        "INSERT INTO 
        tg_shop(
            wallet_id,
            seller_id,
            quantity,
            price,
            currency_id
        )
        VALUES(
            {$_POST['wallet_id']},
            {$_SESSION['user']['id']},
            {$_POST['tg_sell_quantity']},
            {$_POST['tg_sell_price']},
            {$_POST['tg_sell_currency']}
        )"
    );

    //remove asset from tg_user_wallets
    dbcmd(
        "UPDATE 
            tg_user_wallets
        SET
            quantity = quantity-{$_POST['tg_sell_quantity']},
            updated_at = now()
        WHERE 
            user_id = {$_SESSION['user']['id']} and
            wallet_id = {$_POST['wallet_id']}"
    );
    include_once("./task-user-update.php");
    updateTasks($_SESSION['user']['id'], 'selling');

    $asset_capacity = dbget(
        "SELECT capacity FROM tg_wallets_levels
        WHERE wallet_id = {$_POST['wallet_id']}
        and level = {$_SESSION['user']['level']}"
    )[0]['capacity'];
    // send asset_capacity to JSON 
    echo json_encode(array(
        "asset_capacity" => $asset_capacity
    ));
}

require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
