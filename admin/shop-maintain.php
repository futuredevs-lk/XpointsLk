<?php
require("./admin-header.php");

if (isset($_POST['add-product'])) {
    $product_main_cat = $_POST['main-cat'];
    $product_sub_cat = $_POST['sub-cat'];
    $product_name = $_POST['product-name'];
    $product_file = '';


    $required = array($product_main_cat, $product_sub_cat, $product_name);
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "Required fields are not filled.";
            header("location:shop-maintain.php");
            exit;
        };
    };


    //check if main_cat already exist
    $shop_mc_id = dbget("SELECT id FROM shop_mc WHERE cat_name = '" . $product_main_cat . "' LIMIT 1")[0]['id'];


    //check if sub_cat already exist
    $shop_sc_id = dbget("SELECT id FROM shop_sc WHERE cat_name = '" . $product_sub_cat . "' LIMIT 1")[0]['id'];



    if (!isset($shop_mc_id)) {
        //insert if new cat -main
        dbcmd("INSERT INTO shop_mc (cat_name) VALUES('$product_main_cat')");

        $shop_mc_id = dbget("SELECT id FROM shop_mc WHERE cat_name='$product_main_cat' limit 1")[0]['id'];
    };



    if (!isset($shop_sc_id)) {
        //insert if new cat -sub
        dbcmd("INSERT INTO shop_sc (mc_id,cat_name) VALUES($shop_mc_id,'$product_sub_cat')");
        //seting newly stored cat id
        $shop_sc_id = dbget("SELECT id FROM shop_sc WHERE cat_name='$product_sub_cat' limit 1")[0]['id'];
    };




    if (isset($_FILES['product-file'])) {
        $product_file = uploadFiles($_FILES['product-file'], "__user_uploads/user__" . $_SESSION['user']['username'] . "/shop");
    }




    //finally add all to db
    $query = "INSERT INTO shop_product (
      mc_id,
      sc_id,
      seller,
      name,
      price,
      image,
      description) 
          VALUES (
          $shop_mc_id,
          $shop_sc_id,
          " . $_SESSION['user']['id'] . ",
          '$product_name',
          " . $_POST['product-price'] . ",
          '$product_file',
          '" . $_POST['product-desc'] . "'
          )";

    if (mysqli_query($db, $query)) {
        $_SESSION['message']['success'] = "Product has been successfuly added to shop.";
        header("location:shop-maintain.php");
        exit;
    } else {
        die(mysqli_error($db));
    }
};

if (isset($_POST['delete-product'])) {
    $required = array($_POST['main-cat'], $_POST['sub-cat'], $_POST['product-name']);
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "Required fields are not filled.";
            header("location:shop-maintain.php");
            exit;
        }
    }
    function invalidSelection()
    {
        $_SESSION['message']['error'] = "Invalid Product Selection!!";
        header("location:shop-maintain.php");
        exit;
    }

    //delete everything from shop
    if ($_POST['main-cat'] == "*" && $_POST['sub-cat'] == "*" && $_POST['product-name'] == "*") {
        if (mysqli_multi_query(
            $db,
            "DELETE from shop_mc;
          DELETE from shop_sc;
          DELETE from shop_product;
          ALTER TABLE shop_mc AUTO_INCREMENT = 1;
          ALTER TABLE shop_sc AUTO_INCREMENT = 1;
          ALTER TABLE shop_product AUTO_INCREMENT = 1;"
        )) {
            $_SESSION['message']['success'] = "Entire SHOP database has been cleaned!";
            header("location:shop-maintain.php");
            exit;
        }
    }
    //delete all post from 1 category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] != "*" && $_POST['product-name'] == "*") {
        $product_main_cats = dbget("SELECT * FROM shop_mc WHERE cat_name='" . $_POST['main-cat'] . "' limit 1");

        $product_sub_cats = dbget("SELECT * FROM shop_sc WHERE cat_name='" . $_POST['sub-cat'] . "' limit 1");

        $shop_mc_id = $product_main_cats[0]['id'];
        $shop_sc_id  = $product_sub_cats[0]['id'];

        if (mysqli_multi_query(
            $db,
            "DELETE from shop_product WHERE mc_id=$shop_mc_id and sc_id=$shop_sc_id;
      DELETE from shop_sc WHERE sc_id=$shop_sc_id;"
        )) {
            $_SESSION['message']['success'] = "All Products from '" . $_POST['main-cat'] . "' -> '" . $_POST['sub-cat'] . "' have been deleted!";
            header("location:shop-maintain.php");
            exit;
        }
    }
    //delete *posts and  * sub categories from selected main category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] == "*" && $_POST['product-name'] == "*") {
        $product_main_cats = dbget("SELECT id FROM shop_mc WHERE cat_name='" . $_POST['main-cat'] . "' limit 1");
        $shop_mc_id = $product_main_cats[0]['id'];


        if (mysqli_multi_query(
            $db,
            "DELETE from shop_mc WHERE id=$shop_mc_id;
      DELETE from shop_sc WHERE mc_id=$product_main_cat;
      DELETE from shop_product WHERE mc_id=$shop_mc_id"
        )) {
            $_SESSION['message']['success'] = "All products and all sub categories from '" . $_POST['main-cat'] . "' have been deleted!";
            header("location:shop-maintain.php");
            exit;
        }
    }


    //delete selected post from seletecd category
    if ($_POST['main-cat'] != "*" && $_POST['sub-cat'] != "*" && $_POST['product-name'] != "*") {
        $product_main_cats = dbget("SELECT id FROM shop_mc WHERE cat_name='" . $_POST['main-cat'] . "' limit 1");
        $product_sub_cats = dbget("SELECT id FROM shop_sc WHERE cat_name='" . $_POST['sub-cat'] . "' limit 1");
        $product_names = dbget("SELECT id FROM shop_product WHERE name='" . $_POST['product-name'] . "' limit 1");

        $shop_mc_id = $product_main_cats[0]['id'];
        $shop_sc_id  = $product_sub_cats[0]['id'];
        $product_id          = $product_names[0]['id'];

        if (dbcmd("DELETE FROM shop_product WHERE mc_id=$shop_mc_id and sc_id=$shop_sc_id and id = $product_id")) {
            $_SESSION['message']['success'] = "Product Name: '" . $_POST['product-name'] . "' has been deleted!";
            header("location:shop-maintain.php");
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/main.css">
    <link rel="stylesheet" href="style.css">

</head>

<body>
    <div class="body-container" style="padding: unset;">
        <form class="body-widget" action="" method="post" enctype="multipart/form-data">
            <h3 class="title">Shop Maintain</h3>

            <?php require('../assets/php/response.php'); ?>
            <div class="input-group">
                <label>Main Category *</label>
                <div class="form-content">
                    <select name="main-cat" onchange="changeShopCat(this);">
                        <option value="" selected disabled>Select Category</option>
                        <?php
                        $shop_mc_table = dbget("SELECT * FROM shop_mc");
                        if (!empty($shop_mc_table)) {
                            foreach ($shop_mc_table as $shop_cat) {
                                $cat_name = $shop_cat['cat_name'];
                                $cat_id = $shop_cat['id'];
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
                    <select name="sub-cat" onchange="changeProduct(this);" id="sub-cat">
                        <option value="" selected disabled>Select Category</option>
                        <script>
                            function changeShopCat(element) {
                                $.post('./ajax-response.php', {
                                        changeShopCat: true,
                                        selected_main_cat: element.options[element.selectedIndex].getAttribute("main_cat_id")
                                    },
                                    function(data) {
                                        var data = $.parseJSON(data);
                                        for (var i = 0; i < data.shop_scs.length; i++) {
                                            document.getElementById('sub-cat').innerHTML = data.shop_scs;
                                        };
                                        document.getElementById('product-name').innerHTML = '';
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
                <label>Product Name *</label>
                <div class="form-content">
                    <select name="product-name" id="product-name">
                        <option value="" selected disabled>Select Product</option>
                        <script>
                            function changeProduct(element) {
                                $.post('./ajax-response.php', {
                                        changeProduct: true,
                                        selected_main_cat: element.options[element.selectedIndex].getAttribute("main_cat_id"),
                                        selected_sub_cat: element.options[element.selectedIndex].getAttribute("sub_cat_id")
                                    },
                                    function(data) {
                                        var data = $.parseJSON(data);
                                        for (var i = 0; i < data.product_names.length; i++) {
                                            document.getElementById('product-name').innerHTML = data.product_names;
                                        }

                                    }
                                );
                            }
                        </script>
                    </select>
                    <button type="button" class="btn" onclick="openAdd(this,'product-name');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter Product name">
                    <button type="button" class="btn" onclick="cancelAdd(this,'product-name');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>

            <div class="input-group">
                <label for="product-price">Price of the product</label>
                <div class="form-content">
                    <input type="number" name="product-price" placeholder="Price in Rupees.">
                </div>
            </div>

            <div class="input-group">
                <label for="product-file" class="btn">choose file <br><img src="/assets/images/plus.png" alt="" id="product-file-pr"></label>
                <input type="file" name="product-file" id="product-file" onchange="readURL(this,'product-file-pr')">
            </div>
            <div class="input-group">
                <label for="product-desc">Description</label>
                <div class="form-content">
                    <input type="text" name="product-desc" placeholder="Describe about product.">
                </div>
            </div>




            <div class="article-btns">
                <button class="btn" name="add-product" type="submit">Add</button>
                <button class="btn" name="modify-post">Modify</button>
                <button class="btn" name="delete-product" type="submit">Delete</button>
            </div>
        </form>


        <div class="body-widget">
            <div class="title">Products to be Approved.</div>
            <?php
            $products_to_be_approved = dbget("SELECT * FROM user_shop");
            if (!empty($products_to_be_approved)) {
                foreach ($products_to_be_approved as $product_tba) {

            ?>
                    <div class="new-feeds-content" id="ptba-<?php echo $product_tba['id'] ?>">
                        <div class="content-thumbnail">
                            <a href="<?php echo $product_tba['image'] ?>" target="_blank">
                                <img src="<?php echo $product_tba['image'] ?>" alt="">
                            </a>
                        </div>
                        <div class="content-desc">
                            <p>
                                <?php echo $product_tba['name'] ?>
                            </p>
                            <p>
                                <?php echo $product_tba['description'] ?>
                            </p>
                        </div>
                        <div class="remove-content">
                            <p>
                                <i class="fa fa-check" aria-hidden="true" onclick="approveOrDeclineProduct(<?php echo $product_tba['id'] ?>,'approve');"></i>
                            </p>
                        </div>
                        <div class="remove-content">
                            <p><i class="fas fa-ban" onclick="approveOrDeclineProduct(<?php echo $product_tba['id'] ?>,'decline');"></i></p>
                        </div>
                    </div>

            <?php }
            } ?>
        </div>

    </div>
</body>



<script src="script.js"></script>
<script>
    function approveOrDeclineProduct(id, cmd) {
        $(`#ptba-${id}`).remove();
        $.post('./ajax-response.php', {
            shop_approve_or_decline: true,
            cmd: cmd,
            id: id
        }, function(data) {
            console.log(data);
        });
    }
</script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>