<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");


if (isset($_GET['article_category'])) {
    $article_cats = dbget("SELECT * FROM article_category WHERE cat_id = '" . $_GET['article_category'] . "'limit 1");
} else {
    $article_cats = dbget("SELECT * FROM article_category ORDER BY created_at desc limit 1");
}

if (empty($article_cats)) {
    echo "empty";
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <title>Articles - XPOINTS.LK</title>
    <meta property="og:title" content="Explore Articles - Read on XpointsLk">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk/article">
    <meta property="og:description" content="Read article | XpointsLK">
    <meta property="og:image" content="<?php echo $site_elements[8]['img'] ?>" />
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">



</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"); ?>

    <div class="body-container">

        <div class="tab scroll scroll-x">
            <?php
            echo "<div  class='title active'>{$article_cats[0]['cat_name']}</div>";

            $other_cats = dbget("SELECT * FROM article_category WHERE cat_id != '" . $article_cats[0]['cat_id'] . "' ORDER BY created_at desc");
            if (!empty($other_cats)) {
                foreach ($other_cats as $article_cat) {
                    echo
                    "<a class='title' href='?article_category={$article_cat['cat_id']}'>{$article_cat['cat_name']}</a>";
                }
            }
            ?>
        </div>

        <?php
        $articleMaintainTable = dbget("SELECT * FROM article_maintain JOIN users ON article_maintain.uploaded_by = users.id WHERE cat_id = '" . $article_cats[0]['cat_id'] . "' ORDER BY article_maintain.created_at desc");
        if (!empty($articleMaintainTable)) {
            foreach ($articleMaintainTable as $articleDetails) {
        ?>

                <!-- article-item -->
                <div class="body-widget">
                    <div class="item-content">
                        <div class="item-holder" style="background-image: url('<?php echo $articleDetails['article_bg_image']; ?>');">
                            <div class="item-img">
                                <img loading="lazy" src="<?php echo $articleDetails['article_image']; ?>" alt="">

                            </div>
                        </div>
                        <div class="item-desc">
                            <div class="item-name">
                                <h3><?php echo $articleDetails['article_name']; ?></h3>
                            </div>
                            <div class="doner-name">
                                <p>By <span><?php echo $articleDetails['username']; ?> <href="#"></a></span></p>
                            </div>
                            <div class="view-btn">
                                <a href="./preview.php?article=<?php echo $articleDetails['article_id']; ?>" class="btn">View</a>
                            </div>

                        </div>
                    </div>



                    <!-- <div class="border-icon" id="b-icon-tr">
                <div class="profile-frame">
                    <img class="profile-img" src="https://www.filmibeat.com/ph-big/2021/08/anikha-surendran_16286568946.jpg" alt="">
                </div>
            </div>
            <div class="border-icon" id="b-icon-br">
            </div>
            <div class="border-icon" id="b-icon-bl">
                <a href="<?php /*echo $articleDetails['info_button_link']; ?>"><img src="<?php echo $articleDetails['info_button_image'];*/ ?>" alt=""></a>
            </div>                   -->
                </div>
                <!-- article-item -->
        <?php }
        } ?>
        
        
        <div class="body-widget">
            <h1 class="title">Releated Articles</h1>
    
    
            <?php
            $articleMaintainTable = dbget("SELECT * FROM article_maintain ORDER BY rand() limit 5");
            if (!empty($articleMaintainTable)) {
                foreach ($articleMaintainTable as $articleDetails) {
            ?>
    
                    <!-- article-item -->
                    <div class="body-widget">
                        <div class="item-content">
                            <div class="item-holder" style="background-image: url('<?php echo $articleDetails['article_bg_image']; ?>');">
                                <div class="item-img">
                                    <img src="<?php echo $articleDetails['article_image']; ?>" alt="">
    
                                </div>
                            </div>
                            <div class="item-desc">
                                <div class="item-name">
                                    <h3><?php echo $articleDetails['article_name']; ?></h3>
                                </div>
    
                                <div class="view-btn">
                                    <a href="/article/preview.php?article=<?php echo $articleDetails['article_id']; ?>" class="btn">View</a>
                                </div>
    
                            </div>
                        </div>
                    </div>
                    <!-- article-item -->
            <?php }
            } ?>
            <div class="view-btn" style="text-align:center">
                <a class="btn" href=./ style="width:50%;background-color:royalblue">Load More.</a>
            </div>
        </div>

    </div>





</body>
<script src="/assets/js/script.js"></script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>