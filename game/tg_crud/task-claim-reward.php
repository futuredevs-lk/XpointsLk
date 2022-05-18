<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['claim'])) {

    $task = dbget("SELECT * FROM tg_task_config WHERE id = {$_POST['taskId']}")[0];

    $task_user_quantity = dbget("SELECT {$task['task']} FROM tg_task_user WHERE user_id = {$_SESSION['user']['id']}")[0][$task['task']];

    if ($task['task_quantity'] <= $task_user_quantity) {

        //++ user wallet
        //check capacity of user's wallet

        $user_old_quantity = dbget(
            "SELECT quantity 
            FROM tg_user_wallets 
            WHERE 
                wallet_id= {$task['reward_wallet_id']} and
                user_id = {$_SESSION['user']['id']}
            "
        )[0]['quantity'];

        $wallet_capacity = dbget(
            "SELECT capacity FROM tg_wallets_levels WHERE wallet_id = {$task['reward_wallet_id']}"
        )[0]['capacity'];



        $wallet_new_quantity = $user_old_quantity + $task['reward_quantity'];

        if ($wallet_new_quantity > $wallet_capacity) {
            echo json_encode(array("error" => "wallet_capcity limit exceeded!"));
            exit;
        }

        //else 
        dbcmd(
            "UPDATE tg_user_wallets
            SET
                quantity = quantity + {$task['reward_quantity']}
            WHERE
                wallet_id= {$task['reward_wallet_id']} and
                user_id = {$_SESSION['user']['id']}"
        );

        //update counts
        dbcmd(
            "UPDATE tg_task_user
             SET
                 {$task['task']} =  $task_user_quantity - {$task['task_quantity']}
             WHERE user_id = {$_SESSION['user']['id']}"
        );

        $reward_wallet = dbget(
            "SELECT 
                icon_path,wallet_name
            FROM
                tg_wallets_levels
            WHERE
                wallet_id = {$task['reward_wallet_id']} &&
                level = {$_SESSION['user']['level']}
            LIMIT 
                1
            "
        )[0];

        echo json_encode(array("success" => "You have claimed <br>{$task['reward_quantity']} {$reward_wallet['wallet_name']}<br> by completing this task.<br>Your new {$reward_wallet['wallet_name']} balance : $wallet_new_quantity", "icon" => $reward_wallet['icon_path']));
        exit;
    }

    echo json_encode(array("error" => "Please complete the task to claim rewards."));
}

require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
