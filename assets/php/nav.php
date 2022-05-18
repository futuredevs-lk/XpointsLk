<?php echo "<body class='desk-mw {$_SESSION["user"]["theme"]}'></body>";

$site_elements = dbget("SELECT * FROM site_elements");
?>



<!-- header -->
<?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/php/header.php"; ?>



<style>
    img {
        height: auto
    }
</style>
<!-- tg_loader_animation-->
<div class="pos-fx all-0 blurBg" id="tg-loader" style="z-index: 999999;display:none">
    <div class="loader" style="top:35vh">
        <div class="circle"></div>
        <h5 class="title">In Progress..</h5>
    </div>
</div>
<div class="header desk-mw">
    <div class="header-icon">
        <i class="fa fa-user" aria-hidden="true" onclick="openSlider('Acc');"></i>
    </div>
    <a href="/" class="header-logo">
        <img src="<?php echo $site_elements[0]["img"]; ?>" alt="XPOINTS">

    </a>
    <div class="header-icon">
        <i class="fas fa-bars" onclick="openSlider('Menu');"></i>
    </div>
</div>




<div id="acc-short-info" class="sidenav sidenav-acc">
    <div class="sidenav-container scroll scroll-y">
        <div class="sidenav-header">
            <h5 class="sidenav-title">
                Account
            </h5>
            <div class="sidenav-head-icon">
                <i onclick="closeSlider('Acc');" class="fa fa-window-close"></i>
            </div>
        </div>


        <div class="sidenav-profile-img">

            <?php if (isset($_SESSION["user"])) : ?>
                <a href="/profile">
                    <div class="profile-frame" style="width:50px;height:50px;border-radius:50%;overflow:hidden;margin:auto;border:2px outset">
                        <!--<img class="frame-img" src="https://cdn.yourpng.com/uplovendors/preview/christmas-frame-png-transparent-background-hd-11622193656p3exxpmlow.png" alt="photo-frame">-->
                        <img class="profile-img" src="<?php echo $_SESSION["user"]["image"]; ?>" alt="">
                    </div>
                </a>
                <a href="/assets/php/logout.php">
                    <button style="all:unset;width:50%" classs="btn"><img src="<?php echo $site_elements[2]["img"]; ?>"></button>
                </a>
            <?php else : ?>

                <a href="/login/">
                    <img src="<?php echo $site_elements[1]["img"]; ?>" style="width: 60%;margin-top:10%">
                </a>
            <?php endif; ?>

        </div>
        <div class="sidenav-menu-post">
            <div class="post-widget noty-switch">
                <img src="<?php echo $site_elements[3]["img"]; ?>">
            </div>

            <div class="hidden">
                <img src="<?php echo $site_elements[3]["img"]; ?>">
            </div>

            <div class="post-widget noty-switch">
                <img src="<?php echo $site_elements[4]["img"]; ?>">
            </div>

            <div class="hidden">
                <img src="<?php echo $site_elements[4]["img"]; ?>">
            </div>
        </div>
        <div class="sidenav-menu-post">
            <div class="post-widget noty-switch">
                <img src="<?php echo $site_elements[5]["img"]; ?>">
            </div>
            <div class="hidden"><img src="<?php echo $site_elements[5]["img"]; ?>"></div>

            <div class="post-widget noty-switch">
                <img src="<?php echo $site_elements[6]["img"]; ?>">
            </div>
            <div class="hidden">
                <img src="<?php echo $site_elements[6]["img"]; ?>">
            </div>
        </div>
        <!--<div class="pawn-center">
        <div class="pawn-widget noty-switch">something</div>
        <div class="hidden">something</div>
        <div class="pawn-widget noty-switch">Real Shop</div>
        <div class="hidden">Real Shop</div>
      </div>-->
        <div class="shopping-widgets">
            <!--<div class="shoppingB noty-switch">Own Shop</div>
        <div class="hidden">Own Shop</div>-->
            <div class="shoppingS noty-switch">
                <img src="<?php echo $site_elements[7]["img"]; ?>">
            </div>
            <div class="hidden">
                <img src="<?php echo $site_elements[7]["img"]; ?>">
            </div>

        </div>

    </div>
</div>



<div id="menu-short-info" class="sidenav sidenav-menu">
    <div class="sidenav-container scroll scroll-y">
        <div class="sidenav-header">
            <div class="sidenav-head-icon dark-light noty-switch" onclick="fetchNotifications()">
                <i class="far fa-bell"></i>
            </div>


            <!-- notification-->


            <div class="hidden" id="notfications">
                <h2 class="title">Fetching Notification..</h2>
            </div>
            <!-- notification-->



            <h5 class="sidenav-title">Menu</h5>
            <div class="sidenav-head-icon">
                <i onclick="closeSlider('Menu');" class="fa fa-window-close"></i>
            </div>
        </div>


        <a href="/article/">
            <div class="sidenav-widget">
                <img src="<?php echo $site_elements[8]["img"]; ?>" alt="ARTICLES">
            </div>
        </a>
        <a href="/posts/">
            <div class="sidenav-widget">
                <img src="<?php echo $site_elements[9]["img"]; ?>" alt="POSTS">
            </div>
        </a>
        <a href="/shop/">
            <div class="sidenav-widget">
                <img src="<?php echo $site_elements[12]["img"]; ?>" alt="SHOP">
            </div>
        </a>

        <a href="/game">
            <div class="sidenav-widget sidenav-news">
                <img src="<?php echo $site_elements[10]["img"]; ?>" alt="GAME">
            </div>
        </a>
        <style>
            .social-medias a {
                width: 50px;
                height: 50px;

                border-radius: 50%;
                padding: 2%;
                margin: auto;
            }
        </style>
        <div>
            <a href="/about-us.php">
                <h1 class="title">WHO WE ARE?</h1>
            </a>
            <div class="social-medias" style="display: flex;">
                <a href="https://facebook.com/xpointslk"><img src="/assets/images/fb.webp" alt="">
                </a>
                <a href="https://wa.me/94771072424"><img src="/assets/images/whatsapp.png" alt="">
                </a>
                <a href="https://instagram.com"><img src="/assets/images/instagram.png" alt=""></a>
            </div>
        </div>
    </div>
</div>



<!-- welcome-popup -->
<div id="welcome-popup" class="modal fade welcome-popup in" role="dialog" style="display: none; padding-left: 0px;">
    <!--close popup-->
    <i class="fa fa-window-close btn btn-default cls-btn" aria-hidden="true" type="button" data-dismiss="modal"></i>
    <div class="popup-head">
        <h1>Hey there!</h1>
        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, </p>
        <hr>
    </div>

    <div class="popup-body">
        <div class="acc-details">
            <p>New Account</p>
            <div class="acc-benifit">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
            <button class="btn btn-success pop-up-btn">create-acc</button>
        </div>
        <div class="acc-details">
            <p>Volunteer</p>
            <div class="acc-benifit">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. </p>
            </div>
            <button class="btn btn-success pop-up-btn"><a href="/login/login.php">Login</a></button>

        </div>
    </div>
    <div class="popup-rounds-cont">
        <div class="popup-round"></div>
        <div class="popup-round"></div>
        <div class="popup-round"></div>
        <div class="popup-round"></div>
    </div>
</div>



<style>
    .notification-window {
        z-index: 97;
        display: none;
    }

    .noty-content {
        text-align: center;
        height: 100%;
    }

    #noty-window {
        height: 100%;
        margin: auto;
    }

    #noty-window h5 {
        padding-top: 20vh;
    }
</style>
<div class="pos-fx all-0 notification-window blurBg desk-mw">
    <div class="noty-content">
        <button class="btn" onclick="$('.notification-window').hide();" style="position:fixed;right:2%;top:10%;z-index:100">Close</button>

        <div id="noty-window" class="scroll scroll-y">
        </div>
    </div>
</div>


<style>
    .xp-vendors {
        z-index: 97;
        box-shadow: unset;
        border: unset;
    }

    .vendor-left {
        left: 0;
    }

    .vendor-right {
        right: 0;
    }

    .vendor-content,
    .vendor-content a {
        padding: 0;
        overflow: hidden;
    }
</style>
<!-- vendors Container left and right -->
<?php
$vendorsL = dbget(
    "SELECT * FROM vendor_manager WHERE location='left' ORDER BY RAND ( )"
);
$vendorsR = dbget(
    "SELECT * FROM vendor_manager WHERE location='right' ORDER BY RAND ( )"
);
?>
<div class="sidenav xp-vendors vendor-left scroll scroll-y">
    <?php foreach ($vendorsL as $vendor) {
        echo "
              <div class='body-widget vendor-content'>
              <a href='{$vendor["url"]}'>
                  <img src='{$vendor["img"]}'>
              </a>
          </div>
              ";
    } ?>
</div>
<div class="sidenav xp-vendors vendor-right scroll scroll-y">
    <?php foreach ($vendorsR as $vendor) {
        echo "
              <div class='body-widget vendor-content'>
              <a href='{$vendor["url"]}'>
                  <img src='{$vendor["img"]}'>
              </a>
          </div>
              ";
    } ?>
</div>

<?php
$cur_page = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
if ($cur_page != "xpoints.lk/cc/index.php?chat=true") { ?>

    <a title="Chat Here" href="<?php if (!isset($_SESSION["user"])) {
                                    echo "/login/?redir=cc/index.php?chat=true";
                                } else {
                                    echo "/cc/index.php?chat=true";
                                } ?>" style="position:fixed;
        bottom:24px;
        left:24px;
        padding: 10px;
        border-radius: 100%;
        cursor: pointer;
        box-shadow: 0 2px 10px black;
        z-index:100;
        background-color:var(--theme-color)">
        <div>
            <i class="fa fa-comments" aria-hidden="true"></i>
        </div>
    </a>

<?php }
?>



<?php require $_SERVER["DOCUMENT_ROOT"] . "/assets/php/footer.php"; ?>

<script>
    function fetchNotifications() {
        $.post("/assets/php/fetch_notifications.php", {
                fetch: true
            },
            function(data) {
                $("#noty-window").html(data)
            }, "html");
    }

    function notification_seen(element, id) {
        $(element).parent().fadeOut(500, 'swing');
        $.post("/assets/php/notification_seen.php", {
            seen: 1,
            id: id
        }, function(data) {
            console.log(data)
        });
    }

    function clear_notifications() {
        $("#noty-window").html("<h5>All Notfications are cleaned!</h5>");

        $.post("/assets/php/clear_notifications.php", {
            clear_all: true
        }, function(data) {
            console.log(data);
        })
    }
</script>