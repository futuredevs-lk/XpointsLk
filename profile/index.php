<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (!isset($_SESSION['user'])) {
    header("location:/login");
    exit;
}

$user = $_SESSION['user'];


// get user wallet for get quantity
$tg_user_wallets = dbget("SELECT * FROM tg_user_wallets WHERE user_id={$_SESSION['user']['id']}");

// get wallets get capacity
$tg_wallets     = dbget("SELECT * FROM tg_wallets_levels WHERE level ={$_SESSION['user']['level']} ORDER BY wallet_id");

// get max-capacity of wallets and calculate quantity of wallets by percentage
function calProgressPercentage($wallet)
{
    global $tg_user_wallets, $tg_wallets;
    return $tg_user_wallets[$wallet]['quantity'] / $tg_wallets[$wallet]['capacity'] * 100;
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


<body class="w3-light-grey">

    <!-- Page Container -->
    <div class="w3-content w3-margin-top">

        <!-- The Grid -->
        <div class="w3-row-padding">

            <!-- Left Column -->
            <div class="w3-col">

                <div class=" w3-text-grey w3-card-4" style="        padding-top: 12vh
;">
                    <div style="width: 180px;height:180px;margin:auto;border-radius:50%;overflow:hidden;background-color:var(--theme-color) !important">
                        <img src="<?php echo $user['image']; ?>" style="width:100%;height:100%;object-fit:cover;object-position:center;" alt="Avatar">
                        <div class="w3-display-bottomleft w3-container w3-text-black">
                        </div>
                    </div>
                    <div class="w3-container" style="background-color: var(--theme-color);">
                        <a href="edit-profile.php">
                            <p><i class="fa fa-pen fa-fw w3-margin-right w3-large w3-text-teal" aria-hidden="true"></i> Edit Profile</p>
                        </a>
                        <p><i class="fa fa-smile fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo "{$user['fname']} {$user['lname']}"; ?></p>
                        <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo "{$user['district']},{$user['city']}"; ?></p>
                        <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $user['email']; ?></p>
                        <p><i class="fa fa-phone fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $user['phone']; ?></p>
                        <hr>

                        <p class="w3-large"><b><i class="fa fa-asterisk fa-fw w3-margin-right w3-text-teal"></i>Wallets</b></p>
                        <p>Xpoints</p>
                        <div class="w3-light-grey w3-round-xlarge w3-small">
                            <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:<?php
                                                                                                        $xp_pr = calProgressPercentage(1);
                                                                                                        echo "{$xp_pr}%\">",
                                                                                                        $tg_user_wallets[1]['quantity'], "/", $tg_wallets[1]['capacity'] ?></div>
                        </div>
                        <p>Gold</p>
                        <div class=" w3-light-grey w3-round-xlarge w3-small">
                                <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:<?php
                                                                                                            $gold_pr = calProgressPercentage(2);
                                                                                                            echo "{$gold_pr}%\">",
                                                                                                            $tg_user_wallets[2]['quantity'], "/", $tg_wallets[2]['capacity'] ?></div>
                            
                        </div>
                        <p>Copper</p>
                        <div class=" w3-light-grey w3-round-xlarge w3-small">
                                    <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:<?php
                                                                                                                $copper_pr = calProgressPercentage(3);
                                                                                                                echo "{$copper_pr}%\">",
                                                                                                                $tg_user_wallets[3]['quantity'], "/", $tg_wallets[3]['capacity'] ?></div>
                        </div>
                        <p>Bronze</p>
                        <div class=" w3-light-grey w3-round-xlarge w3-small">
                                        <div class="w3-container w3-center w3-round-xlarge w3-teal" style="width:<?php
                                                                                                                    $bronze_pr = calProgressPercentage(4);
                                                                                                                    echo "{$bronze_pr}%\">",
                                                                                                                    $tg_user_wallets[4]['quantity'], "/", $tg_wallets[4]['capacity'] ?></div>
                        </div>
                        <br>

                        <p class=" w3-large w3-text-theme"><b><i class="fa fa-globe fa-fw w3-margin-right w3-text-teal"></i>Level</b></p>
                                            <p>current level: <?php echo $_SESSION['user']['level'] ?></p>
                                            <div class="w3-light-grey w3-round-xlarge">
                                                <div class="w3-round-xlarge w3-teal" style="height:24px;width:<?php echo $_SESSION['user']['level'] ?>%"></div>
                                            </div>
                                            <br>

                                        </div>
                                        <br>

                                    </div><br>
                                    <div class="body-widget" style="font-family: monospace;">
                                        <div class="body-widget">
                                            <p class=" w3-large w3-text-theme"><b><i class="fa fa-share fa-fw w3-margin-right w3-text-teal"></i>Referal</b></p>

                                            <div class="title">
                                                <?php
                                                $referal_link = "https://xpoints.lk/register/?ref_id={$_SESSION['user']['id']}";
                                                echo $referal_link ?>
                                            </div>

                                            <div class="title">
                                                <button class="btn" style="margin: auto;" onclick="share('XpointsLk Referal System.','Hey Buddy!!, I found something interesting on this website.\nJust take a look at it..','<?php echo $referal_link; ?>');">Share My Code >>></button>
                                            </div>

                                        </div>
                                        <div class="body-widget">
                                            <h3 class="title">My Network</h3>
                                            <table class="table">

                                                <thead>
                                                    <tr>
                                                        <th>Username</th>
                                                        <th>Joined On</th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    <?php
                                                    $my_network = dbget("SELECT username, user_referals.created_at FROM user_referals JOIN users ON users.id = user_referals.user WHERE user_referals.referer = {$_SESSION['user']['id']}");
                                                    if (!empty($my_network)) foreach ($my_network as $referal) {
                                                        echo "<tr>
                                                            <td>{$referal['username']}</td>
                                                            <td>{$referal['created_at']}</td>
                                                          </tr>";
                                                    }
                                                    else echo "<tr>You have no members in your network.<br>Refer more people to earn rewards!</tr>";
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
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