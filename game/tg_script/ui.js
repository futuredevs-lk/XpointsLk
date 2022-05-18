function openCat(evt, tabId) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("main-tab-content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabId).style.display = "flex";
  evt.currentTarget.className += " active";
}

function openSubCat(evt, tabId) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("sub-tab-content");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("sub-tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabId).style.display = "block";
  evt.currentTarget.className += " active";
}

$(".slider").slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: 1,
  dots: true,
  slide: ".slide-item",
  infinite: true,
  autoplay: true,
});

function confirm_panel(
  text,
  hide,
  action,
  param1,
  param2,
  param3,
  param4,
  param5,
  param6
) {
  $(".confirm-alert").show();
  $(".confirm-alert .confirm-text").text(text);
  $(".confirm-alert .confirm-response .confirm-true")
    .unbind()
    .click(function () {
      $(".confirm-alert").hide();
      $("#" + hide).hide();
      action(param1, param2, param3, param4, param5, param6);
      // close-windows
      $(".game-notification").hide();
    });
  $(".confirm-alert .confirm-response .confirm-false").click(function () {
    $(".confirm-alert").hide();
  });
}
function congrats_panel(html, image) {
  $("#congrats-window #to-congrats h5").html(html);
  $("#congrats-window #to-congrats img").attr("src", image);
  $("#congrats-window").show();
  // close-windows
  $(".game-notification").hide();
}

function warning_panel(text) {
  $(".warning-alert .warning-text").text(text);
  $(".warning-alert").show();
}

function transferElement(show, from, to) {
  $(show).toggle();
  $(to).html(from.innerHTML);
}
