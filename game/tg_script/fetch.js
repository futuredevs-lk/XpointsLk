function fetch_tg_pr() {
  $.post(
    "./tg_crud/tg_fetch_asset_percentage.php",
    {
      tg_pr: true,
    },
    function (data) {
      data = $.parseJSON(data);
      //   $(`#${data}-progress-bar`).attr("width", data);
      console.log(data);
    }
  );
}

function fetchMyAssets() {
  $.post(
    "./tg_crud/tg_fetch_my_assets.php",
    {
      fetch_my_assets: true,
    },
    function (data) {
      document.getElementById("fetched-my-assets").innerHTML = data;
    },
    "html"
  );
}

function fetchXpBal() {
  fetch_tg_pr("xp");
  $.post(
    "./tg_crud/tg_fetch_xp_bal.php",
    {
      fetch_xp: true,
    },
    function (data) {
      $("#xp-wallet-bal").text(`Available XP: ${data}`);
    }
  );
}

function fetchShopItem() {
  $.post(
    "./tg_crud/tg_fetch_shop_item.php",
    {
      fetch_item: true,
    },
    function (data) {
      document.getElementById("main-tab-2").innerHTML = data;
    },
    "html"
  );
}

function fetchUsedAsset() {
  $.post(
    "./tg_crud/tg_fetch_used_asset.php",
    {
      fetch_used_asset: true,
    },
    function (data) {
      try {
      } catch {}

      data = $.parseJSON(data);
      $("#gemstone").attr("src", data.gem);
      $("#bottle-img").attr("src", data.bottle);
      $("#tg-request-potion").attr("src", data.potion);
      $("#tg-request-wish-t").attr("src", data.wish);
    }
  );
}
function checkTimers() {
  $.post(
    "./tg_crud/tg_timer_check.php",
    {
      check_timers: true,
    },
    function (data) {
      data = $.parseJSON(data);
      if (data.runGift) tg_timer("gift-card-timer", data.countdown);
      if (data.runBottle) tg_timer("bottle-timer", data.countdown);
    }
  );
}

function fetchSoldItems() {
  //let's check sold items
  $.post(
    "./tg_crud/tg_fetch_my_shop_sold.php",
    {
      get: true,
    },
    function (data) {
      data = $.parseJSON(data);
      if (!data.empty) {
        congrats_panel(
          `Your asset '${data[0]["wallet_name"]}' x ${data[0]["quantity"]} has sold to '${data[0]["buyer_name"]}' for ${data[0]["price"]} coin_type#${data[0]["currency_id"]}`,
          data[0]["icon_path"]
        );
      }
    }
  );
}

function fetchTasks() {
  $.post(
    "./tg_crud/task-fetch.php",
    {
      fetch_tasks: true,
    },
    function (data) {
      $("#task-panel").html(data);
    }
  );
}

function updateTgWallets() {
  fetchShopItem();
  fetchMyAssets();
  fetchXpBal();
  fetchUsedAsset();
}

//call fetch functions
updateTgWallets();
fetchSoldItems();
fetchTasks();
checkTimers();
