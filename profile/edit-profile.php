<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");
if (!isset($_SESSION['user'])) {
    header("location:/login");
    exit;
}


if (isset($_POST['update-profile'])) {
    $validation = 1;
    if ($_POST['password1'] != $_POST['password2']) {
        $validation = 0;
        $_SESSION['message']['error'] = "Passwords are not matched!";
        header("location:/profile");
        exit;
    } else {
        if (!empty($_POST['password1'])) {
            $password = md5($_POST['password1']);
        } else {
            $password = $_SESSION['user']['password'];
        }
    }


    if (isset($_FILES['profile-image'])) {
        $profile_image = uploadFiles($_FILES['profile-image'], "__user_uploads/user__" . $_SESSION['user']['username'] . "/profile-pic");
    }
    if ($profile_image == '') {
        $profile_image = $_SESSION['user']['image'];
    }


    if ($validation == 1) {
        if (mysqli_query(
            $db,
            "UPDATE users
          SET
          fname = '" . $_POST['fname'] . "',
          lname = '" . $_POST['lname'] . "',
          username = '" . $_POST['username'] . "',
          password ='$password',
          email = '" . $_POST['email'] . "',
          image = '$profile_image',
          address ='" . $_POST['address'] . "',
          birthdate = '" . $_POST['birthdate'] . "',
          district = '" . $_POST['district'] . "',
          city = '" . $_POST['city'] . "',
          phone = '" . $_POST['phone'] . "',
          theme = '{$_POST['theme']}'
        
         WHERE id=" . $_SESSION['user']['id'] . " limit 1"
        )) {
            $_SESSION['message']['success'] = "Profile has been successfully updated!";
            $_SESSION['user'] = dbget("SELECT * FROM users WHERE id = '" . $_SESSION['user']['id'] . "'")[0];
            header("location:/profile");
            exit;
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
    <title>Profile | xpoints-lk</title>
    <link rel="stylesheet" href="/assets/css/login-reg.css">
    <script src="/assets/js/area.js"></script>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php") ?>
    <style>
        form {
            all: unset
        }
    </style>

</head>

<body>
    <div class="ctrlqFormContentWrapper">
        <div class="ctrlqHeaderMast">
        </div>
        <div class="ctrlqCenteredContent">
            <div class="ctrlqFormCard">
                <div class="ctrlqAccent"></div>
                <div class="ctrlqFormContent">



                    <form action="" method="post" enctype="multipart/form-data">

                        <div class="row">
                            <div class="input-field col s12">
                                <h4>Hey, <?php echo $_SESSION['user']['username'] ?></h4>
                                <p>Update your personal info.</p>
                                <style>
                                    .success p {
                                        border-radius: 12px;
                                        background-color: rgba(55, 211, 55, 0.3);
                                        border: 1px solid green;
                                        align-items: center;
                                        justify-content: center;
                                        text-align: center;
                                        padding: 12px;
                                    }
                                </style>
                                <?php require('../assets/php/response.php'); ?>

                            </div>
                        </div>




                        <div class="row">
                            <div class="input-field col s12">
                                <input id="fname" name="fname" type="text" class="validate" data-error="#e1" value="<?php echo $_SESSION['user']['fname']; ?>" required>
                                <label for="fname">First Name</label>
                                <div id="e1"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="lname" name="lname" type="text" class="validate" data-error="#lnameE" value="<?php echo $_SESSION['user']['lname']; ?>">
                                <label for="lname">Last Name</label>
                                <div id="lnameE"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="username" name="username" type="text" class="validate" data-error="#usernameE" value="<?php echo $_SESSION['user']['username']; ?>" required>
                                <label for="username">Username</label>
                                <div id="usernameE"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="email" name="email" type="email" class="validate" data-error="#e2" value="<?php echo $_SESSION['user']['email']; ?>" required>
                                <label for="email">Email</label>
                                <div id="e2"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password1" name="password1" type="password" class="validate" data-error="#e3">
                                <label for="password1">Change password</label>
                                <div id="e3"></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12">
                                <input id="password2" name="password2" type="password" class="validate" data-error="#e4">
                                <label for="password2">Confirm Password</label>
                                <div id="e4"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="file-field input-field col s12">
                                <div>
                                    <p>Change Avatar</p>
                                    <label class="btn" for="edit-pr-img" style="width: 60px;height: 60px;border-radius:50%;padding:0;overflow:hidden;
                                    position:static;
                                    cursor:pointer">

                                        <img id="pr-img-prv" style="object-fit:cover;height:100%;" src="<?php echo  $_SESSION['user']['image'] ?>" alt="">
                                    </label>
                                    <input type="file" data-error="#e8" name="profile-image" id="edit-pr-img" onchange="readURL(this,'pr-img-prv')" accept="image/*">
                                </div>

                                <div class="input-field">
                                    <div id="e8"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="phone" name="phone" type="number" class="validate" data-error="#e5" value="<?php echo $_SESSION['user']['phone']; ?>">
                                <label for="phone">Contact Number</label>
                                <div id="e5"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <input id="address" name="address" type="text" class="validate" data-error="#e6" value="<?php echo $_SESSION['user']['address']; ?>">
                                <label for="address">House Address</label>
                                <div id="e6"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select id="district" name="district" class="validate" data-error="#e7">
                                    <?php if (isset($_SESSION['user']['district'])) { ?>
                                        <option value="<?php echo $_SESSION['user']['district']; ?>" selected><?php echo $_SESSION['user']['district']; ?></option><?php } ?>
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
                                    <?php if (isset($_SESSION['user']['city'])) { ?>
                                        <option value="<?php echo $_SESSION['user']['city']; ?>" selected><?php echo $_SESSION['user']['city']; ?></option><?php } ?>
                                </select>
                                <div id="e8"></div>
                            </div>
                        </div>



                        <div class="row">
                            <div class="input-field col s12">
                                <label for="birthdate">Date of Birth</label>
                                <input type="date" id="birthdate" class="datepicker" name="birthdate" data-error="#e9" data-value="<?php echo $_SESSION['user']['birthdate']; ?>">
                                <div id="e9"></div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col s12">
                                <select id="theme" name="theme" class="validate" data-error="#theme-e">
                                    <?php if (isset($_SESSION['user']['theme'])) { ?>
                                        <option value="<?php echo $_SESSION['user']['theme']; ?>" selected><?php echo $_SESSION['user']['theme']; ?></option><?php } ?>
                                    <option value="black-and-white">black-and-white</option>
                                    <option value="black-on-white">black-on-white</option>
                                    <option value="light-color">light-color</option>
                                    <option value="dark-color">dark-color</option>
                                </select>
                                <div id="theme-e"></div>
                            </div>
                        </div>


                        <div class="row">
                            <div class="input-field col m6 s12">
                                <button type="submit" class="waves-effect waves-light btn-large" name="update-profile"><i class="material-icons right">update</i>Update Profie</button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="input-field col m6 s12">
                                <a href="/assets/php/logout.php"><button type="button" class="waves-effect waves-light btn-large"><i class="material-icons right">logout</i>logout</button></a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>

</body>
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
            selectMonths: true,
            selectYears: 15
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