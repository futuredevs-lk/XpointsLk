<!-- game-notification-windows ------------>


<!-- water to xpoints -->
<div id="xp-bag-window" class="pos-fx all-0 game-notification blurBg desk-mw">
    <button class="btn cls-btn close-btn" onclick="$(this).parent().fadeOut()">
        <img src="<?php echo $site_elements[11]['img'] ?>" alt="">
    </button>
    <div class="g-noty-title">
        XP BAG
    </div>
    <div class="xp-bag-content">

        <img style="height: 50vh;object-fit:contain" src="<?php echo $xp_bag_img ?>" alt="">
        <h3 class="title" id="xp-wallet-bal"></h3>
    </div>
</div>

<div id="fetched-my-assets"></div>



<style>
    #my-assets-sell-item img {
        height: 40%;
    }
</style>

<!-- my assets sell or use or transfer-->
<div id="my-assets-sell" class="pos-fx all-0 game-notification desk-mw blurBg">
    <div class="cls-btn" onclick="$(this).parent().fadeOut()">
        <img src="<?php echo $site_elements[11]['img']; ?>" alt="">
    </div>
    <div class="g-noty-title">
        my assets -sell use transfer
    </div>
    <div id="my-assets-sell-item" class="tab-item scroll scroll-y" style="width: 100%;height:100%;margin: 0;padding:2vh"></div>
</div>

<!-- =========================================== -->





<!-- market-item-popup -->
<style>
    #market-item-tabitem img {
        height: 25%;
    }

    #market-item-tabitem p {
        font-size: 100%;
    }
</style>
<div id="market-item" class="pos-fx all-0 game-notification blurBg desk-mw">
    <div class="cls-btn" onclick="$(this).parent().fadeOut()">
        <img src="<?php echo $site_elements[11]['img']; ?>" alt="">
    </div>
    <div class="g-noty-title">
        Market Item
    </div>
    <div id="market-item-tabitem" class="tab-item" style="width: 100%;height:100%;margin:0;padding:2vh;">

    </div>
</div>






<!-- ====================================== -->


<!-- water-has-pumped-100% -->

<div class="pos-fx all-0 game-notification blurBg desk-mw" id="water-full">
    <div class="cls-btn" onclick="$(this).parent().fadeOut()">
        <img src="<?php echo $site_elements[11]['img']; ?>" alt="close">
    </div>
    <h3 class="title">
        WATER LIMIT
        <p>Your water bucket is fully filled! <br>
            you can get water after <?php echo $water_finish_before / 60 ?> Minutes
        </p>
    </h3>
</div>




<!-- confirm-alert -->
<div class="pos-fx all-0 confirm-alert blurBg">
    <div class="confirm-text">
        Are you sure want to add this stone?
    </div>
    <div class="confirm-response">
        <button class="btn confirm-true">Yes</button>
        <button class="btn confirm-false">No </button>
    </div>
</div>


<!-- congratulation_alert -->
<div id="congrats-window" class="pos-fx all-0 blurBg desk-mw scroll scroll-y">
    <button class="btn close-btn" onclick="$(this).parent().fadeOut()">
        <img src="<?php echo $site_elements[11]['img']; ?>" alt="close">
    </button>
    <div id="to-congrats">
        <h5>Congratulation you have got 5500 XP </h5>
        <img src="" alt="">
    </div>
</div>

<style>
    #donate-details img {
        height: 30vh;
        object-fit: cover;
    }
</style>
<!-- donate window -->
<div id="donate-window" class="pos-fx all-0 game-notification blurBg desk-mw" style="display: none;text-align:center;padding:5%;z-index:100;">
    <div class="cls-btn" onclick="$(this).parent().fadeOut()"><img src="<?php echo $site_elements[11]['img']; ?>" alt="close"></div>
    <div style="height:100%" class="scroll scroll-y">
        <div id="donate-details"></div>
        <h1>To Whom do you want to send it?</h1>
        <select name="donate-player" id="donate-player">
            <option value="" selected disabled>Select Player</option>
            <?php
            $players = dbget("SELECT id,created_at FROM users WHERE id!={$_SESSION['user']['id']}");
            foreach ($players as $player) {
                echo "<option value='{$player['id']}'>#000{$player['id']}</option>";
            }
            ?>
        </select>
        <button class="btn" style="margin-top:2%" onclick="tg_send_donation()">Send Now..</button>
    </div>
</div>




<!-- game-notification-windows ---------->


<!-- warning alert -->
<style>
    .warning-alert {
        padding: 35vh 2%;
        text-align: center;
        display: none;
        z-index: 102;
    }

    .warning-text {
        border-radius: 12px;
        border: 2px solid;
        margin-bottom: 2%;
        font-size: 120%;
        color: white;
    }
</style>
<div class="pos-fx all-0 blurBg warning-alert desk-mw">
    <div class="warning-text">
        Please select quantity!
    </div>
    <button class="btn" onclick="$(this).parent().fadeOut()">Okay</button>
</div>