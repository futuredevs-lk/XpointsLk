function openAdd(element, name) {
  var select = element.parentElement;
  var addThing = select.nextElementSibling;
  addThing.style.display = "flex";
  select.style.display = "none";
  select.firstElementChild.setAttribute("name", "");
  addThing.firstElementChild.setAttribute("name", name);
}
function cancelAdd(element, name) {
  var addThing = element.parentElement;
  var select = addThing.previousElementSibling;
  select.style.display = "flex";
  addThing.style.display = "none";
  select.firstElementChild.setAttribute("name", name);
  addThing.firstElementChild.setAttribute("name", "");
}

function approveOrDecline(id, cmd) {
  $(`#ptba-${id}`).remove();
  $.post(
    "./ajax-response.php",
    {
      approveOrDecline: true,
      cmd: cmd,
      id: id,
    },
    function (data) {
      console.log(data);
    }
  );
}

// shop
function approveOrDeclineProduct(id, cmd) {
  $(`#ptba-${id}`).remove();
  $.post(
    "./ajax-response.php",
    {
      shop_approve_or_decline: true,
      cmd: cmd,
      id: id,
    },
    function (data) {
      console.log(data);
    }
  );
}

function wtApproved(element, request_id, user_id, approve) {
  $(element).parent().remove();
  $.post(
    "./tg_user_wish_approved.php",
    {
      request_id: request_id,
      user_id: user_id,
      approve: approve,
    },
    function (data) {
      console.log(data);
    }
  );
}

function make_me_rich() {
  $.post(
    "./tg_make_me_rich.php",
    {
      make_me_rich: true,
    },
    function (data) {
      alert(data);
    }
  );
}

function readURL(input, img) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function (e) {
      $("#" + img).attr("src", e.target.result);
    };

    reader.readAsDataURL(input.files[0]);
  }
}
