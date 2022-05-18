<!DOCTYPE html>
<html lang="en">

<head>
    <?php require("./admin-header.php") ?>
    <title>xpointsLK - PlantFree</title>

</head>

<body>



    <div class="container-fluid">
        <div class="row content">
            <!-- <div class="col-sm-3 sidenav hidden-xs" style="height: 100vh;">
      <a href="/">
      <h2>xpoints.LK</h2>
      </a>
      <ul class="nav nav-pills nav-stacked">
        <li class="active"><a href="./article-maintain.php">Customize Articles</a></li>
            <li><a href="./post-maintain.php">Customize Posts</a></li>
            <li><a href="./shop-maintain.php">Shop Maintain</a></li>
            <li><a href="#">Tree Game</a></li>
      </ul><br>
    </div> -->
            <br>

            <div class="col-sm-12">
                <?php if ($_SESSION['user']['admin'] == 2) { ?>
                    <h4>Admins</h4>
                    <div class="col-sm-6 well" style="word-wrap:break-word;width:100%;overflow:auto;max-height: 40vh;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Username</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $admins = dbget("SELECT * FROM users WHERE admin!=0");
                                foreach ($admins as $admin) {
                                    echo '
                            <tr>
                                <td>' . $admin['id'] . '</td>
                                <td>' . $admin['username'] . '</td>
                            </tr>
                        ';
                                } ?>


                            </tbody>
                        </table>


                    </div>


                    <h4>Users</h4>
                    <div class="well" style="word-wrap:break-word;width:100%;overflow:auto;max-height: 40vh;">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>Username</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>D.O.B</th>
                                    <th>Address</th>
                                    <th>District</th>
                                    <th>City</th>
                                    <th>created_at</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = dbget("SELECT * FROM users");
                                foreach ($users as $user) {
                                    echo '
                            <tr>
                                <td>' . $user['id'] . '</td>
                                <td>' . $user['fname'] . '</td>
                                <td>' . $user['lname'] . '</td>
                                <td>' . $user['username'] . '</td>
                                <td>' . $user['email'] . '</td>
                                <td>' . $user['phone'] . '</td>
                                <td>' . $user['birthdate'] . '</td>
                                <td>' . $user['address'] . '</td>
                                <td>' . $user['district'] . '</td>
                                <td>' . $user['city'] . '</td>
                                <td>' . $user['created_at'] . '</td>
                            </tr>
                        ';
                                } ?>


                            </tbody>
                        </table>


                    </div>



                    <div class="row">
                        <div class="col-sm-6">

                            <div class="well">
                                <select class="form-control" name="select-user" id="select-user">
                                    <option value="" selected disabled>Select user</option>
                                    <?php foreach ($users as $user) {
                                        echo '<option value=' . $user['id'] . '>ID:' . $user['id'], " ", $user['username'] . '</option>';
                                    } ?>
                                </select>

                                <div class="form-content" style="margin-top: 2%;">
                                    <button class="btn btn-success mr-2" onclick="user_maintain(1)">UP</button>
                                    <button class="btn btn-warning mr-2" onclick="user_maintain(0)">DOWN</button>
                                    <button class="btn btn-danger ml-2" onclick="user_maintain(-1)">OUT</button>
                                </div>
                            </div>

                        </div>
                    <?php } else {
                    echo "<div style='text-align:center'><h1 class='warning' style='color:red;'>";
                    require('../assets/php/response.php');
                    echo "</h1><h2>You are a Level 1 admin.</h2></div>";
                } ?>

                    <div class="col-sm-6">
                        <div class="well">
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
                                } else {
                                    echo "<p>The Products submited by users will apear here..</>";
                                } ?>
                            </div>
                        </div>
                    </div>






                    <div class="col-sm-6">
                        <div class="well">
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
                                } else {
                                    echo "<p>The posts submited by users will apear here..</p>";
                                } ?>
                            </div>

                        </div>
                    </div>




                    </div>
            </div>
        </div>

</body>
<script>
    function user_maintain(admin) {
        var user_id = $('#select-user').val();
        if (user_id == null) {
            alert("select user first!!");
            return;
        }
        if (admin == -1) {
            if (!(confirm("Are you sure do want to block this user?"))) {
                return;
            }
        }
        $.post('ajax-response.php', {
            user_maintain: true,
            user_admin: admin,
            user_id: user_id
        }, function(response) {
            alert(response)
            location.reload()
        })
    }
</script>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>