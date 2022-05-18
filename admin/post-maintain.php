<?php
require("./admin-header.php");

if (isset($_POST['add-post'])) {
    $post_main_cat = $_POST['main-cat'];
    $post_sub_cat = $_POST['sub-cat'];
    $post_name = $_POST['post-name'];
    $post_file = '';


    $required = array($post_main_cat, $post_sub_cat, $post_name);
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "Required fields are not filled.";
            header("location:post-maintain.php");
            exit;
        };
    };


    //check if main_cat already exist
    $post_main_cat_id = dbget("SELECT main_cat_id FROM post_category_main WHERE main_cat_name = '" . $post_main_cat . "' LIMIT 1")[0]['main_cat_id'];


    //check if sub_cat already exist
    $post_sub_cat_id = dbget("SELECT sub_cat_id FROM post_category_sub WHERE sub_cat_name = '" . $post_sub_cat . "' LIMIT 1")[0]['sub_cat_id'];



    if (!isset($post_main_cat_id)) {
        //insert if new cat -main
        dbcmd("INSERT INTO post_category_main (main_cat_name) VALUES('$post_main_cat')");

        $post_main_cat_id = dbget("SELECT main_cat_id FROM post_category_main WHERE main_cat_name='$post_main_cat' limit 1")[0]['main_cat_id'];
    };



    if (!isset($post_sub_cat_id)) {
        //insert if new cat -sub
        dbcmd("INSERT INTO post_category_sub (main_cat_id,sub_cat_name) VALUES($post_main_cat_id,'$post_sub_cat')");
        //seting newly stored cat id
        $post_sub_cat_id = dbget("SELECT sub_cat_id FROM post_category_sub WHERE sub_cat_name='$post_sub_cat' limit 1")[0]['sub_cat_id'];
    };

    //check if post_name already exist with exact category and sub cat using mysql
    if (dbget("SELECT id FROM post_maintain WHERE main_cat_id='" . $post_main_cat_id . "' and sub_cat_id='" . $post_sub_cat_id . "' and name='" . $post_name . "' limit 1")) {
        $_SESSION['message']['error'] = "This Post is already available in same category..";
        header("location:post-maintain.php");
        exit;
    }



    if (isset($_FILES['post-file'])) {
        $post_file = uploadFiles($_FILES['post-file'], "__user_uploads/user__" . $_SESSION['user']['username'] . "/posts");
    }




    //finally add all to db
    $query = "INSERT INTO post_maintain (
      main_cat_id,
      sub_cat_id,
      name,
      file,
      description,
      uploaded_by) 
          VALUES (
          $post_main_cat_id,
          $post_sub_cat_id,
          '$post_name',
          '$post_file',
          '" . $_POST['post-desc'] . "',
          " . $_SESSION['user']['id'] . "
          )";

    if (mysqli_query($db, $query)) {
        $_SESSION['message']['success'] = "post has been successfuly stored in database.";
        header("location:post-maintain.php");
        exit;
    } else {
        die(mysqli_error($db));
    }
};

if (isset($_POST['delete-post'])) {
    $required = array($_POST['main-cat'], $_POST['sub-cat'], $_POST['post-name']);
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "Required fields are not filled.";
            header("location:post-maintain.php");
            exit;
        }
    }
    function invalidSelection()
    {
        $_SESSION['message']['error'] = "Invalid Post Selection!!";
        header("location:post-maintain.php");
        exit;
    }

    //delete everything from posts
    if ($_POST['main-cat'] == "*" && $_POST['sub-cat'] == "*" && $_POST['post-name'] == "*") {
        if (mysqli_multi_query(
            $db,
            "DELETE from post_category_main;
          DELETE from post_category_sub;
          DELETE from post_maintain;
          DELETE FROM comment WHERE post=1;
          ALTER TABLE post_category_main AUTO_INCREMENT = 1;
          ALTER TABLE post_category_sub AUTO_INCREMENT = 1;
          ALTER TABLE post_maintain AUTO_INCREMENT = 1;"
        )) {
            $_SESSION['message']['success'] = "Entire POST database has been cleaned!";
            header("location:post-maintain.php");
            exit;
        }
    }
    //delete all post from 1 category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] != "*" && $_POST['post-name'] == "*") {
        $post_main_cats = dbget("SELECT * FROM post_category_main WHERE main_cat_name='" . $_POST['main-cat'] . "' limit 1");

        $post_sub_cats = dbget("SELECT * FROM post_category_sub WHERE sub_cat_name='" . $_POST['sub-cat'] . "' limit 1");

        $post_main_cat_id = $post_main_cats[0]['main_cat_id'];
        $post_sub_cat_id  = $post_sub_cats[0]['sub_cat_id'];

        if (mysqli_multi_query(
            $db,
            "DELETE from post_maintain WHERE main_cat_id=$post_main_cat_id and sub_cat_id=$post_sub_cat_id;
      DELETE from post_category_sub WHERE sub_cat_id=$post_sub_cat_id;"
        )) {
            $_SESSION['message']['success'] = "All posts from '" . $_POST['main-cat'] . "' -> '" . $_POST['sub-cat'] . "' have been deleted!";
            header("location:post-maintain.php");
            exit;
        }
    }
    //delete *posts and  * sub categories from selected main category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] == "*" && $_POST['post-name'] == "*") {
        $post_main_cats = dbget("SELECT main_cat_id FROM post_category_main WHERE main_cat_name='" . $_POST['main-cat'] . "' limit 1");
        $post_main_cat_id = $post_main_cats[0]['main_cat_id'];


        if (mysqli_multi_query(
            $db,
            "DELETE from post_category_main WHERE main_cat_id=$post_main_cat_id;
      DELETE from post_category_sub WHERE main_cat_id=$post_main_cat;
      DELETE from post_maintain WHERE main_cat_id=$post_main_cat_id"
        )) {
            $_SESSION['message']['success'] = "All posts and all sub categories from '" . $_POST['main-cat'] . "' have been deleted!";
            header("location:post-maintain.php");
            exit;
        }
    }


    //delete selected post from seletecd category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] != "*" && $_POST['post-name'] != "*") {
        $post_main_cats = dbget("SELECT main_cat_id FROM post_category_main WHERE main_cat_name='" . $_POST['main-cat'] . "' limit 1");
        $post_sub_cats = dbget("SELECT sub_cat_id FROM post_category_sub WHERE sub_cat_name='" . $_POST['sub-cat'] . "' limit 1");
        $post_names = dbget("SELECT id FROM post_maintain WHERE name='" . $_POST['post-name'] . "' limit 1");

        $post_main_cat_id = $post_main_cats[0]['main_cat_id'];
        $post_sub_cat_id  = $post_sub_cats[0]['sub_cat_id'];
        $post_id          = $post_names[0]['id'];

        if (dbcmd("DELETE FROM post_maintain WHERE main_cat_id=$post_main_cat_id and sub_cat_id=$post_sub_cat_id and id = $post_id")) {
            $_SESSION['message']['success'] = "Post Name: '" . $_POST['post-name'] . "' has been deleted!";
            header("location:post-maintain.php");
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="body-container" style="padding: 0;">
        <form class="body-widget" action="" method="post" enctype="multipart/form-data">
            <h3 class="title">Post Maintain</h3>

            <?php require('../assets/php/response.php'); ?>
            <div class="input-group">
                <label>Main Category *</label>
                <div class="form-content">
                    <select name="main-cat" onchange="changeCat(this);">
                        <option value="" selected disabled>Select Category</option>
                        <?php
                        $post_category_main_table = dbget("SELECT * FROM post_category_main");
                        if (!empty($post_category_main_table)) {
                            foreach ($post_category_main_table as $post_cat) {
                                $cat_name = $post_cat['main_cat_name'];
                                $cat_id = $post_cat['main_cat_id'];
                                echo
                                <<<EOT
                  <option value="$cat_name" main_cat_id="$cat_id">$cat_name</option>
                  EOT;
                            }
                        }
                        ?>
                    </select>

                    <button type="button" class="btn" onclick="openAdd(this,'main-cat');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter main Category name">
                    <button type="button" class="btn" onclick="cancelAdd(this,'main-cat');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="input-group">
                <label>Sub Category *</label>
                <div class="form-content">
                    <select name="sub-cat" onchange="changePost(this);" id="sub-cat">
                        <option value="" selected disabled>Select Category</option>
                        <script>
                            function changeCat(element) {
                                $.post('./ajax-response.php', {
                                        changeCat: true,
                                        selected_main_cat: element.options[element.selectedIndex].getAttribute("main_cat_id")
                                    },
                                    function(data) {
                                        var data = $.parseJSON(data);
                                        document.getElementById
                                        for (var i = 0; i < data.sendSubCats.length; i++) {
                                            document.getElementById('sub-cat').innerHTML = data.sendSubCats;
                                        };
                                        document.getElementById('post-name').innerHTML = '';
                                    }
                                );
                            }
                        </script>
                    </select>

                    <button type="button" class="btn" onclick="openAdd(this,'sub-cat');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter Sub Category name">
                    <button type="button" class="btn" onclick="cancelAdd(this,'sub-cat');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="input-group">
                <label>Post Name *</label>
                <div class="form-content">
                    <select name="post-name" id="post-name">
                        <option value="" selected disabled>Select post</option>
                        <script>
                            function changePost(element) {
                                $.post('./ajax-response.php', {
                                        changePost: true,
                                        selected_main_cat: element.options[element.selectedIndex].getAttribute("main_cat_id"),
                                        selected_sub_cat: element.options[element.selectedIndex].getAttribute("sub_cat_id")
                                    },
                                    function(data) {
                                        var data = $.parseJSON(data);
                                        for (var i = 0; i < data.post_names.length; i++) {
                                            document.getElementById('post-name').innerHTML = data.post_names;
                                        }

                                    }
                                );
                            }
                        </script>
                    </select>
                    <button type="button" class="btn" onclick="openAdd(this,'post-name');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter Post name">
                    <button type="button" class="btn" onclick="cancelAdd(this,'post-name');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="input-group">
                <label for="post-file" class="btn">choose file <br><img src="/assets/images/plus.png" alt="" id="post-file-pr"></label>
                <input type="file" name="post-file" id="post-file" onchange="readURL(this,'post-file-pr')">
            </div>
            <div class="input-group">
                <label for="post-desc">Description</label>
                <div class="form-content">
                    <input type="text" name="post-desc" placeholder="Describe about post.">
                </div>
            </div>




            <div class="article-btns">
                <button class="btn" name="add-post" type="submit">Add</button>
                <button class="btn" name="modify-post">Modify</button>
                <button class="btn" name="delete-post" type="submit">Delete</button>
            </div>
        </form>


        <div class="body-widget">
            <div class="title">Posts to be Approved.</div>
            <?php
            $posts_to_be_approved = dbget("SELECT * FROM user_post");
            if (!empty($posts_to_be_approved)) {
                foreach ($posts_to_be_approved as $post_tba) {

            ?>
                    <div class="new-feeds-content" id="ptba-<?php echo $post_tba['post_id'] ?>">
                        <div class="content-thumbnail">
                            <a href="<?php echo $post_tba['post_file'] ?>" target="_blank">
                                <img src="<?php echo $post_tba['post_file'] ?>" alt="">
                            </a>
                        </div>
                        <div class="content-desc">
                            <p>
                                <?php echo $post_tba['post_title'] ?>
                            </p>
                            <p>
                                <?php echo $post_tba['post_desc'] ?>
                            </p>
                        </div>
                        <div class="remove-content">
                            <p>
                                <i class="fa fa-check" aria-hidden="true" onclick="approveOrDecline(<?php echo $post_tba['post_id'] ?>,'approve');"></i>
                            </p>
                        </div>
                        <div class="remove-content">
                            <p><i class="fas fa-ban" onclick="approveOrDecline(<?php echo $post_tba['post_id'] ?>,'decline');"></i></p>
                        </div>
                    </div>

            <?php }
            } ?>
        </div>

    </div>
</body>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>