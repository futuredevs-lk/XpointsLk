<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['purchase_item'])) {
    //posts => purchase_item, quantity, seller_id,wallet_id,price,currency



    //1st check the capacity of wallet in user's level from tg_wallets; 

    $user_old_quantity = dbget(
        "SELECT quantity 
        FROM tg_user_wallets 
        WHERE 
            wallet_id=" . $_POST['wallet_id'] . " and
            user_id = " . $_SESSION['user']['id'] . "
        "
    )[0]['quantity'];

    $wallet_capacity = dbget(
        "SELECT capacity FROM tg_wallets_levels WHERE wallet_id=" . $_POST['wallet_id'] . ""
    )[0]['capacity'];

    // if old_quantity+ current_buying_quantity(POST[quantity]) > capacity => exit;

    $wallet_new_quantity = $user_old_quantity + $_POST['quantity'];

    if ($wallet_new_quantity > $wallet_capacity) {
        echo json_encode(array("error" => "wallet_capcity limit exceeded!"));
        exit;
    }
    // THEN CHECK BALANCE of FROM TG_U_WALLET
    $user_bal_currency = dbget(
        "SELECT 
            quantity 
        FROM 
            tg_user_wallets 
        JOIN 
            tg_wallets_levels
        ON 
            tg_wallets_levels.wallet_id = tg_user_wallets.wallet_id
        WHERE 
            tg_wallets_levels.wallet_id={$_POST['currency']} AND
            user_id = " . $_SESSION['user']['id'] . " AND
            tg_wallets_levels.level = " . $_SESSION['user']['level'] . " 
        "
    )[0]['quantity'];
    //if bal_xp ! > price => exit
    if (!($user_bal_currency >= $_POST['price'])) {
        echo json_encode(array("error" => "You don't have enough balance in required wallet!"));
        exit;
    }
    //if not available in shop exit;
    $asset_available_in_shop = dbget(
        "SELECT quantity FROM tg_shop
         WHERE id = {$_POST['purchase_item']}
    "
    )[0]['quantity'];
    if ($asset_available_in_shop < $_POST['quantity']) {
        echo json_encode(array("error" => "This asset is currently unavailable from this seller.Try from different one.."));
        exit;
    }

    $user_new_bal_currency = $user_bal_currency - $_POST['price'];

    //asset++ and currency-- from tg_user_wallet of buyer
    dbcmd(
        "UPDATE 
        tg_user_wallets 
    SET 
        quantity = CASE wallet_id
                   WHEN {$_POST['currency']} THEN $user_new_bal_currency
                   WHEN " . $_POST['wallet_id'] . " THEN $wallet_new_quantity
                   END ,
        updated_at = now()
    WHERE
        user_id = " . $_SESSION['user']['id'] . " and
        wallet_id IN ({$_POST['currency']}," . $_POST['wallet_id'] . ")
    "
    );


    //store this transection in tg_assset_transection fetch it on main_page
    //price is already multiplied by quantity
    dbcmd(
        "INSERT INTO tg_asset_transection
    (
        asset_id,
        seller_id,
        buyer_id,
        quantity,
        price,
        currency_id
    )
    VALUES
    (
        " . $_POST['wallet_id'] . ",
        " . $_POST['seller_id'] . ",
        " . $_SESSION['user']['id'] . ",
        " . $_POST['quantity'] . ",
        " . $_POST['price'] . ",
        " . $_POST['currency'] . "

    )
    "
    );

    //remove item from tg_shop
    $new_available_in_shop = $_POST['available_in_shop'] - $_POST['quantity'];
    dbcmd(
        "UPDATE 
        tg_shop
    SET 
        quantity = $new_available_in_shop
    WHERE
        id = " . $_POST['purchase_item'] . "
    "
    );

    //update tasks
    include_once("./task-user-update.php");
    updateTasks($_SESSION['user']['id'], 'buying');

    //finally preview congrats window
    //for that get image and wallet name
    $bought_wallet = dbget(
        "SELECT 
            icon_path,wallet_name
        FROM
            tg_wallets_levels
        WHERE
            wallet_id = " . $_POST['wallet_id'] . " &&
            level = {$_SESSION['user']['level']}
        LIMIT 
            1
        "
    )[0];
    //get currency name
    $currecny_name = dbget("SELECT wallet_name FROM tg_wallets_levels WHERE wallet_id = {$_POST['currency']} && level = {$_SESSION['user']['level']}")[0]['wallet_name'];

    //in tg_asset_purchasing xp wont increase so i did not get xp capacity but i need it now
    $xp_capacity = dbget(
        "SELECT capacity FROM tg_wallets_levels WHERE wallet_id=2"
    )[0]['capacity'];

    //send asset_name,asset_capacity,asset_image,xp_bal,xp_capacity,asset_bal

    echo json_encode(
        array(
            "success" =>
            true,
            "asset_name" =>
            "{$bought_wallet['wallet_name']}",
            "asset_capacity" =>
            $wallet_capacity,
            "asset_image" =>
            "{$bought_wallet['icon_path']}",
            "xp_bal" =>
            $user_new_bal_currency,
            "xp_capacity" =>
            $xp_capacity,
            "currency_bal" =>
            $user_bal_currency,
            "currency_name" =>
            $currecny_name
        )
    );
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
