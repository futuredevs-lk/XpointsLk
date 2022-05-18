<?php
//login check
if (!isset($_SESSION['user'])) {
    header("location:/login?redir=game");
    exit;
}
$user_id    = $_SESSION['user']['id'];
$_SESSION['user']['level'] = dbget("SELECT level FROM users WHERE id = $user_id")[0]['level'];
$user_level = $_SESSION['user']['level'];
//check user play TG for the first time..
if ($user_level == 0) {
    //showingStory-slides
    $tg_first_time = true;
    //create new wallets

    //set user level 1 in users table
    dbcmd("UPDATE users SET level=1 WHERE id=$user_id");
    $_SESSION['user']['level'] = 1;
    $user_level = 1;
}


//sync tg_wallets with tg_user_wallets
dbcmd("INSERT IGNORE INTO tg_user_wallets 
  (
    wallet_id,
    user_id
  ) 
  SELECT 
      wallet_id,
      $user_id
    FROM 
      tg_wallets_levels
    WHERE 
      level = $user_level
");


// get images of tg-elements
$tg_elements  = dbget("SELECT image_path FROM tg_main_elements WHERE level=$user_level");
$main_tree_img  = $tg_elements[0]['image_path'];
$beehive_img    = $tg_elements[1]['image_path'];
$xp_bag_img     = $tg_elements[2]['image_path'];
$gift_box_img   = $tg_elements[3]['image_path'];
$cp_market_img  = $tg_elements[4]['image_path'];
$cp_gift_img    = $tg_elements[5]['image_path'];
$cp_offers_img  = $tg_elements[6]['image_path'];
$cp_assets_img  = $tg_elements[7]['image_path'];
$cp_my_shop_img  = $tg_elements[8]['image_path'];
$cp_ranking_img  = $tg_elements[9]['image_path'];
$cp_tasks_img  = $tg_elements[13]['image_path'];
$cp_settings_img  = $tg_elements[10]['image_path'];

//gaame audios
$bg_music = $tg_elements[11]['image_path'];;
$water_tap_sound = $tg_elements[12]['image_path'];

// ********************************************

// get wallets
$tg_wallets     = dbget("SELECT * FROM tg_wallets_levels WHERE level =$user_level ORDER BY wallet_id");
$tg_w_img_water     = $tg_wallets[0]['icon_path'];
$tg_w_img_xpoints   = $tg_wallets[1]['icon_path'];
$tg_w_img_gold      = $tg_wallets[2]['icon_path'];
$tg_w_img_copper    = $tg_wallets[3]['icon_path'];
$tg_w_img_bronze    = $tg_wallets[4]['icon_path'];
$tg_w_img_power_up  = $tg_wallets[5]['icon_path'];

// *********************************************

// get user wallet
$tg_user_wallets = dbget("SELECT * FROM tg_user_wallets WHERE user_id=$user_id");

$tg_uw_water    = $tg_user_wallets[0];
$tg_uw_xpoints  = $tg_user_wallets[1];
$tg_uw_gold     = $tg_user_wallets[2];
$tg_uw_copper   = $tg_user_wallets[3];
$tg_uw_bronze   = $tg_user_wallets[4];


// get max-capacity of wallets and calculate quantity of wallets by percentage
function calProgressPercentage($wallet)
{
    global $tg_user_wallets, $tg_wallets;
    return $tg_user_wallets[$wallet]['quantity'] / $tg_wallets[$wallet]['capacity'] * 100;
}
$tg_pr_water   = calProgressPercentage(0);
$tg_pr_xpoints = calProgressPercentage(1);
$tg_pr_gold    = calProgressPercentage(2);
$tg_pr_copper  = calProgressPercentage(3);
$tg_pr_bronze  = calProgressPercentage(4);


//tg_wallets_cat there shold not be water and xpoints
$tg_wallets_cat = dbget("SELECT * FROM tg_wallets_cat  WHERE level=$user_level and id NOT IN (1,2)");
