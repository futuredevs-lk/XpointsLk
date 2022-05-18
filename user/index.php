<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (!isset($_SESSION['user'])) {
    header("location:/login");
    exit;
}
if ($_SESSION['user']['admin'] == 0) {
    header("location:/pages/not-an-admin.html");
    exit;
}
if (isset($_GET['id'])) {
    if (empty($_GET['id'])) {
        exit;
    }
    $user = dbget(
        "SELECT *
        FROM users
        WHERE id = {$_GET['id']}"
    );
    if (empty($user[0])) {
        echo "no_data found";
        exit;
    } else {
        $user = $user[0];
    }
} else {
    $user = $_SESSION['user'];
}


?>


<!DOCTYPE html>
<html>
<title>Profile | XpointsLk</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
    html,
    body,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Roboto", sans-serif
    }
</style>
<?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
<style>
    body {
        padding-top: 10vh
    }
</style>

<body class="w3-light-grey">

    <!-- Page Container -->
    <div class="w3-content w3-margin-top">

        <!-- The Grid -->
        <div class="w3-row-padding">

            <!-- Left Column -->
            <div class="w3-col">

                <div class="w3-white w3-text-grey w3-card-4">
                    <div class="w3-display-container">
                        <img src="<?php echo $user['image']; ?>" style="width:100%" alt="Avatar">
                        <div class="w3-display-bottomleft w3-container w3-text-black">
                        </div>
                    </div>
                    <div class="w3-container">
                        <a href="edit-profile.php">
                            <p><i class="fa fa-pencil fa-fw w3-margin-right w3-large w3-text-teal" aria-hidden="true"></i> Edit Profile</p>
                        </a>
                        <p><i class="fa fa-person fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo "{$user['fname']} {$user['lname']}"; ?></p>
                        <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo "{$user['district']},{$user['city']}"; ?></p>
                        <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $user['email']; ?></p>
                        <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $user['phone']; ?></p>
                        <hr>

                        <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-teal"></i>Wallets</b></p>
                        <p>Xpoints</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:90%">90%</div>
                        </div>
                        <p>Gold</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:80%">
                                <div class="w3-center w3-text-white">80%</div>
                            </div>
                        </div>
                        <p>Copper</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:75%">75%</div>
                        </div>
                        <p>Bronze</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:50%">50%</div>
                        </div>
                        <br>

                        <p class="w3-large w3-text-theme"><b><i class="fa fa-globe fa-fw w3-margin-right w3-text-teal"></i>Level</b></p>
                        <p>current level</p>
                        <div class="w3-light-grey w3-round-xlarge">
                            <div class="w3-round-xlarge w3-teal" style="height:24px;width:100%"></div>
                        </div>
                        <br>
                    </div>
                </div><br>

                <!-- End Left Column -->
            </div>

        </div>

        <!-- End Page Container -->
    </div>

    <footer class="w3-container w3-teal w3-center w3-margin-top">
        <p>Find me on social media.</p>
        <i class="fa fa-facebook-official w3-hover-opacity"></i>
        <i class="fa fa-instagram w3-hover-opacity"></i>
        <i class="fa fa-snapchat w3-hover-opacity"></i>
        <i class="fa fa-pinterest-p w3-hover-opacity"></i>
        <i class="fa fa-twitter w3-hover-opacity"></i>
        <i class="fa fa-linkedin w3-hover-opacity"></i>
    </footer>

</body>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>