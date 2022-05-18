<?php
$getArticleDetails = true;
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (!isset($_GET['article'])) {
    header("location:index.php");
    exit;
} else {
    $article = dbget("SELECT * FROM article_maintain JOIN users ON article_maintain.uploaded_by = users.id WHERE article_maintain.article_id = '" . $_GET['article'] . "'")[0];
    if (!isset($article)) {
        header("location:/pages/404.html");
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
    <title><?php echo $article['article_name']  ?> - XPOINTS.LK</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="<?php echo $article['article_name']  ?> - Read on XpointsLk">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk/article/preview.php?article=<?php echo $article['article_id']  ?>">
    <meta property="og:description" content="Read article '<?php echo $article['article_name'] ?>' | XpointsLK">
    
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">
    <meta property="og:image" content="https://xpoints.lk<?php  echo "/assets/uploads/thumbnails/", substr($article['article_image'], strrpos($article['article_image'], '/') + 1);  ?> "/>


</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"); ?>

    <div class="body-container">
        <!-- item -->
        <div class="body-widget">
            <div class="item-content">
                <div class="item-holder" style="background-image: url('<?php echo $article['article_bg_image']; ?>');">
                    <div class="item-img">
                        <img class="img" src='<?php echo $article['article_image']; ?>' alt="">

                    </div>
                </div>
                <div class="item-desc">
                    <div class="item-name">
                        <h2><?php echo $article['article_name'] ?></h2>
                    </div>
                    <div class="doner-name">
                        <p>By <span><a href="#"><?php echo $article['username'] ?></a></span></p>
                        <p style="font-family:sans-serif">at <?php echo $article['created_at'] ?></p>
                    </div>
                    <!-- Go to www.addthis.com/dashboard to customize your tools -->
                    <div class="addthis_inline_share_toolbox" style="text-align:center"></div>

                    <!-- <div class="like-share">
                        <button class="btn btn-secondary" onclick="share('Update From XPOINTS.LK','Hey,Look at this! *<?php echo $article['article_name'] ?>* on XpointsLk')">Share <i class="fas fa-share"></i></button>

                    </div> -->



                </div>


            </div>

            


            <div id="added-to-fav" class="pos-ab blurBg" style="display: none;">
                <div style="margin-top:20vh;text-align:center;align-items:center">
                    <h3 style="font-size:26px;">Added to favourite!</h3>
                    <img style="width: 250px;height:250px;" src="/assets/images/fruit.png" alt="">
                    <button class="btn" style="width: 100%;margin-top:2%;" onclick="$('#added-to-fav').hide()">Okay</button>
                </div>
            </div>

            <!-- <div class="border-icon" id="doner-img-holder">
                <div class="profile-frame">
                    <img class="profile-img" src="https://www.filmibeat.com/ph-big/2021/08/anikha-surendran_16286568946.jpg" alt="">
                </div>
            </div>
            <div class="border-icon" id="b-icon-bl">
                <i class="fa fa-info-circle" aria-hidden="true"></i>
            </div>  -->
        </div>
        <!-- <div class="preview-sticky">
            <div class="like-share-cmnt">
                <p><span>1.5k</span> Likes</p>
                <p><span>1.5k</span> shares</p>
                <p><span>1.5k</span> comments</p>
            </div>
            <div class="like-share">
                <button class="btn btn-secondary" onclick="like_this_article(<?php echo $article['article_id'] ?>)">Like <i class="fa fa-thumbs-up" aria-hidden="true" ></i></button>
                <button class="btn btn-secondary" onclick="share('Read <?php echo $article['article_name']  ?> From XPOINTS.LK','Create your account in XPOINTS.LK to get latest articles!')">Share <i class="fas fa-share"></i></button>
            </div>
            <script>
                function like_this_article(article_id){
                    $("#added-to-fav").show();
                }
            </script>
            <div class="cmt-btn">
                <a href="#comments" class="btn">Comment <i class="fa fa-comment" aria-hidden="true"></i></a>
            </div> 
            <a href="./index.php"><button class="btn btn-secondary"><i class="fas fa-home"></i> Go Home </button></a>
        </div> -->






        <!-- article-content -->
        <?php if (isset($article['article_editor_content'])) { ?>
            <div class="body-widget">
                <div class="item-preview">
                    <?php echo $article['article_editor_content']; ?>
                </div>
            </div>
        <?php } ?>

        <style>


        </style>

        <?php if (isset($article['article_file_content'])) { ?>
            <div class="body-widget">
                <?php
                $file_format = getfileformat($article['article_file_content']);
                if (in_array($file_format, $image_formats)) {
                    echo "<img src='{$article['article_file_content']}'>";
                } else if ($file_format == "pdf") {
                    $pdf_url = "http://docs.google.com/gview?embedded=true&url=https://xpoints.lk" . $article['article_file_content'];
                    echo "<embed width='100%' height='500px' src='$pdf_url'><h4 style='font-family:sans-serif'>Open PDF <a href='https://xpoints.lk/{$article['article_file_content']}'>here</a></h4>";
                } elseif (in_array($file_format, $video_formats)) {
                    echo "<video width='100%' src='{$article['article_file_content']}' controls></video>";
                } elseif (in_array($file_format, $audio_formats)) {
                    echo "<div class='title'><audio  src='{$article['article_file_content']}' controls width='100%'></audio></div>";
                } else {
                    "unsuported file format!";
                }


                ?>
            </div>
        <?php } ?>


        <!-- article-content -->


        <!-- body-container -->
    </div>

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
                <a class="btn" href="./" style="width:50%;background-color:royalblue">Load More.</a>
        </div>
    </div>

</body>
<script src="/assets/js/script.js"></script>

</html>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>