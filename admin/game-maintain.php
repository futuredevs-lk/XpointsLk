<?php
require("./admin-header.php");
include("admin-L1-permission.php");

function validationCheck($array)
{
    if (!isset($_SESSION['tg-maintain']['selected-level'])) {
        $_SESSION['message']['error'] = "SELECT LEVEL FIRST!";
        header("location:game-maintain.php");
        exit;
    }
    $required = $array;
    foreach ($required as $field) {
        if (empty(trim($field))) {
            $_SESSION['message']['error'] = "ALL FIELDS ARE REQUIRED!";
            header("location:game-maintain.php");
            exit;
        };
    };
}

//story--slides---

if (isset($_POST['config_story_slides'])) {
    if ($_POST['ss-count'] == 0) {
        dbcmd("DELETE FROM tg_story_slides");
        $_SESSION['message']['error'] = "No Story Slides!!";
        header("location:game-maintain.php");
        exit;
    }
    //get slideNo and compare with ss-count
    if (dbget("SELECT MAX(slideNo) FROM tg_story_slides")[0]['slideNo'] < $_POST['ss-count']) {
        dbcmd("DELETE FROM tg_story_slides WHERE slideNo NOT BETWEEN 0 AND {$_POST['ss-count']}");
    }
    $count = 1;
    for ($i = 0; $i < $_POST['ss-count']; $i++) {

        $ss_img = uploadFiles($_FILES["story_slide_{$count}"], 'tg_core_images/story_slides');
        if ($ss_img != '') {
            dbcmd("INSERT INTO tg_story_slides(slideNo,img) VALUES({$count},'{$ss_img}')");
        }
        $count += 1;
    }
    $_SESSION['message']['success'] = "Opreation succeed!";
    header("location:game-maintain.php");
    exit;
}

//story--slides****


$default_wallets = array(1, 2, 6); //xp water witch
if (isset($_POST['select-tg-level'])) {
    $_SESSION['tg-maintain']['selected-level'] = $_POST['level'];
    $_SESSION['message']['success'] = "LEVEL " . $_POST['level'] . " IS SELECTED.";
    //ADD CATS TO TG_WALLETS_CATS for this level lake tfrom same table
    dbcmd(
        "INSERT INTO tg_wallets_cat(
      id,
      level,
      name
    )
    SELECT
      id,
      {$_POST['level']},
      name

    FROM tg_wallets_cat_temp
    ON DUPLICATE KEY UPDATE
      level = {$_POST['level']}
    "
    );


    //add wallets from tg_wallets to tg_wallets_levels
    dbcmd(
        "INSERT INTO 
      tg_wallets_levels(
        cat_id,
        level,
        wallet_id,
        wallet_name
      )
      SELECT
        cat_id,
        " . $_SESSION['tg-maintain']['selected-level'] . ",
        id,
        name
      FROM 
        tg_wallets
      WHERE 
        cat_id IN (1,2,6)
      ON DUPLICATE KEY UPDATE
        cat_id      = tg_wallets.cat_id
    "
    );


    //add tg_main_elements_temp to tg_main_elements
    dbcmd(
        "INSERT INTO tg_main_elements(
      level,
      element_id,
      name
    )
    SELECT
      " . $_SESSION['tg-maintain']['selected-level'] . ",
      id,
      name
    FROM 
      tg_main_elements_temp
    ON DUPLICATE KEY UPDATE
      element_id = tg_main_elements_temp.id
    "
    );





    header("location:game-maintain.php");
    exit;
}


function get_wallet_id()
{
    return dbget(
        "SELECT 
      wallet_id
    FROM 
      tg_wallets_levels
    WHERE 
      level   = " . $_SESSION['tg-maintain']['selected-level'] . " and
      cat_id  = " . $_POST['wallet-cat'] . " and
      wallet_name    = '" . $_POST['wallet-name'] . "'
    LIMIT 1
    "
    );
}

function updateTg_w_l_table($column, $data)
{
    global $wallet_id;
    dbcmd(
        "UPDATE 
      tg_wallets_levels 
    SET
      $column = '$data'
    WHERE 
      wallet_id=$wallet_id and 
      level = " . $_SESSION['tg-maintain']['selected-level'] . "
    "
    );
}

// modify wallets===========================
if (isset($_POST['modify-wallet'])) {
    validationCheck(array($_POST['wallet-cat'], $_POST['wallet-name']));

    $get_wallet_id = get_wallet_id();
    if (isset($get_wallet_id[0]['wallet_id'])) {
        $wallet_id = $get_wallet_id[0]['wallet_id'];
    }


    if (!isset($wallet_id)) {
        //water xpoints witch can not be added
        if (in_array($_POST['wallet-cat'], $default_wallets)) {
            $_SESSION['message']['error'] = "You can not create a new wallet into default categories";
            header("location:game-maintain.php");
            exit;
        }

        // add default wallets
        /*dbcmd(
    "INSERT INTO 
      tg_wallets
    (
      name,
      cat_id
    )
    VALUES(
      '".$_POST['wallet-name']."',
      ".$_POST['wallet-cat']."
    )");*/
        //get max id from tg_wallets for store it as wallet_id for new wallet in tg_wallets_levels
        $new_wallet_id = dbget("SELECT MAX(wallet_id) as id FROM tg_wallets_levels")[0]['id'] + 1;

        dbcmd(
            "INSERT INTO
        tg_wallets_levels
        (
          level,
          cat_id,
          wallet_id,
          wallet_name
        )
      VALUES(
        " . $_SESSION['tg-maintain']['selected-level'] . ",
        '{$_POST['wallet-cat']}',
        $new_wallet_id,
        '{$_POST['wallet-name']}'
      )"
        );

        $wallet_id = get_wallet_id()[0]['wallet_id'];
    }
    if (!empty($_FILES['cat-img'])) {
        $cat_img = uploadFiles($_FILES['cat-img'], "/tg_core_images/Level-" . $_SESSION['tg-maintain']['selected-level'] . "/" . $_POST['wallet-cat'] . "");
        if ($cat_img != '') {
            dbcmd(
                "UPDATE tg_wallets_cat
        SET icon_path = '$cat_img'
        WHERE id={$_POST['wallet-cat']} and
        level = {$_SESSION['tg-maintain']['selected-level']}"
            );
        }
    }
    // update wallet name
    if (!empty(trim($_POST['rename-wallet']))) {
        updateTg_w_l_table(
            'wallet_name',
            $_POST['rename-wallet']
        );
    }


    if (!empty($_FILES['wallet-icon'])) {
        $wallet_icon = uploadFiles($_FILES['wallet-icon'], "/tg_core_images/Level-" . $_SESSION['tg-maintain']['selected-level'] . "/" . $_POST['wallet-name'] . "");
        if ($wallet_icon != '') {
            updateTg_w_l_table(
                'icon_path',
                $wallet_icon
            );
        }
    }



    if (!empty(trim($_POST['capacity-of-wallet']))) {
        updateTg_w_l_table(
            'capacity',
            $_POST['capacity-of-wallet']
        );
        //also change tg_user_walllets;
        dbcmd("UPDATE tg_user_wallets JOIN users ON users.id = tg_user_wallets.user_id SET quantity = {$_POST['capacity-of-wallet']} WHERE quantity > {$_POST['capacity-of-wallet']} && users.level = {$_SESSION['tg-maintain']['selected-level']} && wallet_id=$wallet_id");
    }



    $_SESSION['message']['success'] = "Affected wallet: " . $_POST['wallet-name'] . " on  Level: " . $_SESSION['tg-maintain']['selected-level'] . ".";
    header("location:game-maintain.php");
    exit;
}




// ************************************

if (isset($_POST['delete-wallet'])) {
    validationCheck(array($_POST['wallet-cat'], $_POST['wallet-name']));

    if (!empty(get_wallet_id()[0]['wallet_id'])) {
        $wallet_id = get_wallet_id()[0]['wallet_id'];
    } else {
        $_SESSION['message']['error'] = "This wallet is not even exist!";
        header("location:game-maintain.php");
        exit;
    }

    //water xpoints witch can not be added

    if (in_array($_POST['wallet-cat'], $default_wallets)) {
        $_SESSION['message']['error'] = "This wallet can not be deleted! $wallet_id";
        header("location:game-maintain.php");
        exit;
    }


    //delete all wallet from 1 sub cat
    if ($_POST['wallet-name'] == "*") {
        if (dbcmd("DELETE from tg_wallets WHERE cat_id=" . $_POST['wallet-cat'] . "")) {
            $_SESSION['message']['success'] = "All wallets from 'main_cat: " . $_POST['wallet-cat'] . "' -> 'wallet_cat: " . $_POST['wallet-cat'] . "' have been deleted!";
            header("location:game-maintain.php");
            exit;
        }
    }

    //delete selected post from seletecd category
    if ($_POST['wallet-name'] != "*") {

        if (dbcmd("DELETE FROM tg_wallets WHERE id=$wallet_id and cat_id=" . $_POST['wallet-cat'] . "")) {
            $_SESSION['message']['success'] = "Wallet Name: '" . $_POST['wallet-name'] . "' has been deleted!";
            header("location:game-maintain.php");
            exit;
        }
    }
}
//a--------------------------------



//setting the time of the water convert as XPoints by bees 
if (isset($_POST['set-water-to-xp-time'])) {
    $unix_minutes = $_POST['water-to-xp-time'] * 60;

    if (dbcmd("INSERT INTO  tg_maintain (level,water_to_xp_time) VALUES 
    (
      " . $_SESSION['tg-maintain']['selected-level'] . ",
      $unix_minutes
    )
    ON DUPLICATE KEY UPDATE
    water_to_xp_time = $unix_minutes
    ")) {
        header("location:game-maintain.php");
        $_SESSION['message']['success'] = "Water to XP Time has been set to UNIX: $unix_minutes\n==({$_POST['water-to-xp-time']} Minutes)";
        exit;
    }
}

// exchange-rates
if (isset($_POST['set-exchange-rate'])) {
    validationCheck(array($_POST['wallet-1'], $_POST['wallet-2'], $_POST['exchange-rate']));
    if ($_POST['wallet-1'] == $_POST['wallet-2']) {
        $_SESSION['message']['error'] = "Can not set convertation between same wallets!!";
        header("location:game-maintain.php");
        exit;
    }
    // check already available, if yes update it


    dbcmd("INSERT INTO tg_wallet_exchange_rates
  (
    level,
    wallet_1_id,
    wallet_2_id,
    wallet_2_rate
  ) 
  VALUES(
    " . $_SESSION['tg-maintain']['selected-level'] . ",
    " . $_POST['wallet-1'] . ",
    " . $_POST['wallet-2'] . ",
    " . $_POST['exchange-rate'] . "
  ) 
  ON DUPLICATE KEY UPDATE 
    wallet_2_rate = " . $_POST['exchange-rate'] . "
  ");


    $_SESSION['message']['success'] = "Exchange Rates are saved!";
    header("location:game-maintain.php");
    exit;
}



if (isset($_POST['change_tg_elements_image'])) {
    $tg_element_img = uploadFiles($_FILES['tg_element_img'], "/tg_core_images/Level-" . $_SESSION['tg-maintain']['selected-level'] . "/_main_elements");
    if (dbcmd("INSERT INTO tg_main_elements(
    level,
    element_id,
    name,
    image_path
  ) 
  SELECT
    " . $_SESSION['tg-maintain']['selected-level'] . ",
    " . $_POST['tg_element'] . ",
    name,
    '$tg_element_img' 
  FROM
    tg_main_elements_temp
  WHERE
    id = " . $_POST['tg_element'] . "
    
  ON DUPLICATE KEY UPDATE
  image_path = '$tg_element_img'
  ")) {
        $_SESSION['message']['success'] = "tg_element image updated!";
        header("location:game-maintain.php");
        exit;
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

        <!-- display message -->
        <div class="body-widget">
            <?php require('../assets/php/response.php'); ?>
        </div>

        <!-- select level for adjust all settings -->

        <!-- level-keeper -->
        <form class="body-widget" action="" method="POST">
            <div class="input-group">
                <h4>All setting will be applied for selected level</h4>
                <select class="form-control" name="level" id="">
                    <option value="" disabled selected>Select Level</option>
                    <?php
                    for ($i = 1; $i < 101; $i++) {
                        if ($_SESSION['tg-maintain']['selected-level'] == $i) {
                            echo "
            <option value='$i' selected>Level $i</option>
            ";
                        } else {
                            echo "
            <option value='$i'>Level $i</option>
            ";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="input-group">
                <button name="select-tg-level" class="btn">continue</button>
            </div>
        </form>

        <style>
            .ss-preview {
                width: 50px;
                height: 50px;
                object-fit: cover;
                padding: 0
            }
        </style>
        <form class="body-widget" action="" method="POST" enctype="multipart/form-data">
            <div class="title">Story Slides</div>
            <button class="btn" onclick="newStorySlide()" type="button">+</button>
            <button class="btn" onclick="removeStorySlide()" type="button">-</button>
            <div class="input-group form-content scroll scroll-x" id="new-ss-container">
                <?php
                $story_slides = dbget("SELECT * FROM tg_story_slides ORDER BY slideNo desc");
                foreach ($story_slides as $story_slide) {
                    echo <<<EOT
                        <label for="story_slide_{$story_slide['slideNo']}">
                        <img src="{$story_slide['img']}" id="preview-story-slide-{$story_slide['slideNo']}" class="btn ss-preview">
                        </label>
                        <input type="file" name="story_slide_{$story_slide['slideNo']}" id="story_slide_{$story_slide['slideNo']}" onchange="readURL(this,'preview-story-slide-{$story_slide['slideNo']}');">
                        EOT;
                }
                ?>


                <input type='number' value=<?php
                                            if (!empty($story_slides)) echo $story_slides[0]['slideNo'];
                                            else echo 0;
                                            ?> id='ss-count' name='ss-count' style='display:none'>
            </div>
            <script>
                var count = parseInt($("#ss-count").val());

                if (isNaN(count)) count = 0;

                function newStorySlide() {
                    if (count < 0) count = 1;
                    else count += 1;
                    $("#new-ss-container").prepend(`
                <label for="story_slide_${count}">
                    <img src="/assets/images/plus.png" id="preview-story-slide-${count}" class="btn ss-preview">
                </label>
                <input type="file" name="story_slide_${count}" id="story_slide_${count}" onchange="readURL(this,'preview-story-slide-${count}')">
              `);

                    $("#ss-count").val(count);
                    console.log($("#ss-count").val());

                }

                function removeStorySlide() {

                    if (count <= 0) {
                        return
                    }
                    $('#story_slide_' + count).remove();
                    $('#preview-story-slide-' + count).remove();
                    count -= 1;
                    $("#ss-count").val(count);
                    console.log($("#ss-count").val());

                }
            </script>
            <div class="input-group">
                <button name="config_story_slides" class="btn">Apply</button>
            </div>
        </form>



        <form class="body-widget" action="" method="post" enctype="multipart/form-data">
            <h3 class="title">Create TG Wallets and Modify it..</h3>

            <div class="input-group">
                <label>TG wallets category</label>
                <div class="form-content">
                    <select name="wallet-cat" onchange="changeWallet(this);" id="wallet-cat">
                        <option value="" selected disabled>Select Category</option>
                        <?php
                        $tg_wallet_cat_table = dbget(
                            "SELECT 
                  *
                FROM 
                  tg_wallets_cat where level={$_SESSION['tg-maintain']['selected-level']}
                "
                        );
                        if (!empty($tg_wallet_cat_table)) {
                            foreach ($tg_wallet_cat_table as $tg_wallet_cat_table) {
                                $cat_name = $tg_wallet_cat_table['name'];
                                $cat_id = $tg_wallet_cat_table['id'];
                                echo
                                <<<EOT
                  <option value="$cat_id" >$cat_name</option>
                  EOT;
                            }
                        }
                        ?>
                    </select>
                    <label for="cat-img" class="btn" style="width: 15%;">
                        <img src="/assets/images/584abf102912007028bd9332.png" alt="">
                    </label>
                    <input type="file" name="cat-img" id="cat-img" accept="image/*">
                </div>
            </div>

            <div class="input-group">
                <label>Wallet</label>
                <div class="form-content">
                    <select name="wallet-name" id="wallet-name">
                        <option value="" selected disabled>Select Wallet</option>
                        <script>
                            function changeWallet(element) {
                                $.post('./game_m_ajax.php', {
                                        changeWallet: true,
                                        selected_wallet_cat: element.value
                                    },
                                    function(data) {
                                        var data = $.parseJSON(data);
                                        for (var i = 0; i < data.wallet_names.length; i++) {
                                            document.getElementById('wallet-name').innerHTML = data.wallet_names;
                                        }

                                    }
                                );
                            }
                        </script>
                    </select>
                    <button type="button" class="btn" onclick="openAdd(this,'wallet-name');"><i class="fa fa-plus" aria-hidden="true"></i></button>
                </div>

                <div class="form-content" style="display: none;">
                    <input type="text" placeholder="Enter new wallet name">
                    <button type="button" class="btn" onclick="cancelAdd(this,'wallet-name');"><i class="fa fa-times" aria-hidden="true"></i></button>
                </div>
            </div>



            <div class="input-group">
                <input type="text" name="rename-wallet" id="" placeholder="Rename Wallet">
            </div>
            <div class="input-group form-content">
                <h5>Set wallet icon:</h5>
                <label for="wallet-icon" class="btn"><img src="/assets/images/584abf102912007028bd9332.png" style="width:40px" alt=""></label>
                <input type="file" name="wallet-icon" id="wallet-icon" accept="image/*">
            </div>
            <div class="input-group">
                <input type="number" name="capacity-of-wallet" placeholder="Enter capacity of this wallet" id="">
            </div>

            <div class="article-btns">
                <button class="btn" name="modify-wallet">Modify</button>
                <button class="btn" name="delete-wallet">Delete</button>
                <button type="button" class="btn" onclick="make_me_rich()">Rich</button>
            </div>
        </form>





        <!-- changing images of game entities -->

        <form class="body-widget" action="" method="POST" enctype="multipart/form-data">
            <div class="title">adjust files for main TG elements</div>
            <div class="input-group form-content">
                <select name="tg_element" id="" required>
                    <option value="" disabled selected>select element</option>
                    <?php foreach (dbget("SELECT id,name FROM tg_main_elements_temp") as $tg_element) {
                        echo "<option value='{$tg_element['id']}'>{$tg_element['name']}</option>";
                    } ?>

                </select>
                <label for="tg_element_img" class="btn">choose file</label>
                <input type="file" name="tg_element_img" id="tg_element_img">
            </div>
            <div class="input-group">
                <button name="change_tg_elements_image" class="btn">Apply</button>
            </div>
        </form>


        <!-- water-to-xp time set by admin -->

        <form class="body-widget" action="" method="POST">
            <div class="input-group">
                <div class="h5">Set water to XP time in minutes</div>
                <input class="form-control" type="number" name="water-to-xp-time" id="" placeholder="Time in minutes" required value="<?php echo dbget("SELECT water_to_xp_time FROM tg_maintain WHERE level =" . $_SESSION['tg-maintain']['selected-level'] . "")[0]['water_to_xp_time'] / 60 ?>">
            </div>
            <div class="input-group">
                <button name="set-water-to-xp-time" class="btn">Apply</button>
            </div>
        </form>

        <!-- exchange -rates -->

        <form class="body-widget" action="" method="POST">
            <div class="input-group">
                <h3>TG wallets exchange rates.</h3>
                <p>This convertation will be stored as 1 wallet_1 = ? wallet_2</p>
                <select class="form-control" name="wallet-1" id="">
                    <option value="" disabled selected>Select wallet 1</option>
                    <?php
                    $tg_wallets = dbget("SELECT wallet_id,wallet_name FROM tg_wallets_levels WHERE level=" . $_SESSION['tg-maintain']['selected-level'] . "");
                    foreach ($tg_wallets as $tg_wallet) {
                        echo "
            <option value='{$tg_wallet['wallet_id']}'>{$tg_wallet['wallet_name']}</option>
            ";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <select class="form-control" name="wallet-2" id="">
                    <option value="" disabled selected>Select wallet 2</option>
                    <?php
                    foreach ($tg_wallets as $tg_wallet) {
                        echo "
            <option value='{$tg_wallet['wallet_id']}'>{$tg_wallet['wallet_name']}</option>
            ";
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <input class="form-control" type="number" name="exchange-rate" id="" placeholder="Enter exchange rate">
            </div>
            <div class="input-group">
                <button name="set-exchange-rate" class="btn">Apply</button>
            </div>
        </form>


        <div class="body-widget">
            <div class="title">Submited Wish Tickets</div>
            <?php
            $submited_wish_tickets = dbget(
                "SELECT
          tg_user_won.id as request_id,
          users.id as user_id,
          users.image,
          tg_user_won.created_at,
          tg_wallets_levels.wallet_name,
          tg_wallets_levels.icon_path
        FROM 
          tg_user_won
        JOIN users ON
          tg_user_won.user_id = users.id
        JOIN tg_wallets_levels ON
          tg_user_won.wish_t_id = tg_wallets_levels.wallet_id and
          users.level = tg_wallets_levels.level
        WHERE tg_user_won.updated_at IS NULL
        "
            );
            if (!empty($submited_wish_tickets)) {
                foreach ($submited_wish_tickets as $submited_wt) {

            ?>
                    <div class="new-feeds-content">
                        <div class="content-thumbnail">
                            <a href="/user?<?php echo $submited_wt['user_id'] ?>">
                                <img src="<?php echo $submited_wt['image'] ?>" alt="">
                            </a>
                        </div>
                        <div class="content-desc">
                            <h4><?php echo $submited_wt['wallet_name'] ?></h4>
                        </div>
                        <button class="remove-content btn" onclick="wtApproved(this,<?php echo $submited_wt['request_id'], ',',
                                                                                    $submited_wt['user_id']
                                                                                    ?>,1)">
                            <i class="fa fa-check" aria-hidden="true"></i>
                        </button>

                        <button class="remove-content btn" onclick="wtApproved(this,
        <?php echo $submited_wt['request_id'], ',',
                    $submited_wt['user_id']
        ?>,0)">
                            <i class="fas fa-ban"></i>
                        </button>

                    </div>

            <?php }
            } else {
                echo "<h5>No Requsts are submited yet!!</h5>";
            } ?>
        </div>
        <?php if (isset($_POST['eval-player'])) {
            dbcmd(
                "UPDATE users
        SET level = {$_POST['level']}
        where id= {$_POST['tg-player']}"
            );
        } ?>
        <form action="" class="body-widget" method="post">
            <div class="input-group">
                <h5>upgrade player.</h5>
                <select name="tg-player" id="" required>
                    <?php foreach (dbget("SELECT * FROM users") as $user) {
                        echo "<option value='{$user['id']}'>{$user['username']}</option>";
                    } ?>

                </select>
                <input type="number" name="level" required placeholder="Level of the player">
                <button class="btn" name="eval-player">change</button>
            </div>
        </form>

    </div>
</body>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>