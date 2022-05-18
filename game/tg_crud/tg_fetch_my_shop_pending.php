<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
$user_id = $_SESSION['user']['id'];


$tg_pending_items = dbget(
    "SELECT
        tg_wallets_levels.icon_path,
        tg_wallets_levels.wallet_name
    FROM
        tg_shop
    JOIN
        tg_wallets_levels
    ON
        tg_shop.wallet_id = 
        tg_wallets_levels.wallet_id
    WHERE 
        seller_id = $user_id"
);

  foreach($tg_pending_items as $tg_pending_item){
    echo <<<EOT
    <div class="tab-item">
      <p>{$tg_pending_item['wallet_name']}</p>
      <img src="{$tg_pending_item['icon_path']}" alt="">
      <p>PENDING</p>
    </div>
    EOT;
  }

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>