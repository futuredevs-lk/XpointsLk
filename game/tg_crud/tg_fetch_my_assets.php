<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['fetch_my_assets'])) {
    $user_id = $_SESSION['user']['id'];
    $user_level = $_SESSION['user']['level'];
    $tg_wallets_cat = dbget("SELECT * FROM tg_wallets_cat  WHERE level=$user_level");

    $site_elements = dbget("SELECT * FROM site_elements");

    foreach ($tg_wallets_cat as $tg_wallet_sc) {
        echo <<<EOT
    <div id="my-asset-types-{$tg_wallet_sc['id']}" class="pos-fx all-0 game-notification desk-mw blurBg my-assets-types">
    <div class="cls-btn" onclick="$(this).parent().fadeOut();">
        <img src="{$site_elements[11]['img']}" alt="">
    </div>
    <div class="g-noty-title">
        My {$tg_wallet_sc['name']} Types
    </div>
    <div class="scroll scroll-y my-asset-types">
    EOT;


        $my_asset_types = dbget(
            "SELECT 
            tg_wallets_levels.wallet_id AS wallet_id,
            tg_wallets_levels.wallet_name,
            tg_wallets_levels.icon_path,
            tg_user_wallets.quantity,
            tg_wallets_levels.cat_id as cat_id 
        FROM 
            tg_user_wallets 
        JOIN 
            tg_wallets_levels 
        ON 
            tg_user_wallets.wallet_id = tg_wallets_levels.wallet_id 
        WHERE 
            user_id = $user_id and 
            cat_id =" . $tg_wallet_sc['id'] . " and 
            level=$user_level and
            quantity!=0
        "
        );
        if (!empty($my_asset_types)) {
            foreach ($my_asset_types as $my_asset_type) {
                if ($my_asset_type['cat_id'] == 6) {
                    //gold copper bronze can only sold for XP
                    echo <<<EOT
            <div id="my-wallet-{$my_asset_type['wallet_id']}" class="tab-item asset-types" onclick="transferElement('#my-assets-sell',this,'#my-assets-sell-item');">
            <p>{$my_asset_type['wallet_name']}</p>
            <img src="{$my_asset_type['icon_path']}" alt="">
            <p id="my-wallet-{$my_asset_type['wallet_id']}-quantity">{$my_asset_type['quantity']}</p>
            <input class="form-control" id="tg-sell-quantity-{$my_asset_type['wallet_id']}" type="number" placeholder="Max quantity {$my_asset_type['quantity']}" min=0
                max={$my_asset_type['quantity']}>
            <input class="form-control" id="tg-sell-price-{$my_asset_type['wallet_id']}" type="number" placeholder="Price in XP.." min=0>
            <select class="form-control" id="tg-sell-currency-{$my_asset_type['wallet_id']}">
                <option selected value="2">XPoints</option>
            </select>
            <button class="btn" onclick="confirm_panel('Are you sure do you want to sell it?','market-item',tg_sell_asset,
            {$my_asset_type['wallet_id']},
            {$my_asset_type['quantity']}
            );">sell</button>
            <button class="btn" onclick="tg_donate_validation({$my_asset_type['wallet_id']},{$my_asset_type['quantity']},'{$my_asset_type['wallet_name']}')">Donate</button>
            </div>
            EOT;
                }
                // not witch wallets can only sell FOR witch
                else {
                    echo <<<EOT
            <div id="my-wallet-{$my_asset_type['wallet_id']}" class="tab-item asset-types" onclick="transferElement('#my-assets-sell',this,'#my-assets-sell-item');">
            <p>{$my_asset_type['wallet_name']}</p>
            <img src="{$my_asset_type['icon_path']}" alt="">
            <p id="my-wallet-{$my_asset_type['wallet_id']}-quantity">{$my_asset_type['quantity']}</p>
            <input class="form-control" id="tg-sell-quantity-{$my_asset_type['wallet_id']}" type="number" placeholder="Max quantity {$my_asset_type['quantity']}" min=0
                max={$my_asset_type['quantity']}>
            <input class="form-control" id="tg-sell-price-{$my_asset_type['wallet_id']}" type="number" placeholder="Price.." min=0>
            <select class="form-control" id="tg-sell-currency-{$my_asset_type['wallet_id']}">
                <option selected disabled value="0">Select currency</option>
                <option value="6">Gold</option>
                <option value="7">Copper</option>
                <option value="8">Bronze</option>
            </select>
            <button class="btn" onclick="confirm_panel('Are you sure do you want to sell it?','market-item',tg_sell_asset,
            {$my_asset_type['wallet_id']},
            {$my_asset_type['quantity']}
            );">sell</button>
            <button class="btn" onclick="confirm_panel('Are you sure do you want to use it?','market-item',tg_use_asset,
            {$my_asset_type['wallet_id']},
            {$my_asset_type['cat_id']}
            );">Use</button>
            <button class="btn" onclick="tg_donate_validation({$my_asset_type['wallet_id']},{$my_asset_type['quantity']},'{$my_asset_type['wallet_name']}','{$my_asset_type['icon_path']}');">Donate</button>
            </div>
            EOT;
                }
            }
        } else {
            echo "
        <h3>EMPTY</h3>
        ";
        }


        echo '</div></div>';
    }
}

require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
