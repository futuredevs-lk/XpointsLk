<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include($_SERVER["DOCUMENT_ROOT"] . "/cc/app.php"); ?>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XpointsLK | CC</title>
</head>

<body>

</body>
<script>
    var url = new URL(window.location.href);
    if (url.searchParams.get("chat")) {
        console.log('chat-show-btn-clicked');
        $(".chat-show-btn").click();

    }
</script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>