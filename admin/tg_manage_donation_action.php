<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['action'])) {
    //remove from tg_donations
    dbcmd(
        "DELETE FROM tg_donations
         WHERE
            id = {$_POST['don_id']}"
    );

    if ($_POST['action'] == 1) {


        //check capacity of receiver.
        $receiver_capacity = dbget("SELECT capacity
                                    FROM tg_wallets_levels
                                    JOIN users
                                    ON
                                        users.level = tg_wallets_levels.level
                                    WHERE
                                        wallet_id = {$_POST['wallet_id']} &&
                                        users.id = {$_POST['receiver']}")[0]['capacity'];


        $receiver_balance = dbget("SELECT quantity 
                                   FROM tg_user_wallets
                                   WHERE
                                        user_id = {$_POST['receiver']}&&
                                        wallet_id = {$_POST['wallet_id']}
                                    ")[0]['quantity'];
        //if receiver's current balance + donating amount > capacity; reject opreation
        if ($_POST['amount'] + $receiver_balance > $receiver_capacity) {

            //check sender's capacity
            $sender_capacity = dbget("SELECT capacity
                    FROM tg_wallets_levels
                    JOIN users
                    ON
                        users.level = tg_wallets_levels.level
                    WHERE
                        wallet_id = {$_POST['wallet_id']} &&
                        users.id = {$_POST['sender']}")[0]['capacity'];


            $sender_balance = dbget("SELECT quantity 
                FROM tg_user_wallets
                WHERE
                        user_id = {$_POST['sender']}&&
                        wallet_id = {$_POST['wallet_id']}
                    ")[0]['quantity'];

            if (!$_POST['amount'] + $sender_balance > $sender_capacity) {
                //sender's wallet ++ (undoing) 
                dbcmd("UPDATE tg_user_wallets
                            SET 
                                quantity = quantity+{$_POST['amount']}
                            WHERE
                                wallet_id = {$_POST['wallet_id']} &&
                                user_id   = {$_POST['sender']}
                            ");

                //sending notification
                sendNotification(
                    $_POST['sender'],
                    "We are sorry to inform you that, due to capacity limitation of the receivers wallet the donation you made was not succeed. We have refunded your asset. Thank you.",
                    '#'
                );
                echo "refund:OK";
                exit();
            } else {
                //sending notification
                sendNotification(
                    $_POST['sender'],
                    "We are sorry to inform you that, due to capacity limitation of the receivers wallet the donation you made was not succeed.",
                    '#'
                );
                echo "refund:failed => sender's capacity limit exceeded!";
                exit();
            }
        }




        //check sender's balance;
        $sender_balance = dbget("SELECT quantity 
                FROM tg_user_wallets
                WHERE
                        user_id = {$_POST['sender']}&&
                        wallet_id = {$_POST['wallet_id']}
                    ")[0]['quantity'];
        if($sender_balance-$_POST['amount']<0){
            //sending notification
            sendNotification(
                $_POST['sender'],
                "We are sorry to inform you that, due to Insufficiant balance in your wallet the donation you made was not succeed.",
                '#'
            );
            echo "Donation Failed; => Sender is a begger!!";
            exit();
        }

        //-- from sender wallet
        dbcmd("UPDATE tg_user_wallets
                SET 
                    quantity = quantity-{$_POST['amount']}
                WHERE
                    wallet_id = {$_POST['wallet_id']} &&
                    user_id   = {$_POST['sender']}
                ");

        //sending notification to sender
        sendNotification(
            $_POST['sender'],
            'The donation you made has been approved.',
            '#'
        );


        //++ receiver's wallet
        dbcmd("UPDATE tg_user_wallets
                SET 
                    quantity = quantity+{$_POST['amount']}
                WHERE
                    wallet_id = {$_POST['wallet_id']} &&
                    user_id   = {$_POST['receiver']}
                ");

        //sending notification to sender
        sendNotification(
            $_POST['receiver'],
            'A new Donation has been arived.',
            '#'
        );

        echo "donation:success";
    }


    //decline
    if ($_POST['action'] == 0) {
        //sending notification to sender
        sendNotification(
            $_POST['sender'],
            'We are sorry to inform you that,we could not approve the donation you had requested.',
            '#'
        );

        echo "donation:declined";
    }
}


?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>