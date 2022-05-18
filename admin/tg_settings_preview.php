<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./admin-header.php");
    include("admin-L1-permission.php");


    $level = 0;
    if (isset($_POST['select-level'])) {
        $_SESSION['tg-maintain']['preview-level'] = $_POST['level'];
        $level = $_SESSION['tg-maintain']['preview-level'];
    }
    ?>
    <title>TG Settings Preview</title>

    <style>
        .well {
            max-height: 40vh;
            word-wrap: break-word;
            width: 100%;
            overflow: auto;
        }

        td img {
            max-width: 35px
        }
    </style>
</head>

<body>



    <div class="container-fluid">
        <div class="row content">


            <div class="col-sm-3">
                <div class="well">
                    <form method="POST" action="">
                        <div class="form-group">
                            <select class="form-control" type="number" name="level" id="select-level">
                                <option value="" disabled selected>Select Level</option>
                                <script>
                                    for (let i = 1; i < 101; i++) {
                                        document.getElementById("select-level").innerHTML += `
                            <option value="${i}">Level ${i}</option>
                            `

                                    }
                                </script>
                            </select>
                        </div>
                        <button class="btn" name="select-level">Continue</button>
                    </form>
                </div>
            </div>


            <div class="col-sm-9">
                <div class="well">
                    <h4>TG Best Players</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>User ID</th>
                                <th>UserNname</th>
                                <th>Level</th>
                                <th>Wish Ticket</th>
                                <th>Approve</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php
                            $best_players = dbget(
                                "SELECT
                            users.id,
                            users.username,
                            tg_user_won.level ,
                            tg_wallets_levels.wallet_name,
                            tg_user_won.approve


                        FROM tg_user_won
                        JOIN users ON tg_user_won.user_id = users.id
                        JOIN tg_wallets_levels
                        ON tg_user_won.wish_t_id = tg_wallets_levels.wallet_id
                        WHERE tg_user_won.level = $level"
                            );
                            if (!empty($best_players)) {
                                foreach ($best_players as $player) {
                                    echo "
                                <tr>
                                    <td><a href='/user/?id={$player['id']}'>{$player['id']}</a></td>
                                    <td>{$player['username']}</td>
                                    <td>{$player['level']}</td>
                                    <td>{$player['wallet_name']}</td>
                                    <td>{$player['approve']}</td>
                                </tr>";
                                }
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="col-sm-12">
                <div class="well" style="max-height: 40vh;">
                    <h4>TG Wallets</h4>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Level</th>
                                <th>Wallet_name</th>
                                <th>Icon</th>
                                <th>Capacity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tg_wallets = dbget("SELECT * FROM tg_wallets_levels
                    WHERE level =$level");
                            if (!empty($tg_wallets)) {
                                foreach ($tg_wallets as $tg_wallet) { ?>
                                    <tr>
                                        <td><?php echo $tg_wallet['level'] ?></td>
                                        <td><?php echo $tg_wallet['wallet_name'] ?></td>
                                        <td><img src="<?php echo $tg_wallet['icon_path'] ?>"></td>
                                        <td><?php echo $tg_wallet['capacity'] ?></td>

                                    </tr>
                            <?php }
                            } ?>


                        </tbody>
                    </table>


                </div>


            </div>

            <div class="col-sm-6">
                <div class="well">
                    <h4>Water to XP Time</h4>
                    <table class="table">
                        <tr>
                            <th>Level</th>
                            <th>Time in Sec</th>
                        </tr>
                        <?php
                        $water_to_xp = dbget(
                            "SELECT * FROM tg_maintain
                    WHERE level =$level"
                        );
                        if (!empty($water_to_xp)) {
                            foreach ($water_to_xp as $item) {
                                echo "<tr>
                                <td>{$item['level']}</td>
                                <td>{$item['water_to_xp_time']}</td>
                              </tr>";
                            }
                        }
                        ?>

                    </table>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="well">
                    <h4>TG Wallet Exchange Rates</h4>
                    <table class="table">
                        <tr>
                            <th>Asset_A</th>
                            <th>Asset_B</th>
                            <th>1 Asset_A = ? Asset_B</th>
                        </tr>
                        <?php
                        $limit = dbget("SELECT COUNT(id) FROM tg_wallet_exchange_rates WHERE level = $level")[0];
                        echo ($limit[0]);
                        $wallet_exchange_rates = dbget(
                            "SELECT 
                        tg_wallet_exchange_rates.wallet_2_rate,
                        wallet_1_name.wallet_name as wallet_1,
                        wallet_2_name.wallet_name as wallet_2
                    FROM 
                        tg_wallet_exchange_rates 
                    JOIN 
                        tg_wallets_levels as wallet_1_name
                    ON 
                        tg_wallet_exchange_rates.wallet_1_id = wallet_1_name.wallet_id
                    JOIN 
                        tg_wallets_levels as wallet_2_name
                    ON 
                        tg_wallet_exchange_rates.wallet_2_id = wallet_2_name.wallet_id
                    WHERE 
                        tg_wallet_exchange_rates.level =$level"
                        );
                        if (!empty($wallet_exchange_rates)) {
                            foreach ($wallet_exchange_rates as $item) {
                                echo "<tr>
                                <td>{$item['wallet_1']}</td>
                                <td>{$item['wallet_2']}</td>
                                <td>{$item['wallet_2_rate']}</td>
                              </tr>";
                            }
                        }
                        ?>

                    </table>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="well">
                    <h4>Tg Elements</h4>
                    <table class="table">
                        <tr>
                            <th>Element</th>
                            <th>Icon path</th>
                        </tr>
                        <?php
                        $tg_elements = dbget(
                            "SELECT * FROM tg_main_elements
                    WHERE level =$level"
                        );
                        if (!empty($tg_elements)) {
                            foreach ($tg_elements as $tg_element) {
                                echo "
                        <tr>
                            <td>{$tg_element['name']}
                            </td>
                            <td><a href='{$tg_element['image_path']}'>{$tg_element['image_path']}</a></td>
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

</body>


</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>