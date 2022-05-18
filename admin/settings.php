<?php
require("./admin-header.php");
include("admin-L1-permission.php");



if (isset($_POST['config-elements'])) {
    foreach ($_FILES as $key => $value) {
        $src = uploadFiles($value, 'site-core-images');
        if ($src != "") {
            dbcmd(
                "UPDATE site_elements
                SET 
                    img = '$src'
                WHERE 
                    element = '{$key}'"
            );
        }
    }
    $_SESSION['message']['success'] = "Changes Saved!";
    header("location:settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>xpointsLK - Settings</title>
    <style>
        label img {
            width: 60%;
            max-width: 480px;
        }


        input[type="file"] {
            display: none;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row content">
            <br>
            <div class="col-sm-12">
                <div class="row">
                    <div class="col-sm-6">
                        <form class="well" method="POST" enctype="multipart/form-data" style="text-align:center">
                            <h2 style="border: 3px solid green;text-align:center"><?php require('../assets/php/response.php'); ?></h2>
                            <p>Meaning of "lw1,lw2,.." => Left SideNav Widgets</p>
                            <?php
                            $site_elements = dbget("SELECT * FROM site_elements");
                            foreach ($site_elements as $site_element) {
                                echo <<<EOT
                                <div class='form-group'>
                                <input name="{$site_element['element']}" type="file" id='{$site_element['element']}' accept="image/*" onchange="readURL(this,'pr-{$site_element['element']}');">
                                <label for='{$site_element['element']}' class="btn"><h3><u>{$site_element['element']}<u></h3><img src="{$site_element['img']}" id="pr-{$site_element['element']}"></label>
                                </div>
                                EOT;
                            }
                            ?>

                            <div class="form-group">
                                <button name="config-elements" class="btn btn-primary">Apply</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
</body>
<script src="script.js"></script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>