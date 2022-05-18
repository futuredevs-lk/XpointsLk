//tg_shop purchase
function tg_shop_purchase(
  available_quantity,
  item_id,
  seller_id,
  wallet_id,
  wallet_price,
  currency
) {
  let tg_buy_quantity = $(
    `#market-item-tabitem #tg-buy-quantity-${item_id}`
  ).val();
  if (
    tg_buy_quantity > available_quantity ||
    tg_buy_quantity <= 0 ||
    tg_buy_quantity.trim() == ""
  ) {
    warning_panel("Please Enter a valid quantity");
  } else {
    $("#tg-loader").show();
    //multiply price by quantity
    wallet_price = wallet_price * tg_buy_quantity;
    $.post(
      "./tg_crud/tg_asset_purchase.php",
      {
        purchase_item: item_id,
        price: wallet_price,
        available_in_shop: available_quantity,
        quantity: tg_buy_quantity,
        seller_id: seller_id,
        wallet_id: wallet_id,
        currency: currency,
      },
      function (data) {
        data = $.parseJSON(data);
        $("#tg-loader").hide();
        if (data.error) {
          warning_panel(data.error);
        } else if (data.success) {
          //use wallet_id,asset_quantity[tg_buy_quantity] from js
          //got asset_name,asset_capacity,asset_image,xp_bal,xp_capacity
          //function

          //show congrats panel
          congrats_panel(
            `BOUGHT NEW ASSET<br>'${data.asset_name}' X${tg_buy_quantity}<br><h3>Available ${data.currency_name}: ${data.currency_bal}</h3>`,
            data.asset_image
          );
          //update UI
          updateTgWallets();
        }
      }
    );
  }
}

function tg_sell_asset(wallet_id, available_quantity) {
  let tg_sell_quantity = $(
    `#my-assets-sell-item #tg-sell-quantity-${wallet_id}`
  ).val();
  let tg_sell_price = $(
    `#my-assets-sell-item #tg-sell-price-${wallet_id}`
  ).val();
  let tg_sell_currency = $(
    `#my-assets-sell-item #tg-sell-currency-${wallet_id}`
  ).val();
  let tg_sell_asset_img_path = $(`#my-wallet-${wallet_id} img`).attr("src");

  if (
    tg_sell_quantity > available_quantity ||
    tg_sell_quantity <= 0 ||
    tg_sell_quantity.trim() == "" ||
    tg_sell_price.trim() == "" ||
    tg_sell_currency == "0"
  ) {
    warning_panel("All fields are required and should be validated!!");
  } else {
    $("#tg-loader").show();
    $.post(
      "./tg_crud/tg_asset_sell.php",
      {
        tg_sell_quantity: tg_sell_quantity,
        tg_sell_price: tg_sell_price,
        wallet_id: wallet_id,
        tg_sell_currency: tg_sell_currency,
      },
      function (data) {
        data = $.parseJSON(data);
        $("#tg-loader").hide();
        //function
        if (data.error) {
          warning_panel(data.error);
          return;
        }
        //take wallet_id,asset_quantity[tg_sell_quntity] from js
        //get asset_capacity from server
        //xp wont be affected coz not sold yet
        congrats_panel(
          "Your asset is listed in our TGShop and we'll notify you once your asset is sold..",
          tg_sell_asset_img_path
        );

        updateTgWallets();
        //function
      }
    );
  }
}

function tg_use_asset(wallet_id, cat_id) {
  const tg_asset_usable = [3, 4, 7, 5];
  if (!tg_asset_usable.includes(cat_id)) {
    warning_panel("This asset is not supported for embeding!!");
  } else {
    $("#tg-loader").show();
    $.post(
      "./tg_crud/tg_asset_use.php",
      {
        wallet_id: wallet_id,
        cat_id: cat_id,
      },
      function (data) {
        data = $.parseJSON(data);
        $("#tg-loader").hide();
        fetchUsedAsset();
        if (data.error) {
          warning_panel(data.error);
          return;
        } else if (data.won) {
          tg_timer("gift-card-timer", data.countdown);
          congrats_panel(data.won, data.image);
        } else if (data.success) {
          congrats_panel(data.success, data.image);
        } else if (data.bottle_timer) {
          congrats_panel(data.bottle_timer, data.image);
          tg_timer("bottle-timer", data.countdown, () => {
            document.getElementById(id).innerHTML = "Processing Request";
          });
        }
        fetchMyAssets();
      }
    );
  }
}

function timerUpdateColumn() {
  $.post(
    "./tg_crud/tg_timer_updateColumn.php",
    {
      update: true,
    },
    function (data) {
      console.log(data);
    }
  );
}

function tg_timer(id, countdown, preAction, PostAction) {
  var timer;
  if (timer) {
    clearInterval(timer);
  }
  timer = setInterval(function () {
    countdown -= 1;
    document.getElementById(id).innerHTML = new Date(countdown * 1000)
      .toISOString()
      .substr(11, 8);
    preAction();
    if (countdown < 0) {
      clearInterval(timer);
      PostAction();
    }
  }, 1000);
}
