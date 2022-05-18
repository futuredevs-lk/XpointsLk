<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");

  $tg_used_gem = dbget(
    "SELECT 
        icon_path
    FROM
        tg_user_wallet_use
    JOIN
      tg_wallets_levels
    ON
      tg_user_wallet_use.gem_id = 
      tg_wallets_levels.wallet_id 
    WHERE 
      tg_user_wallet_use.user_id ={$_SESSION['user']['id']}"
  );

  $tg_used_bottle = dbget(
    "SELECT 
        icon_path
    FROM
        tg_user_wallet_use
    JOIN
      tg_wallets_levels
    ON
      tg_user_wallet_use.bottle_id = 
      tg_wallets_levels.wallet_id 
    WHERE 
      tg_user_wallet_use.user_id ={$_SESSION['user']['id']}"
  );

  $tg_used_potion = dbget(
    "SELECT 
        icon_path
    FROM
        tg_user_wallet_use
    JOIN
      tg_wallets_levels
    ON
      tg_user_wallet_use.potion_id = 
      tg_wallets_levels.wallet_id 
    WHERE 
      tg_user_wallet_use.user_id ={$_SESSION['user']['id']}"
  );


  $tg_used_wish_t = dbget(
    "SELECT 
        icon_path,wallet_name
    FROM
        tg_user_wallet_use
    JOIN
      tg_wallets_levels
    ON
      tg_user_wallet_use.wish_t_id = 
      tg_wallets_levels.wallet_id 
    WHERE 
      tg_user_wallet_use.user_id ={$_SESSION['user']['id']}"
  );

  if(!isset($tg_used_gem[0]['icon_path'])){
    $tg_used_gem[0]['icon_path']="/assets/images/plus.png";
  }

  if(!isset($tg_used_bottle[0]['icon_path'])){
    $tg_used_bottle[0]['icon_path']="/assets/images/plus.png";
  }

  if(!isset($tg_used_potion[0]['icon_path'])){
    $tg_used_potion[0]['icon_path']="/assets/images/plus.png";
  }

  if(!isset($tg_used_wish_t[0]['icon_path'])){
    $tg_used_wish_t[0]['icon_path']="/assets/images/plus.png";
  }
  
  echo json_encode(array(
          "gem" => $tg_used_gem[0]['icon_path'],
          "bottle" => $tg_used_bottle[0]['icon_path'],
          "potion" => $tg_used_potion[0]['icon_path'],
          "wish" => $tg_used_wish_t[0]['icon_path']
  ));

require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>