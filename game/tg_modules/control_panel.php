<!-- control panel -->
<div class="body-widget" style="padding: 0;overflow: hidden;">


    <div class="main-cats scroll scroll-x">
        <!-- portifolio -->
        <div class="mc-name tablinks" id="click-it-1" onclick="openCat(event,'main-tab-1');">
            <img src="<?php echo $_SESSION['user']['image']; ?>" alt="">
        </div>
        <!-- market -->
        <div class="mc-name tablinks" onclick="openCat(event,'main-tab-2')">
            <img src="<?php echo $cp_market_img; ?>" alt="">
        </div>

        <!-- gift card -->
        <div id="gift-card-panel" class="mc-name tablinks" onclick="openCat(event,'main-tab-3');">
            <img src="<?php echo $cp_gift_img ?>" alt="">
        </div>

        <!-- offers -->
        <div class="mc-name tablinks" onclick="openCat(event,'main-tab-4')">
            <img src="<?php echo $cp_offers_img ?>" alt="">
        </div>
    </div>


    <!-- main-cat-contents -->

    <!-- portifolio -->
    <div id="main-tab-1" class="main-tab-content">

        <div class="sub-cats scroll scroll-y">
            <div class="sc-name btn sub-tablinks" id="click-it-2" onclick="openSubCat(event,'sc-assets')">
                <img src="<?php echo $cp_assets_img ?>" alt="">
            </div>
            <!-- <div class="sc-name btn sub-tablinks" onclick="openSubCat(event,'my-shop')">
                <img src="<?php echo $cp_my_shop_img ?>" alt="">
            </div> -->
            <div class="sc-name btn" onclick="openSubCat(event,'sub-tab-2')">
                <img src="<?php echo $cp_ranking_img ?>" alt="">
            </div>
            <div class="sc-name btn sub-tablinks" onclick="openSubCat(event,'cp-task')">
                <img src="<?php echo $cp_tasks_img ?>" alt="">
            </div>
            <div class="sc-name btn" onclick="openSubCat(event,'sub-tab-3')">
                <img src="<?php echo $cp_settings_img ?>" alt="">
            </div>
        </div>


        <div id="sc-assets" class="sub-tab-content scroll scroll-y">
            <?php
            foreach ($tg_wallets_cat as $tg_wallet_sc) {
                echo <<<EOT
  <div class="tab-item" onclick="$('#my-asset-types-{$tg_wallet_sc['id']}').fadeIn();">
    <p>{$tg_wallet_sc['name']}</p>
    <img src="{$tg_wallet_sc['icon_path']}" alt="">
  </div>
  EOT;
            }
            ?>
        </div>

        <div id="my-shop" class="sub-tab-content scroll scroll-y">

        </div>



        <div id="sub-tab-2" class="sub-tab-content scroll scroll-y">Rank</div>


        <div id="cp-task" class="sub-tab-content scroll scroll-y">
            <style>
                .task-cont {
                    display: flex;
                    font-size: 10px;
                    text-align: left;
                    font-family: monospace;
                    align-items: center;
                    width: 96%;
                }

                .task-desc {
                    width: 70%;
                }

                .task-icon {
                    width: 20%;
                }

                .task-reward {
                    width: 10%;
                    display: flex;
                }
            </style>
            <div class="title">Task Center</div>

            <div class="scroll scroll-y" style="height: 80%;margin-top:2%" id="task-panel">
            </div>
        </div>

        <div id="sub-tab-3" class="sub-tab-content scroll scroll-y">
            <style>
                #sub-tab-3 .btn {
                    width: 80%;
                    margin-top: 2%;
                }
            </style>
            <p>Turn on/off game audio.</p>
            <input type="checkbox" hidden="hidden" id="tg-bg-audio-switch" onchange="triggerBgm()">
            <label class="switch" for="tg-bg-audio-switch"></label>
            <br>
            <button class="btn" onclick="$('#story-slides').show()">Story</button>
            <br>
            <button class="btn">Guide</button>
            <br>
            <button class="btn" onclick="confirm_panel('Are you sure do you want to execute this process?','none',clear_data)">Reset game</button>

        </div>


    </div>



    <!-- shop -->
    <div class="main-tab-content" id="main-tab-2"></div>
    <script>

    </script>


    <!-- gift-cart-requst -->
    <div class="main-tab-content" id="main-tab-3">
        <style>
            #main-tab-3 {
                text-align: center;

            }

            #main-tab-3 img,
            #main-tab-3 i {
                width: 20%;
                margin: auto;
                cursor: pointer;
                height: auto;
            }
        </style>
        <div>
            <div style="display:flex;margin-top:25%">
                <img id="tg-request-potion" src="/assets//images/plus.png" alt="" onclick="$('#my-asset-types-7').fadeIn();">

                <i class="fa fa-plus" aria-hidden="true"></i>

                <img id="tg-request-wish-t" src="/assets/images/plus.png" alt="" onclick="$('#my-asset-types-5').fadeIn();">

                <i class="fas fa-equals"></i>
                <img src="/assets/images/ticket.png" alt="">
            </div>
            <p id="gift-card-timer"></p>
        </div>


    </div>
    <style>
        #main-tab-4,
        .offer-container .tab-item {
            padding: 0
        }

        .offer-container img {
            object-fit: contain;
            height: 40vh;
        }
    </style>
    <!-- offers  Ad-left-->
    <div class="main-tab-content" id="main-tab-4">
        <div class="offer-container slider" style="width: 100%;">
            <?php
            foreach (array_merge($vendorsL, $vendorsR) as $vendor) {
                echo "<a href='{$vendor['url']}' class='slide-item offer-slide'>
                    <img src='{$vendor['img']}' alt=''>
                  </a>";
            }
            ?>

        </div>
    </div>

</div>