<?php

require("./admin-header.php");
include("admin-L1-permission.php");


if (isset($_POST['give-rewards'])) {
    if ($_POST['user'] == "*") {
        $_SESSION['message']['error'] = "This feature is under development!!";
        header("location:./reward_user.php");
        exit;
    }

    //Check capacity of wallet; if quantity > capacity : error!
    $uw_capacity = dbget(
        "SELECT 
                            capacity 
                        FROM 
                            tg_wallets_levels
                        JOIN 
                            users
                        ON
                            tg_wallets_levels.level = users.level
                        WHERE 
                            users.id={$_POST['user']} &&
                            tg_wallets_levels.wallet_id={$_POST['wallet']}"
    )[0]['capacity'];
    $uw_balance = dbget("SELECT quantity
                        FROM 
                            tg_user_wallets
                        WHERE 
                            tg_user_wallets.wallet_id = {$_POST['wallet']} &&
                            tg_user_wallets.user_id = {$_POST['user']}")[0]['quantity'];
    $current = $_POST['quantity'] + $uw_balance > $uw_capacity;
    if ($_POST['quantity'] + $uw_balance > $uw_capacity) {
        $_SESSION['message']['error'] = "User's wallet capacity limit exceeded!";
        header("location:./reward_user.php");
        exit;
    }


    //finally give rewards to user
    dbcmd(
        "UPDATE tg_user_wallets
        SET 
            quantity    = {$_POST['quantity']},
            updated_at  = now()
        WHERE
            user_id= {$_POST['user']} &&
            wallet_id = {$_POST['wallet']}"
    );
    //send notification
    sendNotification($_POST['user'], "You just got a reward from Admin.", "#");
    $_SESSION['message']['success'] = "Reward has been successfully Sent to user!!";
    header("location:./reward_user.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reward User</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <?php require('../assets/php/response.php'); ?>
        <form class="body-widget" action="" method="POST">
            <h4 class="title">Give Reward to users</h4>
            <div class="form-group">
                <select class="form-control" name="user" id="" required>
                    <option value="" selected disabled>Select User</option>
                    <option value="*">All users</option>
                    <?php
                    $users = dbget("SELECT id,fname,lname,level FROM users");
                    foreach ($users as $user) {
                        echo "<option value={$user['id']}>#{$user['id']}-{$user['fname']} {$user['lname']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="wallet" id="tg_wallets">
                    <option value="" disabled selected>Select wallet</option>
                    <?php
                    $wallets = dbget("SELECT 
                                    DISTINCT(wallet_id),wallet_name
                                    FROM 
                                    tg_wallets_levels");
                    foreach ($wallets as $wallet) {
                        echo "<option value={$wallet['wallet_id']}>{$wallet['wallet_name']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <input class="form-control" type="number" name="quantity" id="" placeholder="Quantity" required>
            </div>
            <button name="give-rewards" class="btn">Proceed.</button>
        </form>
    </div>
</body>

</html>








<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>