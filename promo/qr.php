<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if(isset($_GET["id"])){
    $id = $_GET["id"];
    try
    {
        $promoImageUrl = dbget("SELECT * FROM qr_promotions WHERE urlId = '$id'")[0]["ImageUrl"];
    }
    catch(Exception $error) {
        
    }
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpointsLk QR Promotions</title>
    <?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/header.php")
    ?>
</head>
<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
    <div class="pos-fx all-0 notification-window blurBg desk-mw" style="display: block;">
        <div class="noty-content">
            <a href="/" style="position:fixed;right:2%;top:10%;z-index:100;color:white;font-size:20px">
                <button class="btn">
                    <i class="fa fa-home" aria-hidden="true"></i>
                </button>
            </a>

            <div id="noty-window" class="scroll scroll-y" style="position:relative;height:100vh">
                <?php
                echo "<img class='pos-ab all-0' src='{$promoImageUrl}' style='margin:auto'>"
                ?>
            </div>
        </div>
    </div>
</body>
</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>