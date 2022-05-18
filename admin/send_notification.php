<?php

require("./admin-header.php");
include("admin-L1-permission.php");


if (isset($_POST['send-notification'])) {
    if ($_POST['user'] == "*") {
        foreach (dbget("SELECT id FROM users") as $user) {
            dbcmd("INSERT INTO user_notification(
                    user_id,
                    text,
                    page)
                VALUES(
                    {$user['id']},
                    '{$_POST['notification']}',
                    '{$_POST['href']}'
                    )
            ");
        }
        header("location:./send_notification.php");
        exit;
    }


    dbcmd(
        "INSERT INTO user_notification(
            user_id,
            text,
            page
        )
        VALUES(
            {$_POST['user']},
            '{$_POST['notification']}',
            '{$_POST['href']}'
        )"
    );
    header("location:./send_notification.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <form class="body-widget" action="" method="POST">
            <h4 class="title">Send Notification to users</h4>
            <div class="form-group">
                <select class="form-control" name="user" id="" required>
                    <option value="" selected disabled>Select User</option>
                    <option value="*">All users</option>
                    <?php
                    $users = dbget("SELECT * FROM users");
                    foreach ($users as $user) {
                        echo "<option value={$user['id']}>#{$user['id']}-{$user['fname']} {$user['lname']}</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <textarea class="form-control rounded-0" name="notification" id="" rows="10" placeholder="What you wanna say?" required></textarea>
            </div>
            <div class="form-group">
                <input class="form-control" type="text" name="href" id="" placeholder="URL" required>
            </div>
            <button name="send-notification" class="btn">Send</button>
        </form>
    </div>
</body>

</html>








<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>