<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (isset($_POST['fetch_tasks'])) {

    $tasks = dbget(
        "SELECT 
            tg_task_config.id,
            tg_task_config.task,
            tg_task_config.text,
            tg_task_config.task_quantity,
            tg_task_config.reward_wallet_id,
            tg_task_config.reward_quantity,
            tg_wallets_levels.icon_path
        FROM tg_task_config 
        JOIN tg_wallets_levels 
        ON 
            tg_task_config.task_wallet_id =  tg_wallets_levels.wallet_id AND 
            tg_task_config.level =  tg_wallets_levels.level  
        WHERE tg_task_config.level = '{$_SESSION['user']['level']}'
        "
    );


    foreach ($tasks as $task) {
        $reward_icon = dbget(
            "SELECT icon_path FROM tg_wallets_levels WHERE wallet_id = {$task['reward_wallet_id']}"
        )[0]['icon_path'];

        $task_user_quantity = dbget("SELECT {$task['task']} FROM tg_task_user WHERE user_id = {$_SESSION['user']['id']}")[0][$task['task']];

        echo <<<EOT
        <div class="task-cont">
            <div class="task-icon"><img src="{$task['icon_path']}"></div>
            <div class="task-desc">
                {$task['text']} [$task_user_quantity/{$task['task_quantity']}] <br>
                <div class="task-reward">
                    <img src="{$reward_icon}">
                    <span>{$task['reward_quantity']}</span>
                </div>
            </div>
            <div class="task-claim"><button class="btn" onclick="claim_reward({$task['id']})">Claim</button></div>
        </div> 
        EOT;
    }
}
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
