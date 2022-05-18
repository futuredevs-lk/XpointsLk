<?php
include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (isset($_GET['main_category'])) {
    $main_cat = dbget("SELECT * FROM shop_mc WHERE id = '" . $_GET['main_category'] . "'limit 1");
} else {
    $main_cat = dbget("SELECT * FROM shop_mc ORDER BY created_at desc
    limit 1");
}

if (!empty($main_cat)) {
    $main_cat_id = $main_cat[0]['id'];
    $main_cat_name = $main_cat[0]['cat_name'];
} else {
    header("location:/pages/404.html");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shop - XPOINTS.LK</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="Buy on XpointsLk">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk/shop?>">
    <meta property="og:description" content="Buy @ XpointsLK">
    <meta property="og:image" itemprop="image" content="https://xpoints.lk/<?php echo $site_elements[12]['img'] ?>" />
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">

</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"); ?>


    <div class="body-container">
        <div class="tab">
            <?php
            //active element
            echo "<div  class='title active'>$main_cat_name</div>";

            //other elements
            $main_cats = dbget("SELECT * FROM shop_mc WHERE id != $main_cat_id order by created_at desc");
            if (!empty($main_cats)) {
                foreach ($main_cats as $main_cat) {
                    echo
                    "<a class='title' href='?main_category={$main_cat['id']}'>{$main_cat['cat_name']}</a>";
                }
            }
            ?>
        </div>

        <?php
        $sub_cats = dbget("SELECT * FROM shop_sc WHERE mc_id=$main_cat_id order by created_at desc");
        if (!empty($sub_cats)) {
            foreach ($sub_cats as $sub_cat) {
                $sub_cat_id = $sub_cat['id'];
        ?>
                <div class="body-widget">
                    <div class="title"><?php echo $sub_cat['cat_name']; ?></div>
                    <div class="slider">
                        <?php
                        $products = dbget("SELECT * FROM shop_product WHERE mc_id=$main_cat_id and sc_id = $sub_cat_id order by created_at desc");
                        if (!empty($products)) {
                            foreach ($products as $product) {
                        ?>
                                <div class="post-container">
                                    <div class="post-head">
                                        <div class="post-uploader">
                                            <img src="<?php echo dbget('SELECT image FROM users WHERE id=' . $product['seller'] . ' limit 1')[0]['image']; ?>" alt="">
                                        </div>
                                        <h3><?php echo dbget('SELECT fname FROM users WHERE id=' . $product['seller'] . ' limit 1')[0]['fname']; ?></h3>

                                    </div>




                                    <a href="./product.php?product_id=<?php echo $product['id']; ?>">
                                        <div class="post-file">
                                            <?php
                                            echo "<img src='{$product["image"]}'>"; ?>
    
                                        </div>
                                    </a>





                                    <div class="post-footer">
                                        <div class="post-caption">

                                            <h2 style="
                        font-size: 250%;
                        font-weight: 800;
                        color: #009877;
                        text-align: center;">RS <?php echo $product["price"] ?></h2>
                                        </div>
                                        <div class="footer-icons">
                                            <a href="./product.php?product_id=<?php echo $product['id']; ?>" class="btn">VIEW</a>
                                            <button class="btn" onclick="try{addUrPost(<?php echo $product['mc_id'], ',', $product['sc_id']; ?>);}catch{window.location.href='/login?redir=shop'}">SELL</button>
                                        </div>

                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>
        <?php }
        } ?>
        <!-- sub-cat        -->

        <!-- post-item -->

        <!-- body-container -->

        <!-- add-ur-postwindow -->
        <div class="pos-fx all-0 add-ur-post desk-mw" id="add-ur-post" style="background-color:var(--sidenav-bg-color);color:var(--sidenav-font-color)">
            <h4 style="font-size: 1.2rem;text-align:center">Add your Product</h4>
            <div class="add-content">
                <label class="btn input-file-label" for="post-file">
                    <img src="/assets/images/584abf102912007028bd9332.png" alt="" id="product-img-pr">
                </label>
                <h5 style="text-align: center;">set icon</h5>
                <input type="file" id="post-file" onchange="readURL(this,'product-img-pr')">
                <div class="input-group">
                    <label for="">Title: </label>
                    <input type="text" id="post-title" placeholder="Add a title..">
                </div>

                <div class="input-group">
                    <label for="">Price: </label>
                    <input type="number" id="post-price" placeholder="Set price in RS.">
                </div>


                <div class="input-group">
                    <label for="">Description</label>
                    <textarea id="post-desc" cols="30" rows="10" placeholder="Add description"></textarea>
                </div>



                <div class="add-ur-p-btns">
                    <button class="btn" onclick="submitUrPost();">SUBMIT</button>
                    <button class="btn" onclick="document.getElementById('add-ur-post').style.display='none';">CANCEL</button>
                </div>
            </div>
            <div id="submit-loader" class="pos-ab" style="padding-top: 25%;background-color: rgb(34, 103, 112);display: none;">
                <div class="loader">
                    <div class="circle"></div>
                    <h5 class="title">uploading..</h5>
                </div>
            </div>
            <div id="submit-success" class="pos-ab" style="padding:25vh 0;background-color: rgb(34, 103, 112);display: none;">
                <h3 class="title" style="font-size: 1.5rem;">Your post has been successfully submited.<br>
                    <p style="font-size: 0.7rem;">we will check it manually and notify you once it posted!</p>
                </h3>
                <button class="btn" style="width: 100%;" onclick="$('#submit-success').hide();$('#add-ur-post').hide(); ">Okay</button>

            </div>
        </div>
    </div>


</body>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/footer.php") ?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>


<script>
    $('.slider').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        arrows: 0,
        dots: true,
        slide: ".post-container",
        infinite: true
    });
    <?php if (isset($_SESSION['user'])) { ?>

        function addUrPost(m_id, s_id) {
            $("#add-ur-post").show();
            document.getElementById("add-ur-post").setAttribute("m_id", m_id);
            document.getElementById("add-ur-post").setAttribute("s_id", s_id);
        }

        function submitUrPost() {
            $("#submit-loader").show();
            let post_file = document.getElementById("post-file").files[0];
            let main_cat_id = document.getElementById("add-ur-post").getAttribute("m_id");
            let sub_cat_id = document.getElementById("add-ur-post").getAttribute("s_id");
            let post_title = document.getElementById("post-title").value;
            let post_desc = document.getElementById("post-desc").value;
            let post_price = document.getElementById("post-price").value;


            let formData = new FormData();

            formData.append("submit_ur_post", true);
            formData.append("post_file", post_file);
            formData.append("main_cat_id", main_cat_id);
            formData.append("sub_cat_id", sub_cat_id);
            formData.append("post_title", post_title);
            formData.append("post_desc", post_desc);
            formData.append("post_price", post_price);

            $.ajax({
                url: './ajax-response.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(response) {
                    $("#submit-loader").hide();
                    $("#submit-success").show();
                    document.getElementById("post-file").files[0] = "";
                    document.getElementById("post-title").value = "";
                    document.getElementById("post-desc").value = "";
                    $("#post-file-pr").attr("src","/assets/images/584abf102912007028bd9332.png");
                    console.log(response);
                }
            });
        }
    <?php } ?>
</script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>