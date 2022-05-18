<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./admin-header.php");
    include("admin-L1-permission.php");
    ?>
    <title>Manage Donations | TG-Xpointslk</title>
    <style>
        label img {
            width: 70px;
            margin-left: 5%;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row content">
            <br>
            <div class="col-sm-12">
                <h2>Manage Donations | TG-Xpointslk</h2>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="well scroll scroll-x" style="overflow: scroll;">
                            <table class="table">
                                <tr>
                                    <th>Sender</th>
                                    <th>Receiver</th>
                                    <th>Asset Name</th>
                                    <th>Amount</th>
                                    <th>Requested on</th>
                                    <th>Action</th>
                                </tr>
                                <style>
                                    td img {
                                        width: 20px;
                                    }
                                </style>
                                <?php
                                $tg_donations =  dbget("SELECT 
                                                                tg_donations.*,
                                                                wallet_name,
                                                                capacity,
                                                                icon_path
                                                        FROM tg_donations
                                                        JOIN
                                                            tg_wallets_levels
                                                        ON 
                                                            tg_donations.wallet_id = tg_wallets_levels.wallet_id");
                                if (!empty($tg_donations)) {
                                    foreach ($tg_donations as $tg_donation) {
                                        echo "
                                            <tr>
                                                <td>
                                                    <a href='/user?id={$tg_donation['sender']}'>
                                                    #_00{$tg_donation['sender']}
                                                    </a>
                                                </td>
                                                <td>
                                                    <a href='/user?id={$tg_donation['receiver']}'>
                                                    #_00{$tg_donation['receiver']}
                                                    </a>
                                                </td>
                                                <td>
                                                    {$tg_donation['wallet_name']}
                                                    <img src='{$tg_donation['icon_path']}'>
                                                </td>
                                                <td>{$tg_donation['amount']}</td>
                                                <td>{$tg_donation['created_at']}</td>
                                                <td>
                                                    <a href='#' onclick='tg_donation_action(
                                                        this,
                                                        {$tg_donation['id']},
                                                        1,
                                                        {$tg_donation['sender']},
                                                        {$tg_donation['receiver']},
                                                        {$tg_donation['wallet_id']},
                                                        {$tg_donation['amount']}
                                                        )'>Approve</a>
                                                    <a href='#' onclick='tg_donation_action(
                                                        this,
                                                        {$tg_donation['id']},
                                                        0,
                                                        {$tg_donation['sender']},
                                                        {$tg_donation['receiver']},
                                                        {$tg_donation['wallet_id']},
                                                        {$tg_donation['amount']}
                                                        )'>Decline</a>
                                                </td>
                                            </tr>
                                            ";
                                    }
                                }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</body>
<script src="script.js"></script>
<script>
    function tg_donation_action(element, don_id, action, sender, receiver, wallet_id, amount) {
        $(element).parent().parent().remove();
        $.post("./tg_manage_donation_action.php", {
            action: action,
            don_id: don_id,
            sender: sender,
            receiver: receiver,
            wallet_id: wallet_id,
            amount: amount
        }, function(data) {
            console.log(data)
        })
    }
</script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>