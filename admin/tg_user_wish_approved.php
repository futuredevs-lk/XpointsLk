<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
if(isset($_POST['user_id'])){
    //setting requst updated in won table
    dbcmd(
        "UPDATE tg_user_won
        SET approve = {$_POST['approve']},
        updated_at =now()
        WHERE id={$_POST['request_id']}"
    );
    
    dbcmd(
        "DELETE FROM tg_user_wallet_use
        WHERE user_id={$_POST['user_id']}"
    );
    // if approved
    if($_POST['approve']==1){
            //upgrading level
        dbcmd(
            "UPDATE users SET level = level+1
            WHERE id={$_POST['user_id']}"
        );
        //sending notification
        dbcmd(
            "INSERT INTO user_notification(
                user_id,
                text,
                page
            )
            VALUES(
                {$_POST['user_id']},
                'Congrats,You are being upgraded to New Level',
                '#'
            )"
        );

        //reseting wallets
        dbcmd(
            "DELETE FROM tg_user_wallets
            WHERE user_id = {$_POST['user_id']}"
        );
    }
    else{
        //sending notification
        dbcmd(
            "INSERT INTO user_notification(
                user_id,
                text,
                page
            )
            VALUES(
                {$_POST['user_id']},
                'We are sorry to inform you that,We found your profile as a fake one.Therefore we could not validate your request.Be matured next time..',
                '#'
            )"
        );
    }
    

}

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>