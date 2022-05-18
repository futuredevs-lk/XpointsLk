<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

// get user wallet for get quantity
$tg_user_wallets = dbget("SELECT * FROM tg_user_wallets WHERE user_id={$_SESSION['user']['id']}");

// get wallets get capacity
$tg_wallets     = dbget("SELECT * FROM tg_wallets_levels WHERE level ={$_SESSION['user']['level']} ORDER BY wallet_id");

// get max-capacity of wallets and calculate quantity of wallets by percentage
function calProgressPercentage($wallet)
{
    global $tg_user_wallets, $tg_wallets;
    return $tg_user_wallets[$wallet]['quantity'] / $tg_wallets[$wallet]['capacity'] * 100;
}

echo json_encode(
    array(
        "water" => calProgressPercentage(0),
        "xp" => calProgressPercentage(1),
        "gold" => calProgressPercentage(2),
        "copper" => calProgressPercentage(3),
        "bronze" => calProgressPercentage(4)
    )
);


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
