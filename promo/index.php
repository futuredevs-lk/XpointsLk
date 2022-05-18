<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    if (isset($_GET['ad'])) {
        $get_ads = dbget("SELECT img,url FROM vendor_manager WHERE id = {$_GET['ad']}")[0];
        if (empty($get_ads)) {
            header("location:/pages/404.html");
            exit();
        }
    } else {
        header("location:/");
        exit();
    }
    require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/header.php")
    ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpointsLk | Promo</title>
</head>

<body>
    <div class="pos-fx all-0 notification-window blurBg desk-mw" style="display: block;">
        <div class="noty-content">
            <a href="/" style="position:fixed;right:2%;top:10%;z-index:100;color:white;font-size:20px">
                <button class="btn">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </button>
            </a>

            <div id="noty-window" class="scroll scroll-y" style="position:relative;height:100vh">
                <?php
                echo "<a href='{$get_ads['url']}'>
                    <img class='pos-ab all-0' src='{$get_ads['img']}' style='margin:auto'>
                  </a>"
                ?>
            </div>
        </div>
    </div>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

</html>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>