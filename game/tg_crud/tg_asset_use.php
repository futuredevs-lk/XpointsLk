<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['wallet_id'])) {
    // first CHECK BALANCE of wallet FROM TG_U_WALLET
    $user_wallet_bal = dbget(
        "SELECT 
            quantity 
        FROM 
            tg_user_wallets 
 
        WHERE 
            wallet_id={$_POST['wallet_id']} AND
            user_id = {$_SESSION['user']['id']}
        "
    )[0]['quantity'];



    if ($user_wallet_bal == 0) {
        echo json_encode(array("error" => "You don't have enough balance in required wallet!"));
        exit;
    }

    //assign cat_id to coulmns
    $tg_user_wallet_use_columns = array(
        3 => "gem_id",
        4 => "bottle_id",
        7 => "potion_id",
        5 => "wish_t_id",
    );
    $column_name = $tg_user_wallet_use_columns[$_POST['cat_id']];

    //check the wallet is already used like embeded in tree if yes send retry
    if (!empty(dbget(
        "SELECT id
        FROM tg_user_wallet_use
        WHERE
            user_id = {$_SESSION['user']['id']} and
            {$column_name} = {$_POST['wallet_id']}"
    )[0]['id'])) {
        echo json_encode(array(
            "error" => "You are already placed this asset on correct position. Move towards the next target!!"
        ));
        exit;
    }

    //substr asset from tg_user_wallets
    dbcmd(
        "UPDATE tg_user_wallets
         SET 
            quantity = quantity-1
        WHERE 
            user_id = {$_SESSION['user']['id']} and
            wallet_id = {$_POST['wallet_id']}"
    );

    //finally add datas to tg_user_wallet_use
    //send succes image
    dbcmd(
        "INSERT INTO tg_user_wallet_use(
            user_id,
            {$column_name}
        )
        VALUES(
            {$_SESSION['user']['id']},
            {$_POST['wallet_id']}
        )
        ON DUPLICATE KEY UPDATE
        {$column_name} ={$_POST['wallet_id']}"
    );

    //if gem and empty bottle are placed, start timer and after 1 hour give portion..
    $bottle_timer_check = dbget(
        "SELECT
                    bottle_id
        FROM 
            tg_user_wallet_use
        WHERE 
            user_id = {$_SESSION['user']['id']} and
            gem_id    IS NOT NULL and
            bottle_id IS NOT NULL and
            potion_id IS NULL and
            wish_t_id IS NULL"
    )[0]['bottle_id'][0]['bottle_id'];

    if (isset($bottle_timer_check)) {
        dbcmd(
            "UPDATE tg_user_wallets
            SET
                in_progress = 1,
                updated_at = now()
            WHERE 
                user_id = {$_SESSION['user']['id']} and
                wallet_id = $bottle_timer_check"
        );
        $bottle_image = dbget(
            "SELECT icon_path FROM tg_wallets_levels
            WHERE wallet_id = {$bottle_timer_check}"
        );
        $bottle_timer_countdown = dbget(
            "SELECT tg_bottle_timer FROM tg_maintain WHERE level = {$_SESSION['user']['level']}"
        )[0]['tg_bottle_timer'];
        echo json_encode(array(
            "bottle_timer" => "Producing Portion for you!",
            "image"   => $bottle_image,
            "countdown" => $bottle_timer_countdown
        ));
        exit;
    }



    //if all target done make wish ticket worthy
    $tg_final_check = dbget(
        "SELECT
            wish_t_id,
            updated_at
        FROM 
            tg_user_wallet_use
        WHERE 
            user_id = {$_SESSION['user']['id']} and
            gem_id    IS NOT NULL and
            bottle_id IS NOT NULL and
            potion_id IS NOT NULL and
            wish_t_id IS NOT NULL"
    );

    if (isset($tg_final_check[0]['wish_t_id'])) {
        dbcmd(
            "INSERT INTO tg_user_won(
                level,
                user_id,
                wish_t_id,
                in_process
            )
            VALUES (
                {$_SESSION['user']['level']},
                {$_SESSION['user']['id']},
                {$tg_final_check[0]['wish_t_id']},
                1
            )
            ON DUPLICATE KEY UPDATE
               wish_t_id = {$tg_final_check[0]['wish_t_id']},
               created_At=now()"
        );
        //GET WALLET_OIMAGE FROM TG_WALLETS_LEVELS
        $wish_t_image = dbget(
            "SELECT icon_path FROM tg_wallets_levels
            WHERE wallet_id = {$tg_final_check[0]['wish_t_id']} and
            level = {$_SESSION['user']['level']}"
        )[0]['icon_path'];
        $gift_card_timer_countdown = dbget(
            "SELECT tg_gift_card_timer FROM tg_maintain WHERE level = {$_SESSION['user']['level']}"
        )[0]['tg_gift_card_timer'];

        //
        echo json_encode(array(
            "won" => "Your Wish Ticket is now validated and pending for the manual check by Administrator!",
            "image"   => $wish_t_image,
            "countdown" => $gift_card_timer_countdown
        ));


        //sending notification
        dbcmd(
            "INSERT INTO user_notification(
                user_id,
                text,
                page
            )
            VALUES(
                {$_SESSION['user']['id']},
                'Your Wish Ticket is now validated and pending for the manual check by Administrator!',
                '#'
            )"
        );
        exit;
    }

    //GET WALLET_OIMAGE FROM TG_WALLETS_LEVELS
    $wallet_image = dbget(
        "SELECT icon_path FROM tg_wallets_levels
        WHERE wallet_id = {$_POST['wallet_id']} and
        level = {$_SESSION['user']['level']}"
    )[0]['icon_path'];

    echo json_encode(array(
        "success" => "Successfully placed the asset on correct position!",
        "image"   => $wallet_image
    ));
}

require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
