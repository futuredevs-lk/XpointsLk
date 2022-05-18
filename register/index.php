<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_SESSION['user'])) {
    header('location: /profile');
    exit();
}
$sighnuperrors = "";
if (isset($_POST['form1'])) {
    $validation = 1;
    if ($_POST['password1'] != $_POST['password2']) {
        $validation = 0;
        $sighnuperrors = "Passwords are not matched<br>";
    }

    $sql = "SELECT * FROM users WHERE email = '" . $_POST['email'] . "' or username = '" . $_POST['username'] . "'";
    $result = $db->query($sql);
    if ($result->num_rows > 0) {
        $validation = 0;
        $sighnuperrors = "Email already exist.<br>";
    }

    if ($validation == 1) {
        $password = md5($_POST['password1']);
        if (mysqli_query(
            $db,
            "INSERT INTO users(
              fname,
              lname,
              username,
              password,
              email,
              address,
              birthdate,
              district,
              phone
            ) 
            VALUES (
              '" . $_POST['fname'] . "',
              '" . $_POST['lname'] . "',
              '" . $_POST['username'] . "',
              '$password',
              '" . $_POST['email'] . "',
              '" . $_POST['address'] . "',
              '" . $_POST['birthdate'] . "',
              '" . $_POST['district'] . "',
              '" . $_POST['phone'] . "'
            )"
        )) {
            session_start();
            $_SESSION['user'] = dbget("SELECT * FROM users WHERE username='" . $_POST['username'] . "' and email='" . $_POST['email'] . "' limit 1")[0];
            header('location: /profile/');
            if (isset($_POST['referal'])) dbcmd("INSERT INTO user_referals(referer,user) VALUES({$_POST['referal']},{$_SESSION['user']['id']})");
            exit();
        } else {
            die(mysqli_error($db));
        }
    }
}

?>


<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <title>Reserve An Account | XpointsLK</title>
    <meta name="keywords" content="XpointsLk">
    <meta name="description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:title" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:type" content="website">
    <meta property="og:url" content="https://xpoints.lk">
    <meta property="og:description" content="An Educational Website with PlantFree Game and Live Chat feature!!">
    <meta property="og:image" content="https://xpoints.lk/assets/images/xpoints.png" />
    <link rel="stylesheet" href="/assets/css/login-reg.css">


    <script src="https://www.lynkap.com/code/area.js"></script>
    <link rel="shortcut icon" href="/assets/images/xpoints.png" type="image/x-icon">

</head>

<body>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
    <style>
        form {
            all: unset;
            font-family:sans-serif;
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
                                <p>Let's Reserve an Account for You..</p>
                                <?php echo "<p style='color:red;'> $sighnuperrors</p>"; ?>
                            </div>
                        </div>

                        <?php
                        if (isset($_GET['ref_id'])) $ref_id = $_GET['ref_id'];
                        else $ref_id = '';
                        ?>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="referal" name="referal" type="text" class="validate" data-error="#e-referal" value="<?php echo $ref_id ?>">
                                <label for="fname">Referal ID</label>
                                <div id="e-referal"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="fname" name="fname" type="text" class="validate" data-error="#e1" required>
                                <label for="fname">First Name</label>
                                <div id="e1"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="lname" name="lname" type="text" class="validate" data-error="#lnameE">
                                <label for="lname">Last Name</label>
                                <div id="lnameE"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" class="validate" data-error="#usernameE" required>
                                <label for="username">Username</label>
                                <div id="usernameE"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" class="validate" data-error="#e2" required>
                                <label for="email">Email</label>
                                <div id="e2"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password1" name="password1" type="password" class="validate" data-error="#e3" required>
                                <label for="password1">Password</label>
                                <div id="e3"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password2" name="password2" type="password" class="validate" data-error="#e4" required>
                                <label for="password2">Confirm Password</label>
                                <div id="e4"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="number" class="validate" data-error="#e5">
                                <label for="phone">Contact Number</label>
                                <div id="e5"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="address" name="address" type="text" class="validate" data-error="#e6">
                                <label for="address">House Address</label>
                                <div id="e6"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select id="district" name="district" class="validate" data-error="#e7">
                                    <option disabled selected value="">Choose District</option>
                                    <script>
                                        Object.keys(areas).forEach((area) => {
                                            document.write(`<option value="${area}">${area}</option>`);
                                        });
                                    </script>
                                </select>
                                <div id="e7"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select id="city" name="city" class="validate" data-error="#e8">
                                    <option disabled selected value="">Choose City</option>
                                </select>
                                <div id="e8"></div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="input-field col s12">
                                <label for="birthdate">Date of Birth</label>
                                <input type="date" id="birthdate" class="datepicker" name="birthdate" data-error="#e9">
                                <div id="e9"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <button name="form1" type="submit" class="waves-effect waves-light btn-large" style="width:100%">Submit</button>
                            </div>
                        </div>

                    </form>
                    
                    <div class="row">
                            <div class="input-field col s12">
                                Already a member?
                                <a href="/login"  class="waves-effect waves-light btn-large" style="width:100%;text-decoration:none">
                                    Sign in   
                                </a>
                            </div>
                    </div>

                    

                </div>
            </div>
        </div>
    </div>

    <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
        <a class="btn-floating btn-large red" href="#top">Go Up</a>
    </div>



</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>

<script>
    $(function() {
        $('#district').change(function() {
            $('#city').material_select({
                destroy: true
            });
            var selecteddistrict = $('#district').val();
            $('#city').html('');
            for (area of areas[selecteddistrict]) {
                $('#city').append(`<option value="${area}">${area}</option>`);
            }
            $('#city').material_select();
        })
    })
</script>


<script>
    $(document).ready(function() {
        $('select').material_select();
        $('.datepicker').pickadate({
            max: new Date().getFullYear(),
            selectMonths: true,
            selectYears: 50
        });




        $.validator.setDefaults({
            ignore: []
        });
        $("form").validate({
            submitHandler: function(form) {
                console.log(form);
                return true;
            },
            errorElement: 'div',
            errorPlacement: function(error, element) {
                var placement = $(element).data('error');
                if (placement) {
                    $(placement).append(error)
                } else {
                    error.insertAfter(element);
                }
            }
        });

    });
</script>

</html>
<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php"); ?>