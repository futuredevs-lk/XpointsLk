<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

require("./tg_modules/main.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="imagetoolbar" content="no" />
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>PlantFree - XPOINTS.LK</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="Buy on XpointsLk">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk/game>">
    <meta property="og:description" content="Play @ XpointsLK">
    <meta property="og:image" content="https://xpoints.lk/assets/images/xpoints.png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="/assets/css/nav.css">
    <link rel="stylesheet" href="/assets/css/responsive.css?v=1">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="tg_css/tree.css">
    <link rel="stylesheet" href="tg_css/component.css">
    <link rel="stylesheet" href="tg_css/cp.css">

    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"); ?>

</head>

<body>
    <?php include("./tg_modules/components.php");
    ?>
    <style>
        body {
            font-family: unset;
            font-family: my-font;
        }
    </style>
    <div class="body-container desk-flex">
        <?php
        include("./tg_modules/tree.php");
        include("./tg_modules/control_panel.php")
        ?>
    </div>


    <!-- game bg audio -->
    <audio id="game-audio" loop style="display:none">
        <source src="<?php echo $bg_music ?>" type="audio/mp3">
    </audio>
    <!-- game click sound -->
    <audio id="click-sound-water" style="display:none">
        <source src="<?php echo $water_tap_sound ?>" type="audio/mp3">
    </audio>



</body>




<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

<!-- changing order may leads to errors -->
<script src="tg_script/water-func.js?v=<?php echo time() ?>"></script>
<script src="tg_script/ui.js?v=<?php echo time() ?>"></script>
<script src="tg_script/asset-func.js?v=<?php echo time() ?>"></script>
<script src="tg_script/donate.js?v=<?php echo time() ?>"></script>
<script src="tg_script/fetch.js?v=<?php echo time() ?>"></script>
<script src="tg_script/task.js?v=<?php echo time() ?>"></script>



<script>
    $(document).ready(function() {
        document.getElementById("click-it-1").click();
        document.getElementById("click-it-2").click();
    });


    tg_bgm = document.getElementById('game-audio');

    function triggerBgm() {
        if (tg_bgm.paused) tg_bgm.play();
        else tg_bgm.pause();
    }
    //reset game
    function clear_data() {
        $("#tg-loader").show();
        $.post(
            "./tg_crud/clear_data.php", {
                clear_data: true,
            },
            function(data) {
                location.reload();
                console.log(data)
            }
        );
    }
</script>

</html>
<?php

include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
?>