<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_SESSION['user'])) {
    header('location: /profile');
    exit();
};
$errors = "";
if (isset($_POST['login-form'])) {
    $password = md5(mysqli_real_escape_string($db, $_POST['password']));
    $email    = mysqli_real_escape_string($db, $_POST['email']);


    $user = dbget("SELECT * FROM users WHERE email = '$email' and password = '$password' limit 1");
    if (!empty($user)) {
        session_start();
        $_SESSION['user'] = $user[0];
        if (isset($_GET['redir'])) {
            header("location:/{$_GET['redir']}");
        } else if ($user[0]['admin'] == 1) {
            header("location:/admin/");
        } else {
            header("location:/profile/");
        }

        exit();
    } else {
        $errors = "INVALID CREDETIALS";
    }
}

?>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/login-reg.css">
    <title>Login to your account</title>
    <script src="/assets/js/area.js"></script>
    
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">
</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
    <style>
        form {
            all: unset;
            font-family:sans-serif;
        }
        .input-field label{
            color:white;
        }
    </style>
    <div class="ctrlqFormContentWrapper">
        <div class="ctrlqHeaderMast">
            <img src="<?php
                        echo $site_elements[13]['img'];
                        ?>" />
        </div>
        <div class="ctrlqCenteredContent">
            <div class="ctrlqFormCard">
                <div class="ctrlqAccent"></div>
                <div class="ctrlqFormContent">

                    <form action="" method="post">
                        <div class="row">
                            <div class="input-field col s12">
                                <h4>Welcome To XPOINTS.LK</h4>
                                <p>Login to get started..</p>
                                <p style="color:red;font-size:0.8rem;"><?php echo $errors; ?></p>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" class="validate" data-error="#e1" required>
                                <label for="name">Email Address</label>
                                <div id="e1"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password" name="password" type="password" class="validate" data-error="#e2" required>
                                <label for="email">Password</label>
                                <div id="e2"></div>
                            </div>
                        </div>


                        <style>
                            .waves-effect{
                                width:100%;
                            }
                        </style>
                        <div class="row">
                            <div class="input-field col s12">
                                <button name="login-form" type="submit" class="waves-effect waves-light btn-large">
                                    Login
                                </button>
                            </div>
                                                        
                            
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <button
                                    type="button"
                                    scope="public_profile,email"
                                    onclick="checkLoginState();"
                                    class="waves-effect waves-light btn-large"
                                >
                                    Login with Facebook
                                </button>
                            </div>
                                                        
                            
                        </div>
                        
                    </form>
                    
                    <div class="row">
                        <div class="input-field col s12">
                            <a class="waves-effect waves-light btn-large" href="/register/" style="text-decoration:none">
                                Create an Account
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>





</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>

</html>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>