<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

if(isset($_POST['fetch_item'])){
    $user_level = $_SESSION['user']['level'];
    $tg_wallets_cat =dbget("SELECT * FROM tg_wallets_cat  WHERE level=$user_level and id NOT IN (1,2)");

    //cats
    echo '<div class="sub-cats scroll scroll-y">';
        foreach($tg_wallets_cat as $tg_wallet_sc){
        echo <<<EOT
        <div class="sc-name btn sub-tablinks" onclick="openSubCat(event,'sc-{$tg_wallet_sc['id']}')">
            <img src='{$tg_wallet_sc['icon_path']}'>
        </div>
        EOT;
    }
    echo '</div>';

    foreach($tg_wallets_cat as $tg_wallet_sc){
        echo <<<EOT
        <div id="sc-{$tg_wallet_sc['id']}" class="sub-tab-content scroll scroll-y">
        EOT;
        
        $tg_sc_wallets =dbget(
        "SELECT 
            tg_wallets_levels.wallet_name AS wallet_name,
            tg_wallets_levels.wallet_id AS wallet_id, 
            tg_wallets_levels.icon_path AS icon_path,
            tg_shop.price AS wallet_price,
            tg_shop.quantity,
            tg_shop.id AS shop_item_id,
            users.username AS seller_name,
            users.id AS seller_id,
            tg_shop.currency_id as currency
          FROM 
            tg_shop 
          JOIN 
            tg_wallets_levels 
          ON 
            tg_shop.wallet_id = tg_wallets_levels.wallet_id
          JOIN
            users
          ON
            tg_shop.seller_id = users.id 
          WHERE 
            tg_wallets_levels.cat_id=".$tg_wallet_sc['id']." and
            tg_wallets_levels.level=$user_level and
            tg_shop.quantity!=0
          ORDER BY
            tg_shop.created_at desc
        ");
        if(!empty($tg_sc_wallets)){
        foreach($tg_sc_wallets as $tg_sc_wallet){
        if($tg_sc_wallet['currency']==6){
          $tg_shop_currency = "Gold";
        }
        elseif($tg_sc_wallet['currency']==7){
          $tg_shop_currency = "Copper";
        }
        elseif($tg_sc_wallet['currency']==8){
          $tg_shop_currency = "Bronze";
        }
        elseif($tg_sc_wallet['currency']==2){
          $tg_shop_currency = "XP";
        }
        
        echo <<<EOT
        <div class="tab-item" onclick="transferElement('#market-item',this,'#market-item-tabitem');">
        <p>{$tg_sc_wallet['wallet_name']}</p>
        <img src="{$tg_sc_wallet['icon_path']}" alt="">
        <p>Price : {$tg_sc_wallet['wallet_price']} {$tg_shop_currency}</p>
        <p>SOLD BY : {$tg_sc_wallet['seller_name']}</p>  
        <input class="form-control" id="tg-buy-quantity-{$tg_sc_wallet['shop_item_id']}" type="number" placeholder="Max quantity {$tg_sc_wallet['quantity']}" min=0
        max={$tg_sc_wallet['quantity']}>
        <button class="btn" onclick="confirm_panel('Are you sure do you want to purchase it?','market-item',tg_shop_purchase,
        {$tg_sc_wallet['quantity']},
        {$tg_sc_wallet['shop_item_id']},{$tg_sc_wallet['seller_id']},
        {$tg_sc_wallet['wallet_id']},
        {$tg_sc_wallet['wallet_price']},
        {$tg_sc_wallet['currency']});">Purchase Now</button>
        </div>
        EOT;
        }};
        echo "</div>";
        }
}



require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");
