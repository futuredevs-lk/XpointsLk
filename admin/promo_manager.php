<?php
require("./admin-header.php");
if (isset($_POST['generate-qr'])) {

    //upload file before store url in db
    $promo_image = uploadFiles($_FILES['img'], "QR_Promos");
    if ($promo_image == "") {
        $_SESSION['message']['error'] = "There was an error while uploading your file.\nTry re-upload a correct file.";
        header("location:./promo_manager.php");
        exit;
    }
    $uniqid = uniqid();

    //store details in db
    dbcmd(
        "INSERT INTO qr_promotions(
            UrlId,
            imageUrl
        )
        VALUES(
            '$uniqid',
            '$promo_image'
        )"
    );
    $_SESSION['message']['success'] = "Success!!";
    header("location:./vendor_manager.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo Manager</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <form class="body-widget" action="" method="POST" enctype="multipart/form-data">
            <?php require('../assets/php/response.php'); ?>
            <h4 class="title">QR Promotions</h4>
            <div class="form-group">
            </div>
            <style>
                .vendor_img {
                    width: 100px;
                }
            </style>
            <div class="form-group">
                <h3>Select AD img: </h3>
                <label for="img" class="vendor_img">
                    <img loading="lazy" src="/assets/images/plus.png" id="img-preview" class="btn">
                </label>
                <input class="form-control" type="file" name="img" id="img" required onchange="readURL(this,'img-preview');" accept="*/img">
            </div>
            <button name="generate-qr" class="btn">Promote</button>
        </form>
        <form action="" method="post">
            <h3>Remove Ads Here</h3>
            <table class="table">
                <tr>
                    <th>Ad Image</th>
                    <th>URL</th>
                </tr>
                <?php
                foreach (dbget("SELECT * FROM qr_promotions") as $promotions) {
                    $promoUrl = "https://xpoints.lk/promo/qr.php/?id={$promotions['UrlId']}";
                    echo "
                    <tr>
                        <td>
                            <a href='{$promotions['ImageUrl']}' target='_blank'>
                                <img loading='lazy'class='vendor_img' src='{$promotions['ImageUrl']}' title='Click here to preview..'>
                            </a>
                        </td>
                        <td>
                            <a href='{$promoUrl}' target='_blank' title='Open in new tab'>{$promoUrl}</a>
                        </td>
                    </tr>";
                }
                ?>
            </table>
        </form>
    </div>
</body>

</html>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>