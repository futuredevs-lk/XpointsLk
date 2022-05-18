<?php
function updateTasks($userId, $field)
{

    dbcmd(
        "INSERT INTO tg_task_user (user_id) VALUES ($userId) 
         ON DUPLICATE KEY UPDATE
            $field = $field + 1"
    );
}
