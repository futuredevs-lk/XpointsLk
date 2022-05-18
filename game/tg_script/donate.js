function tg_donate_validation(wallet_id, available_quantity, wallet_name, img) {
  let donate_quantity = $(
    `#my-assets-sell-item #tg-sell-quantity-${wallet_id}`
  ).val();
  if (
    donate_quantity > available_quantity ||
    donate_quantity <= 0 ||
    donate_quantity.trim() == ""
  ) {
    warning_panel(
      "Invalid Quantity!"
    );
  } else {
    $("#donate-window").show();
    $("#donate-details").html(
      `<h1 wallet_id="${wallet_id}" amount="${donate_quantity}">Assets Name: ${wallet_name}<br>Amount: ${donate_quantity}</h1><img src='${img}'>`
    );
  }
}

function tg_send_donation() {
  let tg_donate_receiver = $("#donate-player").val();
  let tg_donate_amount = $("#donate-details h1").attr("amount");
  let wallet_id = $("#donate-details h1").attr("wallet_id");
  let wallet_img = $("#donate-details img").attr("src");
  console.log(tg_donate_receiver);
  if (tg_donate_receiver == null) {
    warning_panel("Please select a user!");
  } else {
    $("#tg-loader").show();
    $.post(
      "./tg_crud/tg_asset_donate.php",
      {
        receiver: tg_donate_receiver,
        amount: tg_donate_amount,
        wallet_id: wallet_id,
      },
      function (data) {
        console.log(data);
        fetchMyAssets();
        congrats_panel(
          "Your request has been sent. Pending for the manual check by admin..",
          wallet_img
        );
        $("#donate-window").hide();
        $("#tg-loader").hide();
      }
    );
  }
}
