<?php
include $_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php";
if (isset($_GET["main_category"])) {
    $main_cat = dbget(
        "SELECT * FROM post_category_main WHERE main_cat_id = '" .
            $_GET["main_category"] .
            "' limit 1"
    );
} else {
    $main_cat = dbget(
        "SELECT * FROM post_category_main ORDER BY created_at desc limit 1"
    );
}

if (!empty($main_cat)) {
    $main_cat_id = $main_cat[0]["main_cat_id"];
    $main_cat_name = $main_cat[0]["main_cat_name"];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts - XPOINTS.LK</title>
    <meta name="keywords" content="XpointsLk,Posts,Articles,Xpoints">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <?php if (isset($_GET["post_id"])) {
        $post = dbget(
            "SELECT * FROM post_maintain WHERE id = {$_GET["post_id"]}"
        )[0];
        if (!empty($post)) {
            $og_image = "https://xpoints.lk/assets/uploads/thumbnails/" . substr($post['file'], strrpos($post['file'], '/') + 1);
            echo <<<EOT
                <meta property='og:image' content='$og_image' />
                <meta property="og:title" content="{$post['name']} - {$post['description']} \nPOSTS - XPOINTS.LK">
                <meta property="og:type" content="website">
                <meta property="og:url" content="https://xpoints.lk/posts/?post_id={$post['id']}">
                <meta property="og:description" content="FUNNY MEMES ON XPOINTS.LK.">
                EOT;
        }
    }
    ?>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="style.css">
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">


</head>

<body>
    <?php include $_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"; ?>

    <div class="body-container">


        <div class="tab">
            <?php //active element


            echo "<div  class='title active'>$main_cat_name</div>"; //other elements
            $main_cats = dbget(
                "SELECT * FROM post_category_main WHERE main_cat_id != $main_cat_id order by created_at desc"
            );
            if (!empty($main_cats)) {
                foreach ($main_cats as $main_cat) {
                    echo "<a class='title' href='?main_category={$main_cat["main_cat_id"]}'>{$main_cat["main_cat_name"]}</a>";
                }
            }
            ?>
        </div>
        <?php if (!empty($post)) { ?>


            <div class="body-widget">
                <h4 class="title">For You..</h4>
                <div class="post-container body-widget">
                    <div class="post-head">
                        <div class="post-uploader">
                            <img loading="lazy" src="<?php echo dbget(
                                                            "SELECT image FROM users WHERE id=" .
                                                                $post["uploaded_by"] .
                                                                " limit 1"
                                                        )[0]["image"]; ?>" alt="">
                        </div>
                        <div class="uploader-name"><a href="#"><?php echo dbget(
                                                                    "SELECT fname FROM users WHERE id=" .
                                                                        $post["uploaded_by"] .
                                                                        " limit 1"
                                                                )[0]["fname"]; ?></a></div>
                    </div>





                    <div class="post-file">

                        <?php
                        $file_format = getfileformat($post["file"]);
                        if (in_array($file_format, $image_formats)) {
                            echo "<img src='{$post["file"]}'>";
                        } elseif ($file_format == "pdf") {
                            $pdf_url =
                                "http://docs.google.com/gview?embedded=true&url=https://xpoints.lk" .
                                $post["file"];
                            echo "<embed src='$pdf_url' width='100%' height='100%'>";
                        } elseif (in_array($file_format, $video_formats)) {
                            echo "<video poster='https://static.vecteezy.com/system/resources/previews/001/200/436/large_2x/music-player-button-play-png.png' preload='none' src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%' onclick='this.play()'></video>";
                        } elseif (in_array($file_format, $audio_formats)) {
                            echo "<div class='title'><audio src='{$post["file"]}' controls type='audio/$file_format' width='100%' height='100%'></audio></div>";
                        } else {
                            echo "<video poster='https://static.vecteezy.com/system/resources/previews/001/200/436/large_2x/music-player-button-play-png.png' preload='none' src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%' onclick='this.play()'></video>";
                        }
                        ?>
                    </div>





                    <div class="post-footer">
                        <div class="footer-icons" style="align-items:center">
                            <!--<p ><span>259</span> Likes</p>-->
                            <a class="btn" href="<?php echo $post["file"]; ?>" download><i class="fa fa-download" aria-hidden="true"></i></a>
                            <!--<i  class="fa fa-heart" aria-hidden="true"></i>-->
                            <button class="btn" onclick="try{addUrComment(<?php echo $post['id']; ?>)}catch{window.location.href='/login?redir=posts'}"><i class="fa fa-comment" aria-hidden="true"></i></button>
                            <button class="btn" onclick="try{addUrPost(<?php echo $main_cat_id,
                                                                        ',',
                                                                        $sub_cat_id; ?>);}catch{window.location.href='/login?redir=posts'}"><i class="fa fa-plus" aria-hidden="true"></i></button>
                            <!--<a href="?post_id=<?php echo $post['id']; ?>">
                                <button class="btn btn-secondary" onclick="share('Update From XPOINTS.LK','Hey,Look at this! *<?php echo $post['name']; ?>* on XpointsLk','?post_id=<?php echo $post['id']; ?>')">Share <i class="fas fa-share"></i></button>
                            </a>-->
                             <!-- Go to www.addthis.com/dashboard to customize your tools -->
                            <div class="addthis_inline_share_toolbox"></div>
                        </div>

                        <div class="post-caption scroll scroll-y">
                            <h4 class="title"><?php echo $post["name"]; ?></h4>
                            <p><?php echo $post["description"]; ?></p>
                        </div>

                       
                    </div>
                </div>
            </div>
        <?php
        } ?>

        <?php
        $sub_cats = dbget(
            "SELECT * FROM post_category_sub WHERE main_cat_id=$main_cat_id order by created_at desc"
        );
        if (!empty($sub_cats)) {
            foreach ($sub_cats as $sub_cat) {
                $sub_cat_id = $sub_cat["sub_cat_id"]; ?>
                <div class="body-widget">
                    <div class="title"><?php echo $sub_cat["sub_cat_name"]; ?></div>
                    <div class="slider">
                        <?php
                        $posts = dbget(
                            "SELECT * FROM post_maintain WHERE main_cat_id=$main_cat_id and sub_cat_id = $sub_cat_id order by uploaded_on desc"
                        );
                        if (!empty($posts)) {
                            foreach ($posts as $post) { ?>
                                <div class="post-container">
                                    <div class="post-head">
                                        <div class="post-uploader">
                                            <img loading="lazy" src="<?php echo dbget(
                                                                            "SELECT image FROM users WHERE id=" .
                                                                                $post["uploaded_by"] .
                                                                                " limit 1"
                                                                        )[0]["image"]; ?>" alt="">
                                        </div>
                                        <div class="uploader-name"><a href="#"><?php echo dbget(
                                                                                    "SELECT fname FROM users WHERE id=" .
                                                                                        $post["uploaded_by"] .
                                                                                        " limit 1"
                                                                                )[0]["fname"]; ?></a></div>
                                    </div>





                                    <div class="post-file">

                                        <?php
                                        $file_format = getfileformat(
                                            $post["file"]
                                        );
                                        if (
                                            in_array($file_format, $image_formats)
                                        ) {
                                            echo "<img src='{$post["file"]}'>";
                                        } elseif ($file_format == "pdf") {
                                            $pdf_url =
                                                "http://docs.google.com/gview?embedded=true&url=https://xpoints.lk" .
                                                $post["file"];
                                            echo "<embed src='$pdf_url' width='100%' height='100%'>";
                                        } elseif (
                                            in_array($file_format, $video_formats)
                                        ) {
                                            echo "<video poster='https://static.vecteezy.com/system/resources/previews/001/200/436/large_2x/music-player-button-play-png.png' preload='none' src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%' onclick='this.play()'></video>";
                                        } elseif (
                                            in_array($file_format, $audio_formats)
                                        ) {
                                            echo "<div class='title'><audio src='{$post["file"]}' controls type='audio/$file_format' width='100%' height='100%'></audio></div>";
                                        } else {
                                            echo "<video poster='https://static.vecteezy.com/system/resources/previews/001/200/436/large_2x/music-player-button-play-png.png' preload='none' src='{$post["file"]}' controls type='video/$file_format' width='100%' height='100%' onclick='this.play()'></video>";
                                        }
                                        ?>
                                    </div>





                                    <div class="post-footer">
                                        <div class="footer-icons">
                                            <!--<p ><span>259</span> Likes</p>-->
                                            <a class="btn" href="<?php echo $post["file"]; ?>" download><i class="fa fa-download" aria-hidden="true"></i></a>
                                            <!--<i  class="fa fa-heart" aria-hidden="true"></i>-->
                                            <button class="btn" onclick="try{addUrComment(<?php echo $post["id"]; ?>)}catch{window.location.href='/login?redir=posts'}"><i class="fa fa-comment" aria-hidden="true"></i></button>
                                            <button class="btn" onclick="try{addUrPost(<?php echo $main_cat_id,
                                                                                        ",",
                                                                                        $sub_cat_id; ?>);}catch{window.location.href='/login?redir=posts'}"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                            <a class="btn" href="?post_id=<?php echo $post['id']; ?>">
                                                <!--<button class="btn btn-secondary" onclick="share('Update From XPOINTS.LK','Hey,Look at this! *<?php echo $post['name']; ?>* on XpointsLk','?post_id=<?php echo $post['id']; ?>')"> <i class="fas fa-share"></i></button>--> 
                                             <i class="fas fa-share"></i>
                                            </a>
                                        </div>
                                        <div class="post-caption scroll scroll-y">
                                            <h4 class="title"><?php echo $post["name"]; ?></h4>
                                            <p><?php echo $post["description"]; ?></p>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        }
                        ?>
                    </div>
                </div>
        <?php
            }
        }
        ?>
        <!-- sub-cat        -->

        <!-- post-item -->

        <!-- body-container -->

        <!-- add-ur-postwindow -->




        <div class="pos-fx all-0 body-widget blurBg add-ur-post scroll scroll-y desk-mw" id="add-ur-post">
            <h4 class="title">Add your Post</h4>
            <div class="add-content">
                <label for="post-file" class="btn">
                    <h3>Select File</h3>
                    <img src="/assets/images/584abf102912007028bd9332.png" alt="" id="post-file-pr">
                </label>
                <input type="file" id="post-file" required onchange="readURL(this,'post-file-pr')">
                <input type="text" id="post-title" placeholder="Add a title..">
                <textarea id="post-desc" cols="30" rows="10" placeholder="Describe about this post.."></textarea>
                <div class="add-ur-p-btns">
                    <button class="btn" onclick="submitUrPost();">SUBMIT</button>
                    <button class="btn" onclick="document.getElementById('add-ur-post').style.display='none';">CANCEL</button>
                </div>
            </div>
            <div id="submit-loader" class="pos-fx all-0" style="padding-top: 25%;background-color: rgb(34, 103, 112);display: none;">
                <div class="loader">
                    <div class="circle"></div>
                    <h5 class="title">uploading..</h5>
                </div>
            </div>
            <div id="submit-success" class="pos-fx all-0" style="padding-top: 25%;background-color: rgb(34, 103, 112);display: none;">
                <h5 class="title">Your post has been successfully submited.<br>
                    <p>we will check it manually and notify you once it posted!</p>
                </h5>
                <button class="btn" style="width: 100%;" onclick="$('#submit-success').hide();$('#add-ur-post').hide(); ">Okay</button>

            </div>
        </div>





        <!-- comment window -->

        <div id="cmt-window" class="cmt-section blurBg desk-mw">
            <button style="text-align:center;margin-bottom:2%" class="btn" onclick="$(this).parent().hide();">CLOSE</button>
            <div id="comment_container" class="scroll scroll-y" style="height: 80%;"></div>


            <div class="type-comment">
                <div class="cmt-box">
                    <input class="user-cmt" type="text" id="user-comment" name="user-comment" placeholder="Add your comment.." style="padding:10px 20px;">
                    <button id="post-cmt" onclick="posturcomment()">post</button>
                </div>
            </div>
        </div>


        <!-- /* comment-window===================== */ -->






    </div>


</body>
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


    // add comment,like,shar upload-mine will only work if loggen in
    <?php if (isset($_SESSION["user"])) { ?>

        function addUrPost(m_id, s_id) {
            $("#add-ur-post").show();
            document.getElementById("add-ur-post").setAttribute("m_id", m_id);
            document.getElementById("add-ur-post").setAttribute("s_id", s_id);
        }

        function submitUrPost() {

            let post_file = document.getElementById("post-file").files[0];
            let main_cat_id = document.getElementById("add-ur-post").getAttribute("m_id");
            let sub_cat_id = document.getElementById("add-ur-post").getAttribute("s_id");
            let post_title = document.getElementById("post-title").value;
            let post_desc = document.getElementById("post-desc").value;

            if (post_file == '' || post_title == "") {
                alert("All fields are required");
                return;
            }
            $("#submit-loader").show();
            let formData = new FormData();

            formData.append("submit_ur_post", true);
            formData.append("post_file", post_file);
            formData.append("main_cat_id", main_cat_id);
            formData.append("sub_cat_id", sub_cat_id);
            formData.append("post_title", post_title);
            formData.append("post_desc", post_desc);

            $.ajax({
                url: `/posts/ajax-response.php`,
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




        // comment function start here
        function addUrComment(post_id) {
            $.ajax({ //create an ajax request to display.php
                type: "GET",
                url: `/posts/fetch_comments.php`,
                dataType: "html", //expect html to be returned 
                data: {
                    'id': post_id
                },
                success: function(response) {
                    document.getElementById('comment_container').innerHTML = response;
                }
            });

            $('#cmt-window').toggle();
            document.getElementById("cmt-window").setAttribute("post_id", post_id);

        }

        function posturcomment() {
            let post_id = document.getElementById("cmt-window").getAttribute("post_id");
            let comment = document.getElementById("user-comment").value;
            if (comment == "") {
                return;
            }
            document.getElementById("user-comment").value = "";
            document.getElementById('comment_container').innerHTML += `
        <div class="cmt-box">
        <div class="cmt-dp">
            <img  src="<?php echo $_SESSION["user"]["image"]; ?>" alt="">
        </div>
        <div class="cmt-content">
            <p class="commenter-name"><?php echo $_SESSION["user"]["username"]; ?> <span class="comment">${comment}</span></p>

        </div>

    </div>`;
            $.ajax({
                url: '/posts/post_comment.php',
                type: 'POST',
                data: {
                    'post_id': post_id,
                    'comment': comment
                },
                success: function(response) {
                    console.log(response);
                }
            });
        }
    <?php } ?>
    
    //only play one audio or video at a time
    $(function () {
      $("audio,video").on("play", function () {
        $("audio,video")
          .not(this)
          .each(function (index, audio) {
            audio.pause();
          });
      });
    });
</script>


</html>
<?php require $_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"; ?>