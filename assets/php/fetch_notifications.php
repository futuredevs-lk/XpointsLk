<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (!isset($_SESSION['user'])) {
    echo "<h5>Please <a href='/login'>Login</a> to access this feature</h5>";
    exit;
}

$notifications = dbget(
    "SELECT * FROM user_notification
    WHERE 
        user_id = {$_SESSION['user']['id']} and
        seen = 0
    ORDER BY datetime desc"
);
if (!empty($notifications)) {
    echo "<h5>New Notifications</h5>
          <Button class='btn' onclick='clear_notifications()'>clear all</Button> ";
    foreach ($notifications as $notification) {
        echo <<<EOT
        <!-- Content -->
        <div class="new-feeds-content">
            <div class="content-thumbnail">
            <img class="thumbnail-img" src="https://www.freeiconspng.com/thumbs/comment-png/comment-png-1.png" alt="">
            </div>
            <div class="content-desc" onclick="location.href='{$notification['page']}'">
            <p>{$notification['text']}<br>
            {$notification['datetime']}
            </p>
            </div>
            <div class="remove-content" onclick="notification_seen(this,{$notification['id']})"><p>
            <i class="fa fa-trash" aria-hidden="true"></i>
            </p></div>
        </div>
        <!-- content -->
        EOT;
    }
} else {
    echo "<h5>You have no New Notifications!</h5>";
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
