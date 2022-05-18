var water_level = parseInt($("#water-progress-bar").attr("quantity"));

var tg_w_water_capacity = parseInt($("#water-progress-bar").attr("capacity"));

var water_in_progress = parseInt($("#water-progress-bar").attr("in_progress"));
var decrease_water_running = false;

if (water_in_progress == 0) {
  console.log("Waiting for tap on water..");
}

if (water_in_progress == 1) {
  decreaseWater();
}

function pumpWater() {
  if (water_level >= tg_w_water_capacity || decrease_water_running) {
    document.getElementById("water-full").style.display = "block";
    if (!decrease_water_running) {
      decreaseWater();
    }
  } else if (!decrease_water_running) {
    document.getElementById("click-sound-water").play();
    water_level_percentage = (water_level / tg_w_water_capacity) * 100;
    document.getElementById("water-progress-bar").style.width =
      water_level_percentage + "%";
    water_level += 1;
    $("#tg-pr-wt-val").text(`${water_level} / ${tg_w_water_capacity}`);
    console.log("tap " + water_level);

    $.post("./tg_crud/tg_increase_water_level.php", {
      increase_water: true,
    });
  }
}

function decreaseWater() {
  decrease_water_running = true;
  var water_time_left;
  //get how many sec more
  $.post(
    "./tg_crud/tg_fetch_water_info.php",
    {
      get_time: true,
    },
    function (response) {
      response = $.parseJSON(response);
      water_time_left = response.water_time_left;
      water_level = response.water_level;
      var deduct_water = water_level / water_time_left;

      console.log("current_level: " + water_level);
      console.log("decrease_within: " + water_time_left);
      console.log("deduct by " + deduct_water + " every 1 sec");

      if (deduct_water > 0 && deduct_water != Infinity) {
        var decreasewaterinterval = setInterval(function () {
          if (water_level < 0) {
            generateXP();
            clearInterval(decreasewaterinterval);
          } else {
            water_level_percentage = (water_level / tg_w_water_capacity) * 100;
            document.getElementById("water-progress-bar").style.width =
              water_level_percentage + "%";
            $("#tg-pr-wt-val").text(
              `${parseInt(water_level)} / ${tg_w_water_capacity}`
            );
            water_level -= deduct_water;
            console.log("decreasing: " + water_level);
            $.post("tg_crud/tg_decrease_water_level.php", {
              decrease_water: deduct_water,
            });
          }
        }, 1000);
      } else {
        $.post("tg_crud/tg_decrease_water_level.php", {
          decrease_water: deduct_water,
        });
        reloadBrowser();
      }
    }
  );
}
function reloadBrowser() {
  location.reload();
}
function generateXP() {
  //add to xp_wallet, get current balance..
  $.post(
    "./tg_crud/tg_generate_xp.php",
    {
      generate_xp: true,
    },
    function (data) {
      data = $.parseJSON(data);
      if (data.error) {
        warning_panel(data.error);
      } else {
        congrats_panel(data.success, data.image);
        fetchXpBal();
      }

      //reseting and play again
      decrease_water_running = false;
      water_level = 0;
    }
  );
}
