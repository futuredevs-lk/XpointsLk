<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST["check_timers"])) {
   $user_id = $_SESSION['user']['id'];
   $bottle_timer = dbget("SELECT in_progress,updated_at FROM tg_user_wallets WHERE user_id = $user_id and wallet_id != 1")[0];
   $gift_timer = dbget("SELECT in_process,updated_at FROM tg_user_won WHERE user_id = $user_id")[0];

   $bottle_countdown = dbget("SELECT tg_bottle_timer FROM tg_maintain WHERE level = {$_SESSION['user']['level']}")[0]['tg_bottle_timer'];
   $gift_countdown = dbget("SELECT tg_gift_card_timer FROM tg_maintain WHERE level = {$_SESSION['user']['level']}")[0]['tg_gift_card_timer'];

   if (!empty($bottle_timer)) {
      $bottle_passed_time = time() - strtotime($bottle_timer['updated_at']);
      if ($bottle_timer['in_progress'] == 1 && $bottle_passed_time < $bottle_countdown) {
         echo json_encode(array("runBottle" => true, "countdown" => $bottle_passed_time));
         exit;
      }
   }
   if (!empty($gift_timer)) {
      $gift_passed_time = time() - strtotime($gift_timer['updated_at']);
      if ($gift_timer['in_process'] == 1 && $gift_passed_time < $gift_countdown) {
         echo json_encode(array("runGift" => true, "countdown" => $gift_passed_time));
         exit;
      }
   }
   echo json_encode(array("error" => "hii"));
}


require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
