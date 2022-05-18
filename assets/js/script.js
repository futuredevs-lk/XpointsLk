$(window).on("load", function () {
  $("#tg-loader").fadeOut(500, "swing");
});

var menuNav = document.getElementById("menu-short-info");
var accNav = document.getElementById("acc-short-info");

function openSlider(slider) {
  if (slider == "Acc") {
    menuNav.style.right = "-100%";
    accNav.style.left = 0;
  } else if (slider == "Menu") {
    accNav.style.left = "-100%";
    menuNav.style.right = 0;
  }
}
function closeSlider(slider) {
  if (slider == "Acc") {
    accNav.style.left = "-100%";
  } else if (slider == "Menu") {
    menuNav.style.right = "-100%";
  }
}

function openCatTab(evt, tabId) {
  var i, tabcontent, tablinks;

  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }

  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }

  document.getElementById(tabId).style.display = "block";
  evt.currentTarget.className += " active";
}

document.querySelectorAll(".noty-switch").forEach((element) =>
  element.addEventListener("click", function () {
    openNotyWindow(element);
  })
);

function openNotyWindow(element) {
  closeSlider("Acc");
  closeSlider("Menu");
  document.querySelector(".notification-window").style.display = "block";
  document.getElementById("noty-window").innerHTML =
    element.nextElementSibling.innerHTML;
}

function share(title, text, url) {
  if (url == undefined) {
    url = window.location.href;
  }
  navigator.share({
    title: title,
    text: text,
    url: url,
  });
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

//Facebook SDK

  window.fbAsyncInit = function() {
    FB.init({
      appId      : '319697940243163',
      cookie     : true,
      xfbml      : true,
      version    : 'v13.0'
    });
      
    FB.AppEvents.logPageView();   
      
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "https://connect.facebook.net/en_US/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
   
    
function checkLoginState() {
  FB.getLoginStatus(function(response) {
    console.log(response);
    alert("Coming Soon!");
  });
}  
