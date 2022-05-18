<?php
require $_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php"; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPOINTS.LK | Home Page</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk">
    <meta property="og:description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:image" content="https://xpoints.lk/assets/images/xpoints.png" />
    <link rel="stylesheet" href="/article/style.css">
    <link rel="stylesheet" href="/posts/style.css">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">

    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9104032822081017" crossorigin="anonymous"></script>

</head>

<body>

    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"; ?>


    <div class="body-container">
        <div style="padding: 0;position:relative">
            <img src="<?php
                        echo $site_elements[13]['img'];
                        ?>" alt="">
            <div class="title pos-ab" style="padding-top: 25%;">
                <h1 style="color:white;background-color: var(--sidenav-bg-color)">An Educational Website with PlantFree Game and Live Chat feature!!</h1>
                <a href="/register">
                    <button class="btn" style="font-size:120%">Get Started</button>
                </a>
                
            </div>
        </div>
    </div>
    <a href="/game">
        <div style="padding: 0;position:relative">
            <img src="<?php
                        $tg_main_tree = dbget(
                            "SELECT image_path FROM tg_main_elements WHERE level=1"
                        )[0];
                        echo $tg_main_tree["image_path"];
                        ?>" alt="">
            <div class="pos-ab title">
                <h1 style="color:white;background-color: var(--sidenav-bg-color)">Let's pour some water to your Tree..</h1>
                <button class="btn">Play</button>
            </div>
        </div>
    </a>

    <div class="body-widget">
        <h1 class="title">XpointsLk Articles</h1>


        <?php
        $articleMaintainTable = dbget(
            "SELECT * FROM article_maintain ORDER BY rand() limit 3"
        );
        if (!empty($articleMaintainTable)) {
            foreach ($articleMaintainTable as $articleDetails) { ?>

                <!-- article-item -->
                <div class="body-widget">
                    <div class="item-content">
                        <div class="item-holder" style="background-image: url('<?php echo $articleDetails["article_bg_image"]; ?>');">
                            <div class="item-img">
                                <img src="<?php echo $articleDetails["article_image"]; ?>" alt="">

                            </div>
                        </div>
                        <div class="item-desc">
                            <div class="item-name">
                                <h3><?php echo $articleDetails["article_name"]; ?></h3>
                            </div>

                            <div class="view-btn">
                                <a href="/article/preview.php?article=<?php echo $articleDetails["article_id"]; ?>" class="btn">View</a>
                            </div>

                        </div>
                    </div>
                </div>
                <!-- article-item -->
        <?php }
        }
        ?>
        <div class="title">
            <a href="/article/">
                <button class="btn">Read More</button>
            </a>
        </div>
    </div>



    <div class="body-widget">
        <h1 class="title">
            XpointsLK Posts
        </h1>


        <?php
        $posts = dbget("SELECT * FROM post_maintain ORDER BY rand() limit 2");
        if (!empty($posts)) {
            foreach ($posts as $post) { ?>
                <div class="post-container body-widget">
                    <div class="post-head">
                        <div class="uploader-name">
                            <?php echo $post["name"],
                            "|",
                            $post["description"]; ?>
                        </div>
                    </div>





                    <a href='/posts/?post_id=<?php echo $post["id"]; ?>'>
                        <div class="post-file">

                            <?php
                            $file_format = getfileformat($post["file"]);
                            if (in_array($file_format, $image_formats)) {
                                echo "<img style='object-fit:contain' src='{$post["file"]}'>";
                            } elseif ($file_format == "pdf") {
                                $pdf_url =
                                    "http://docs.google.com/gview?embedded=true&url=https://xpoints.lk" .
                                    $post["file"];
                                echo "<embed src='$pdf_url' width='100%' height='100%'>";
                            } elseif (in_array($file_format, $video_formats)) {
                                echo "<video src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%'></video>";
                            } elseif (in_array($file_format, $audio_formats)) {
                                echo "<div class='title'><audio src='{$post["file"]}' controls type='audio/$fileformat' width='100%' height='100%'></audio></div>";
                            } else {
                                echo "<video src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%'></video>";
                            }
                            ?>
                        </div>
                        <button class="btn">View >>></button>

                    </a>

                </div>
        <?php }
        }
        ?>
        <div class="title">
            <a href="/posts/"><button class="btn">Laugh More</button></a>
        </div>
    </div>

    <link rel="stylesheet" href="/shop/style.css">
    <div class="body-widget">
        <h1 class="title">XpointsLK Shop</h1>
        <?php
        $products = dbget("SELECT * FROM shop_product order by rand() limit 2");
        if (!empty($products)) {
            foreach ($products as $product) { ?>
                <div class="post-container body-widget">
                    <div class="post-head">
                        <h3><?php echo "{$product["name"]}"; ?></h3>
                    </div>

                    <a href="/shop/product.php?product_id=<?php echo "{$product["id"]}"; ?>">
                        <div class="post-file" style="height: unset;">
                            <?php echo "<img src='{$product["image"]}'>"; ?>

                        </div>
                        <button class="btn">View >>></button>
                    </a>

                </div>
        <?php }
        }
        ?>
        <div class="title">
            <a href="/shop">
                <button class="btn title">Buy More>></button>
            </a>
        </div>
    </div>

    <div style="background-color: royalblue;padding: 10%;">
        <h1>Find Us Here</h1>
        <div class="social-medias body-widget" style="display: flex;" }>
            <a href="https://facebook.com/xpointslk"><img src="/assets/images/fb.webp" alt="">
            </a>
            <a href="https://wa.me/94771072424"><img src="/assets/images/whatsapp.png" alt="">
            </a>
            <a href="mailto:xpointsofficial@gmail.com"><img src="/assets/images/gmail.png" alt=""></a>
        </div>
    </div>
    <div class="body-widget">
        <h2 class="title">Our Sponsors</h2>
        <?php foreach (array_merge($vendorsL, $vendorsR) as $vendor) {
            echo "
              <div class='body-widget' style='padding:0'>
              <a href='{$vendor["url"]}'>
                  <img src='{$vendor["img"]}'>
              </a>
          </div>
              ";
        } ?>
    </div>
    <div style="
        background-color:royalblue;
        padding:10%;
        font-family:sans-serif">
        <b><a href="/" style="font-size:140%;font-family: my-font;">XpointsLk</a></b>
        <p>An Educational website with many key features.</p>
        <a href="/about-us.php" class"btn">
            Learn more about us.
        </a>
        <ul style="background-color:darkslategrey">
            <li>Tel : <a href:"tel:077777777">+94-(0)77898999999</a></li>
            <li>Mail : <a href="mailto:xpointsofficial@gmail.com">xpointsofficial@gmail.com</a></li>
        </ul>
        <a href="#top">Go Top</a>
    </div>
</body>


</html>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"; ?>