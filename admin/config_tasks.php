<?php

require("./admin-header.php");
include("admin-L1-permission.php");

if (!isset($_SESSION['tg-maintain']['selected-level'])) {
    $_SESSION['message']['error'] = "PLEASE SELECT LEVEL!";
    header("location:game-maintain.php");
    exit;
}
$level = $_SESSION['tg-maintain']['selected-level'];
if (isset($_POST['add-task'])) {
    dbcmd(
        "INSERT INTO tg_task_config (
            level,
            text,
            task,
            task_wallet_id,
            task_quantity,
            reward_wallet_id,
            reward_quantity
            )
        VALUES(
            {$level},
            '{$_POST['text']}',
            '{$_POST['task']}',
            {$_POST['task-wallet']},
            {$_POST['task-quantity']},
            {$_POST['reward-wallet']},
            {$_POST['reward-quantity']}
            )"
    );
    $_SESSION['message']['success'] = "Task Added!";
    header("location:config_tasks.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CONFIG TASKS</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <form class="body-widget" action="" method="POST">
            
            <?php require('../assets/php/response.php'); ?>

            <h4 class="title">Config Tasks</h4>
            <P>All settings will be applied for : Level <?php echo $level; ?> </P>
            <div class="form-group">
                <textarea class="form-control" name="text" id="" cols="30" rows="10" placeholder="Write the task.." required></textarea>
            </div>
            <div class="form-group">
                <select name="task" id="">
                    <option value="donating">Donating</option>
                    <option value="watering">Watering</option>
                    <option value="selling">Selling</option>
                    <option value="buying">Buying</option>
                </select>
            </div>
            <div class="form-group">
                <select class="form-control" name="task-wallet" id="" required>
                    <option value="" selected disabled>Task Wallet</option>
                    <?php
                    $wallets = dbget("SELECT wallet_name,wallet_id FROM  tg_wallets_levels  WHERE level = {$level}");
                    foreach ($wallets as $wallet) {
                        echo "<option value={$wallet['wallet_id']}>#{$wallet['wallet_id']}-{$wallet['wallet_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input class="form-control" type="number" name="task-quantity" id="" placeholder="Task quantity" required>
            </div>


            <div class="form-group">
                <select class="form-control" name="reward-wallet" id="" required>
                    <option value="" selected disabled>Reward Wallet</option>
                    <?php
                    foreach ($wallets as $wallet) {
                        echo "<option value={$wallet['wallet_id']}>#{$wallet['wallet_id']}-{$wallet['wallet_name']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <input class="form-control" type="number" name="reward-quantity" id="" placeholder="Reward quantity" required>
            </div>
            <button name="add-task" class="btn">Add Task</button>
        </form>
    </div>
</body>

</html>








<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>