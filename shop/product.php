<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (!isset($_GET['product_id'])) {
    header("location:index.php");
    exit;
} else {
    $product = dbget("SELECT shop_product.*, users.username AS seller_name,users.phone AS seller_contact FROM shop_product JOIN users ON shop_product.seller = users.id WHERE shop_product.id = '" . $_GET['product_id'] . "'")[0];
    if (!isset($product)) {
        echo "not FOUND!";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $product['name']  ?> | For cheap price only at XpointsLk</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="<?php echo $product['name']  ?> - For cheap price at XpointsLk">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk/shop/product.php?product_id=<?php echo $product['id']; ?>>">
    <meta property="og:description" content="<?php echo $product['description']; ?>">
    <meta property="og:image" content="https://xpoints.lk<?php  echo "/assets/uploads/thumbnails/", substr($product['image'], strrpos($product['image'], '/') + 1);  ?> " />
    
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">
</head>

<style>
    html {
        text-align: center;
    }

    .navbar {
        display: flex;
        background-color: #009877;
        align-items: center;
        color: white;
        padding: 2%;
        width: 100%;
        position: sticky;
        position: -webkit-sticky;
        top: 0;

    }

    .navbar i {
        font-size: 2rem;
        color: white;
    }



    .price {
        font-size: 250%;
        font-weight: 800;
        color: #009877;
        text-align: center;
    }

    .posted {
        color: #707676;
        line-height: 1.71428571;

    }


    .call-message {
        text-align: center;
        margin-top: 2%;
    }

    .call-message .btn {
        font-size: 120%;


    }
</style>

<body>
    <?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>

    <div class="body-container">
        <div class="body-widget" style="padding: 0 0 2% 0;overflow:hidden;">

            <div class="navbar">
                <a href="index.php">
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>


            <h1 style="font-size: 1.7rem;" class="product-name"><?php echo $product['name']; ?></h1>
            <p style="font-size: 1rem;font-family:sans-serif" class="posted">Posted On <?php echo "{$product['created_at']} <br>By {$product['seller_name']}" ?></p>

            <img src="<?php echo $product['image']; ?>" alt="0">

            <div class="price">
                RS.<?php echo $product['price'] ?>
            </div>


            <div class="desc scroll scroll-y" style="max-height: 20vh;">
                <p style="font-size: 1.2rem;text-align:left;padding:2%;font-family:sans-serif"><?php echo $product['description']; ?></p>
            </div>


            <div class="call-message">
                <?php $seller_num = $product['seller_contact']; ?>
                <a class="btn" href='https://api.whatsapp.com/send?phone=+94<?php echo $seller_num ?>'>
                    Message
                </a>
                <a class="btn" href='tel:+94<?php echo $seller_num ?>'>Call
                </a>
                <button class="btn" onclick="share('Register XPOINTSLK to get more products..','Hey, Look here.. *<?php echo $product['name']  ?>* available on XpointsLk!!')">
                    share
                </button>
            </div>
            <!-- Go to www.addthis.com/dashboard to customize your tools -->
            <div class="addthis_inline_share_toolbox" style="margin-top:2%"></div>
        </div>


    </div>

    <div class="body-widget">
        <h1 class="title">Releated Products</h1>
        <?php
        $products = dbget("SELECT * FROM shop_product order by rand() limit 5");
        if (!empty($products)) {
            foreach ($products as $product) {
        ?>
                <div class="post-container body-widget">
                    <div class="post-head">
                        <h3 style="font-family:sans-serif"><?php
                            echo "{$product["name"]}"; ?></h3>
                    </div>

                    <a href="/shop/product.php?product_id=<?php
                                                            echo "{$product["id"]}"; ?>">
                        <div class="post-file">
                            <?php
                            echo "<img src='{$product["image"]}'>"; ?>

                        </div>
                        <div class="title" style="margin-top:2%">
                            <button class="btn">View >>></button>
                        </div>
                    </a>

                </div>
        <?php }
        } ?>

    </div>


</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="/assets/js/script.js"></script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>