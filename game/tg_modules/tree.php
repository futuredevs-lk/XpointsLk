<!-- tree -->
<div class="body-widget" style="position: relative;padding: 0;overflow:hidden;background:url('/assets/images/garden.jpg');background-repeat:no-repeat;background-position:center">


    <!-- the main tree  -->
    <img id="main-tree" src="<?php
                                echo $main_tree_img;
                                ?>" alt="">

    <div id="tree-play" class="pos-ab tree-play">
        <!-- progress-bars -->

        <!-- level and profile -->
        <div class="game-progress-bars">
            <div class="progress">

                <div class="progress-bar progress-bar-striped active" style="width:
    <?php echo $user_level; ?>%;background-color: rgb(38, 64, 211);">
                </div>
                <div class="progress-value">
                    <?php echo $user_level; ?>/100
                </div>
            </div>
            <div class="pos-ab progress-icons">
                <img id="pr-profile" src="<?php echo $_SESSION['user']['image'] ?>" alt="">
            </div>
        </div>



        <!-- gold -->
        <div class="game-progress-bars wallet-pr">
            <div class="progress">
                <div id="tg-wallet-6-progress" class="progress-bar progress-bar-striped active" style="width:<?php
                                                                                                                echo $tg_pr_gold
                                                                                                                ?>%;background-color: rgb(255, 239, 10);">
                </div>
                <div class="progress-value">
                    <?php
                    echo  "{$tg_user_wallets[2]['quantity']} / {$tg_wallets[2]['capacity']}";
                    ?>
                </div>
            </div>
            <div class="pos-ab progress-icons">
                <img src="<?php
                            echo $tg_w_img_gold;
                            ?>" alt="">
            </div>
        </div>



        <!-- bras -->
        <div class="game-progress-bars wallet-pr">
            <div class="progress">
                <div id="tg-wallet-8-progress" class="progress-bar progress-bar-striped active" style="width:<?php
                                                                                                                echo $tg_pr_bronze
                                                                                                                ?>%;background-color: rgb(255, 239, 10);">
                </div>
                <div class="progress-value">
                    <?php
                    echo  "{$tg_user_wallets[4]['quantity']} / {$tg_wallets[4]['capacity']}";
                    ?>
                </div>
            </div>
            <div class="pos-ab progress-icons">
                <img src="<?php
                            echo $tg_w_img_bronze;
                            ?>" alt="">
            </div>
        </div>

        <!-- copper -->
        <div class="game-progress-bars wallet-pr">
            <div class="progress">
                <div id="tg-wallet-7-progress" class="progress-bar progress-bar-striped active" style="width:<?php
                                                                                                                echo $tg_pr_copper;
                                                                                                                ?>%;background-color: rgb(255, 239, 10);">
                </div>
                <div class="progress-value">
                    <?php
                    echo  "{$tg_user_wallets[3]['quantity']} / {$tg_wallets[3]['capacity']}";
                    ?>
                </div>
            </div>
            <div class="pos-ab progress-icons">
                <img src="<?php
                            echo $tg_w_img_copper;
                            ?>" alt="">
            </div>
        </div>

        <!-- xpoints water power-up -->
        <div class="tr-progress">


            <!-- water -->
            <div id="water-progress" class="progress">
                <img id="water-pr-img" src="<?php
                                            echo $tg_w_img_water;
                                            ?>" alt="">
                <div id="water-progress-bar" class="progress-bar progress-bar-warning progress-bar-striped active" style="width:<?php

                                                                                                                                $wallet_water = dbget("SELECT quantity,updated_at,in_progress FROM tg_user_wallets WHERE user_id=" . $_SESSION['user']['id'] . " and wallet_id = 1")[0];

                                                                                                                                $water_finish_before = dbget("SELECT water_to_xp_time FROM tg_maintain WHERE level =$user_level")[0]['water_to_xp_time']; //geting water to -xp time applied by admin


                                                                                                                                $water_wallet_updated_at = strtotime($wallet_water['updated_at']);

                                                                                                                                $water_time_left = time() - $water_wallet_updated_at; //how many sec more..

                                                                                                                                if ($wallet_water['in_progress'] == 1) {
                                                                                                                                    if ($water_time_left > $water_finish_before) {
                                                                                                                                        echo 0;
                                                                                                                                        dbcmd("UPDATE tg_user_wallets SET quantity = 0, updated_at=now(),in_progress=0 WHERE user_id=$user_id and wallet_id = 1");
                                                                                                                                        include_once("./tg_crud/tg_generate_xp.php");
                                                                                                                                    } else {
                                                                                                                                        echo $tg_pr_water;
                                                                                                                                        dbcmd("UPDATE tg_user_wallets SET quantity = {$tg_user_wallets[0]['quantity']}, updated_at=now() WHERE user_id=$user_id and wallet_id = 1");
                                                                                                                                    }
                                                                                                                                } else {
                                                                                                                                    echo $tg_pr_water;
                                                                                                                                }

                                                                                                                                ?>%;background-color: rgb(85, 220, 238);" <?php
                                                                                                                                                                            echo "
  in_progress = {$wallet_water['in_progress']}
  quantity    = {$tg_user_wallets[0]['quantity']}
  capacity    = {$tg_wallets[0]['capacity']}
  ";

                                                                                                                                                                            ?>>
                </div>
                <div id="tg-pr-wt-val" class="progress-value">
                    <?php
                    echo  round($tg_user_wallets[0]['quantity']), "/", $tg_wallets[0]['capacity'];
                    ?>
                </div>
            </div>

            <!-- xpoints -->
            <div class="progress">
                <img id="xpoints-pr-img" src="<?php
                                                echo $tg_w_img_xpoints;
                                                ?>" alt="">
                <div id="tg-wallet-2-progress" class="progress-bar progress-bar-warning progress-bar-striped active" style="width:<?php
                                                                                                                                    echo $tg_pr_xpoints
                                                                                                                                    ?>%;background-color: rgb(255, 14, 14);">
                </div>
                <div id="tg-pr-xp-val" class="progress-value">
                    <?php
                    echo  $tg_user_wallets[1]['quantity'], "/", $tg_wallets[1]['capacity'];
                    ?>
                </div>
            </div>

        </div>

        <!-- progress-bars -->



        <img id="beehive" src="<?php echo $beehive_img; ?>" alt="">

        <?php
        //GOT XP BY TABING WATER
        function gotXP()
        {
            echo "<script>document.getElementById('xpoints-bag').classList.add('glow');</script>";
        }
        ?>

        <img id="gift-2" src="<?php echo $gift_box_img ?>" alt="">

        <img id="xpoints-bag" src="<?php echo $xp_bag_img ?>" alt="" onclick="$('#xp-bag-window').fadeIn();">



        <img id="gemstone" src="/assets/images/plus.png" alt="" onclick="$('#my-asset-types-3').fadeIn();">


        <img id="bottle-img" src="/assets/images/plus.png" alt="" onclick="$('#my-asset-types-4').fadeIn();">

        <img id="gift" src="<?php echo $gift_box_img ?>" alt="">
        <img id="gift-2" src="<?php echo $gift_box_img ?>" alt="">

        <div style="position: absolute;bottom: 10%;right: 40%;width:20%;background-color:var(--theme-color);border-radius:10px">
            <p id="bottle-timer" style="text-align:center;"></p>
        </div>




        <img id="water-tab" src="<?php echo $tg_w_img_water ?>" alt="" onclick="pumpWater();">
    </div>
</div>