<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (!isset($_SESSION['user'])) {
    header("location:/login?redir=admin");
    exit;
}
if ($_SESSION['user']['admin'] == 0) {
    header("location:/pages/not-an-admin.html");
    exit;
}
echo "<body class='desk-mw {$_SESSION["user"]["theme"]}'></body>";

?>
<style>
    body {
        all: unset;
        font-family:sans;
    }
    .body-container{
        max-width:540px;
        
    }
</style>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="script.js"></script>
<link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">

<nav class="navbar navbar-inverse">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">XPOINTS.LK</a>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav">
                <li><a href="./">Admin Home</a></li>
                <li><a href="/profile/">Profile: <?php echo $_SESSION['user']['username'] ?></a></li>
                <li><a href="./settings.php">XP Settings</a></li>
                <li><a href="./vendor_manager.php">Ad Manager</a></li>
                <li><a href="./article-maintain.php">Customize Articles</a></li>
                <li><a href="./post-maintain.php">Customize Posts</a></li>
                <li><a href="./shop-maintain.php">Shop Maintain</a></li>
                <li><a href="./game-maintain.php">TG Maintain</a></li>
                <li><a href="./tg_settings_preview.php">TG Preview</a></li>
                <li><a href="./reward_user.php">TG Reward Center</a></li>
                <li><a href="./tg_manage_donation.php">TG Manage Donations</a></li>
                <li><a href="./config_tasks.php">TG Task configuration</a></li>
                <li><a href="./send_notification.php">Send Notification</a></li>
                <li><a href="./promo_manager.php">Promo Manager</a></li>
                <li><a href="/assets/php/logout.php">Log out.</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-KE4SQ5NQX8"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', 'G-KE4SQ5NQX8');
</script>