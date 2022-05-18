<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['clear_data'])) {
    $user_id = $_SESSION['user']['id'];
    dbcmd("UPDATE users SET level = 0 WHERE id = {$user_id}");
    dbcmd("DELETE FROM tg_user_wallets WHERE user_id = {$user_id}");
    dbcmd("DELETE FROM tg_user_won WHERE user_id = {$user_id}");
    dbcmd("DELETE FROM tg_user_wallet_use WHERE user_id = {$user_id}");
    echo "Good bye!";
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
