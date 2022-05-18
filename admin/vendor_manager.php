<?php

require("./admin-header.php");
if (isset($_POST['promote-add'])) {

    //upload file before store url in db
    $vendor_img = uploadFiles($_FILES['img'], "vendors");
    if ($vendor_img == "") {
        $_SESSION['message']['error'] = "There was an error while uploading your file.\nTry re-upload a correct file.";
        header("location:./vendor_manager.php");
        exit;
    }


    //store details in db
    dbcmd(
        "INSERT INTO vendor_manager(
            location,
            img,
            url
        )
        VALUES(
            '{$_POST['location']}',
            '$vendor_img',
            '{$_POST['url']}'
        )"
    );
    $_SESSION['message']['success'] = "Ad has been successfully promoted in XpointsLK website!!";
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
    <title>Ad Manager | XpointsLk</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="body-container">
        <form class="body-widget" action="" method="POST" enctype="multipart/form-data">
            <?php require('../assets/php/response.php'); ?>
            <h4 class="title">Customize Ads</h4>
            <div class="form-group">
                <select class="form-control" name="location" id="" required>
                    <option value="" selected disabled>Choose location</option>
                    <option value="left">Left</option>
                    <option value="right">Right</option>
                </select>
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
            <div class="form-group">
                <input class="form-control" type="text" name="url" id="" placeholder="URL" required>
            </div>
            <button name="promote-add" class="btn">Promote..</button>
        </form>
        <form action="" method="post">
            <h3>Remove Ads Here</h3>
            <table class="table">
                <tr>
                    <th>Ad Image</th>
                    <th>URL</th>
                    <th>Action</th>
                </tr>
                <?php
                foreach (dbget("SELECT * FROM vendor_manager") as $vendor) {
                    echo "
                    <tr>
                        <td>
                            <a href='{$vendor['img']}' target='_blank'>
                                <img loading='lazy'class='vendor_img' src='{$vendor['img']}' title='Click here to preview..'>
                            </a>
                        </td>
                        <td>
                            <a href='{$vendor['url']}' target='_blank' title='Open in new tab'>{$vendor['url']}</a>
                        </td>
                        <td>
                            <button class='btn' onclick='remove_ad(this,{$vendor['id']});' title='Terminate this Ad'>X</button>
                        </td>
                    </tr>";
                }
                ?>
            </table>
        </form>
    </div>
</body>

<script>
    function remove_ad(element, id) {
        $(element).parent().parent().remove();
        $.post("./vendor_remove.php", {
                remove_ad: true,
                id: id
            },
            function(data) {
                console.log(data);

            })
    }
</script>

</html>








<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>