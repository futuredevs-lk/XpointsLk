<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
$user_id = $_SESSION['user']['id'];
$tg_sold_items = dbget(
    "SELECT
        tg_asset_transection.price,
        tg_asset_transection.quantity,
        tg_asset_transection.currency_id,
        tg_wallets_levels.wallet_name,
        users.username as buyer_name,
        tg_wallets_levels.icon_path

    FROM 
        tg_asset_transection
    JOIN
        tg_wallets_levels
    ON
        tg_asset_transection.asset_id = 
        tg_wallets_levels.wallet_id
    JOIN
        users
    ON
        users.id = tg_asset_transection.buyer_id

    WHERE 
    tg_asset_transection.seller_id = $user_id"
);
//give sold money to seller ;)
if (!empty($tg_sold_items)) {
    foreach ($tg_sold_items as $tg_sold_item) {
        //capacity check;
        $seller_capacity = dbget("SELECT capacity 
                                FROM tg_wallets_levels
                                WHERE 
                                    level = {$_SESSION['user']['level']} &&
                                    wallet_id = {$tg_sold_item['currency_id']}")[0]['capacity'];
        $seller_balance = dbget("SELECT quantity FROM tg_user_wallets
                                WHERE
                                    user_id = $user_id and
                                    wallet_id = {$tg_sold_item['currency_id']}")[0]['quantity'];
        //price is already multiplied by quantity in script.js
        if (($seller_balance + $tg_sold_item['price']) < $seller_capacity) {
            dbcmd(
                "UPDATE 
                    tg_user_wallets
                SET
                    quantity = quantity + {$tg_sold_item['price']}
                WHERE
                    wallet_id = {$tg_sold_item['currency_id']} &&
                    user_id = $user_id"
            );
        }
    }
    //remove datas from transection table
    dbcmd(
        "DELETE 
        FROM 
            tg_asset_transection
        WHERE 
            seller_id = $user_id"
    );
    //sending tg_sold_items['price,currency_id,wallet_name,buyer_name,icon_path']
    //currency name also want..
} else {
    $tg_sold_items = array(
        "empty" => true
    );
}
echo json_encode($tg_sold_items);



require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
