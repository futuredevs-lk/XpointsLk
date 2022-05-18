<?php

require("./admin-header.php");

if (isset($_POST['add-article'])) {


    $required = array($_POST['article-cat'], $_POST['article-name'], $_POST['article-editor-content']);
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "Required fields are not filled.";
            header("location:article-maintain.php");
            exit;
        };
    };






    //check if cat already exist
    $article_cat_id = dbget("SELECT cat_id FROM article_category WHERE cat_name = '" . $_POST['article-cat'] . "' LIMIT 1")[0]['cat_id'];




    if (!isset($article_cat_id)) {
        //insert if new cat
        dbcmd("INSERT INTO article_category(cat_name) VALUES('" . $_POST['article-cat'] . "')");

        $article_cat_id = dbget("SELECT cat_id FROM article_category WHERE cat_name = '" . $_POST['article-cat'] . "' LIMIT 1")[0]['cat_id'];
    };




    /*if(!empty($_POST['article-author'])){
       //check if article_author already exist
      $article_author_id = dbget("SELECT author_id FROM article_author WHERE author_name = '".$_POST['article-author']."' LIMIT 1")[0]['author_id'];

      if(empty($article_author_id)){
          dbcmd("INSERT INTO article_author(author_name) VALUES('".$_POST['article-author']."')");
          
          $article_author_id = dbget("SELECT author_id FROM article_author WHERE author_name = '".$_POST['article-author']."' LIMIT 1")[0]['author_id'];
      };
  }else{
    $article_author_id = 0;
  }*/




    if (!empty($_FILES["article-bg-image"])) {
        $article_bg_image = uploadFiles($_FILES["article-bg-image"], "articles/" . $_POST['article-cat'] . "/" . $_POST['article-name'] . "");
    };
    if (!empty($_FILES['article-image'])) {
        $article_image = uploadFiles($_FILES['article-image'], "__user_uploads/user__" . $_SESSION['user']['username'] . "/article");
    };


    if (isset($_POST['info-link'])) {
        $article_info_link = $_POST['info-link'];
    }
    if (!empty($_FILES['info-img'])) {
        $article_info_img = uploadFiles($_FILES['info-img'], "articles/" . $_POST['article-cat'] . "/" . $_POST['article-name'] . "");
    };

    if (!empty($_FILES['article-file-content'])) {
        $article_file_content = uploadFiles($_FILES['article-file-content'], "articles/" . $_POST['article-cat'] . "/" . $_POST['article-name'] . "");
    };

    //finally add all to db
    $query = "INSERT INTO article_maintain(
    cat_id,
    article_name,
    article_editor_content,
    article_file_content,
    article_image,
    uploaded_by,
    article_bg_image,
    info_button_link,
    info_button_image) 
    VALUES (
      '$article_cat_id',
      '" . $_POST['article-name'] . "',
      '" . $_POST['article-editor-content'] . "',
      '$article_file_content',
      '$article_image',
      " . $_SESSION['user']['id'] . ",
      '$article_bg_image',
      '" . $_POST['info-link'] . "',
      '$article_info_img')";

    if (mysqli_query($db, $query)) {
        $_SESSION['message']['success'] = "Article has been successfuly stored in database.";
        header("location:article-maintain.php");
        exit;
    } else {
        die(mysqli_error($db));
    }
};




if (isset($_POST['modify-article'])) {
    $message = "This Feature Is Coming Soon!";
}





if (isset($_POST['delete-article'])) {
    if (isset($_POST['article-cat'])) {
        if (isset($_POST['article-name'])) {

            // delete entire article datas if * comes
            if ($_POST['article-cat'] == "*" && $_POST['article-name'] == "*") {
                if (mysqli_query($db, "DELETE FROM article_category") && mysqli_query($db, "DELETE FROM article_maintain ")) {
                    $_SESSION['message']['success'] = "ENTIRE DATABSE CLEANED!";
                    header("location:article-maintain.php");
                    exit;
                }
            };
            //  delete all articles from a category
            if ($_POST['article-cat'] != "*" && $_POST['article-name'] == '*') {

                $article_cat_id = dbget("SELECT cat_id FROM article_category WHERE cat_name  = '" . $_POST['article-cat'] . "'")[0]["cat_id"];

                if (mysqli_multi_query($db, "DELETE FROM article_maintain WHERE cat_id=$article_cat_id;DELETE FROM article_category WHERE cat_id=$article_cat_id")) {
                    $_SESSION['message']['success'] = "All Articles from '" . $_POST['article-cat'] . "' Category has been Deleted!";
                    header("location:article-maintain.php");
                    exit;
                }


                //  if unknown category comes
                if (!isset($article_cat_id)) {
                    $_SESSION['message']['error'] = "This Category is not available in our Database!";
                    header("location:article-maintain.php");
                    exit;
                }
            }
            //delete 1 article from a category
            if ($_POST['article-cat'] != "*" && $_POST['article-name'] != "*") {

                $article_cat_id = dbget("SELECT cat_id FROM article_category WHERE cat_name = '" . $_POST['article-cat'] . "'")[0]["cat_id"];


                if (!empty($article_cat_id) && !empty($_POST['article-name'])) {
                    if (dbcmd("DELETE FROM article_maintain WHERE cat_id =$article_cat_id and article_name = '" . $_POST['article-name'] . "'")) {
                        $_SESSION['message']['success'] = "Article Name: '" . $_POST['article-name'] . "' has been Deleted!";
                        header("location:article-maintain.php");
                        exit;
                    }
                } else {
                    $_SESSION['message']['error'] = "This Article is not available in our Database!";
                    header("location:article-maintain.php");
                    exit;
                }
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
</head>

<body>
    <div class="body-container" style="padding:unset">
        <form class="body-widget" action="" method="post" enctype="multipart/form-data" id="article-maintain-form">
            <h3 class="title">Article Maintain</h3>

            <?php require('../assets/php/response.php'); ?>
            <div class="input-group">
                <label>Article Category *</label>
                <div class="form-content">
                    <select name="article-cat" onchange="changeArticle(this);">
                        <option value="" selected disabled>Select Category</option>
                        <?php
                        $article_cats = dbget("SELECT * FROM article_category");
                        if (!empty($article_cats)) {
                            foreach ($article_cats as $article_cat) {
                                $cat_name = $article_cat['cat_name'];
                                $cat_id = $article_cat['cat_id'];
                                echo
                                <<<EOT
                  <option value="$cat_name" cat_id="$cat_id">$cat_name</option>
                  EOT;
                            }
                        }
                        ?>
                    </select>

                    <button type="button" class="btn" onclick="openAdd(this,'article-cat');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter category name..">
                    <button type="button" class="btn" onclick="cancelAdd(this,'article-cat');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="input-group">
                <label>Article Name *</label>
                <div class="form-content">
                    <select name="article-name" id="article-name">
                        <option value="" selected disabled>Select Article</option>
                        <script>
                            function changeArticle(element) {
                                $.post('./ajax-response.php', {
                                        selected_art_cat: element.options[element.selectedIndex].getAttribute("cat_id")
                                    },
                                    function(data) {
                                        document.getElementById
                                        for (var i = 0; i < data.length; i++) {
                                            document.getElementById('article-name').innerHTML = data;
                                        }

                                    }
                                );
                            }
                        </script>
                    </select>
                    <button type="button" class="btn" onclick="openAdd(this,'article-name');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter article name..">
                    <button type="button" class="btn" onclick="cancelAdd(this,'article-name');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <!-- <div class="input-group">
        <label>Article Author</label>
        <div class="form-content" id="select-author">
          <select name="article-author">
          <option value="" disabled selected>Select Author</option>
            
          </select>
          <button type="button" class="btn" onclick="openAdd(this,'article-author');"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </div>
        
        <div class="form-content" style="display: none;" id="add-author">
          <input type="text" placeholder="Enter author name..">
          <button type="button" class="btn" onclick="cancelAdd(this,'article-author');"><i class="fa fa-times" aria-hidden="true"></i></button>
        </div>
      </div> -->



            <style>
                .insert-files .btn {
                    float: left;
                    margin: 1%;
                }
            </style>

            <div class="input-group insert-files">

                <label for="article-image" class="btn title">
                    Thumbnail *
                    <img style="width: 20%;" src="/assets/images/plus.png" alt="" id="article-img-pr">
                </label>

                <input type="file" id="article-image" name="article-image" accept="image/*" onchange="readURL(this,'article-img-pr')">

                <!--           
          <label for="article-bg-image" class="btn">BG *</label>
          <input type="file" name="article-bg-image" id="article-bg-image" accept="image/*">

          <select style="width: 40%;" class="btn" name="select-bg" id="">
            <option value="" selected disabled>or select bg</option>
          </select> -->

            </div>

            <!-- <div class="input-group form-content">
        <input style="width: 60%;" type="text" name="info-link" placeholder="info-btn url">
        <label for="info-img" class="btn">info icon</label>
        <input type="file" id="info-img" name="info-img" accept="image/*">
      </div> -->

            <hr>

            <div class="title">Article content</div>
            <div class="input-group">
                <label for="article-file-content" class="btn" title="Select Article content from your device.">Upload File</label>
                <input type="file" id="article-file-content" name="article-file-content">
            </div>




            <!-- summereditor -->
            <div class="input-group ">
                <textarea id="article-editor-content" name="article-editor-content"></textarea>
            </div>


            <!-- summereditor -->








            <div class="article-btns">
                <button class="btn" name="add-article" type="submit">Add</button>
                <button class="btn" name="modify-article">Modify</button>
                <button class="btn" name="delete-article" type="submit">Delete</button>
            </div>
        </form>

    </div>
</body>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>


<script>
    $(document).ready(function() {
        $('#article-editor-content').summernote({
            minHeight: 300,
            width: 250,
            placeholder: "add article content here..",
            wraptext: true

        });
    });
</script>
<style>
    .note-editor * :not(.note-color-btn) {
        background-color: var(--theme-color);
        color: var(--font-color);
        font-size: 1rem;
    }
</style>

</html>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>