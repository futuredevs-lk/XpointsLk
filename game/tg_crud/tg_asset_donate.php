<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['receiver'])) {
    dbcmd("INSERT INTO tg_donations (
                sender,
                receiver,
                wallet_id,
                amount)
            VALUES(
                {$_SESSION['user']['id']},
                {$_POST['receiver']},
                {$_POST['wallet_id']},
                {$_POST['amount']}
            )
        ");

    sendNotification(
        $_SESSION['user']['id'],
        "You are donating your asset.",
        "#"
    );
    include_once("./task-user-update.php");
    updateTasks($_SESSION['user']['id'], 'donating');
    echo "success";
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
