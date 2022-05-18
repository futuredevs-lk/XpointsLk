<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
if(!isset($_SESSION['user'])){
  header("location:/login");
  exit;
}


if (isset($_POST['update-profile'])) {
    $validation = 1;
    if($_POST['password1'] != $_POST['password2']){
        $validation = 0;
        $_SESSION['message']['error'] = "Passwords are not matched!";
    }else{
      if(!empty($_POST['password1'])){
        $password = md5($_POST['password1']);
      }else{
        $password = $_SESSION['user']['password'];
      }
    }


    if(isset($_FILES['profile-image'])){
      $profile_image = uploadFiles($_FILES['profile-image']);
    }
    if($profile_image ==''){
      $profile_image = $_SESSION['user']['image'];
    }

    
    if($validation == 1){
        if(mysqli_query($db,"UPDATE users
          SET
          fname = '".$_POST['fname']."',
          lname = '".$_POST['lname']."',
          username = '".$_POST['username']."',
          password ='$password',
          email = '".$_POST['email']."',
          image = '$profile_image',
          address ='".$_POST['address']."',
          birthdate = '".$_POST['birthdate']."',
          district = '".$_POST['district']."',
          phone = '".$_POST['phone']."'
        
         WHERE id=".$_SESSION['user']['id']." limit 1"
        )){
          $_SESSION['message']['success'] = "Profile has been successfully updated!";
          $_SESSION['user'] = dbget("SELECT * FROM users WHERE id = '".$_SESSION['user']['id']."'")[0];

        }else{
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
    </head>
    <style>
    body {
      background-color: #e0f2f1;
    }
    
    form p {
      font-size: 120%;
    }
    
    .ctrlqHeaderMast {
      height: 278px;
      background: #009688;
    }
    
    .ctrlqFormContent {
      color: rgba(0,0,0,.87);
      padding: 20px 35px
    }
    
    .ctrlqFormContentWrapper {
      display: -webkit-box;
      display: -moz-box;
      display: -webkit-flex;
      display: -ms-flexbox;
      display: flex;
      -webkit-box-orient: vertical;
      box-orient: vertical;
      -webkit-flex-direction: column;
      flex-direction: column
    }
    
    .ctrlqAccent {
      background-color: #a7ffeb;
      height: 8px;
    }
    .ctrlqCenteredContent {
      margin: auto;
      width: 600px; 
    }
    .ctrlqFormCard {
      background-color: #fff;
      margin-bottom: 48px;
      -webkit-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.37);
      box-shadow: 0 1px 4px 0 rgba(0,0,0,0.37);
      word-wrap: break-word
    }
    
    .ctrlqFormCard:first-of-type {
      margin-top: -100px
    }
    
    .ctrlqHeaderTitle {
      -webkit-box-sizing: border-box;
      box-sizing: border-box;
      font-size: 34px;
      line-height: 135%;
      max-width: 100%;
      min-width: 0%;
      margin-bottom: 22px
    }
    
    @media (max-width: 660px) {
      .ctrlqHeaderMast {
        height: 122px;
      }
      .ctrlqFormCard:first-of-type {
        margin-top: -50px;
      }
    
      .ctrlqCenteredContent {
        width: 90%;
      }
    }
    
    div.error {
      position: relative;
      top: -1rem;
      left: 0rem;
      font-size: 0.8rem;
      color: #FF4081;
      -webkit-transform: translateY(0%);
      -ms-transform: translateY(0%);
      -o-transform: translateY(0%);
      transform: translateY(0%);
    }
    </style>
    <body>
        <div class="ctrlqFormContentWrapper">
        <div class="ctrlqHeaderMast">
        </div>
        <div class="ctrlqCenteredContent">
            <div class="ctrlqFormCard">
            <div class="ctrlqAccent"></div>
            <div class="ctrlqFormContent">

            
              <img style="width: 20%;max-height: 100%;border-radius:50%;" src="<?php echo  $_SESSION['user']['image']?>" alt="">
                <form action="" method="post" enctype="multipart/form-data">    
    
                <div class="row">
                    <div class="input-field col s12">
                    <h4>Hey, <?php echo $_SESSION['user']['username']?></h4>
                    <p>Update your personal info.</p>
                    <?php require('../assets/php/response.php'); ?>

                    </div>
                </div>
    



                <div class="row">
                  <div class="input-field col s12">
                  <input id="fname" name="fname" type="text" class="validate" data-error="#e1" value="<?php echo $_SESSION['user']['fname'];?>">
                  <label for="fname">First Name</label>
                  <div id="e1"></div>
                  </div>
              </div>
  
              <div class="row">
                  <div class="input-field col s12">
                  <input id="lname" name="lname" type="text" class="validate" data-error="#lnameE" value="<?php echo $_SESSION['user']['lname'];?>">
                  <label for="lname">Last Name</label>
                  <div id="lnameE"></div>
                  </div>
              </div>
              <div class="row">
                  <div class="input-field col s12">
                  <input id="username" name="username" type="text" class="validate" data-error="#usernameE" value="<?php echo $_SESSION['user']['username'];?>" >
                  <label for="username">Username</label>
                  <div id="usernameE"></div>
                  </div>
              </div>
  
              <div class="row">
                  <div class="input-field col s12">
                  <input id="email" name="email" type="email" class="validate" data-error="#e2" value="<?php echo $_SESSION['user']['email'];?>">
                  <label for="email">Email</label>
                  <div id="e2"></div>
                  </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                <input id="password1" name="password1" type="password" class="validate" data-error="#e3" >
                <label for="password1">Change password</label>
                <div id="e3"></div>
                </div>
              </div>
              <div class="row">
                <div class="input-field col s12">
                <input id="password2" name="password2" type="password" class="validate" data-error="#e4" >
                <label for="password2">Confirm Password</label>
                <div id="e4"></div>
                </div>
              </div>
  
              <div class="row">
                <div class="file-field input-field col s12">
                <div class="btn">
                    <span>Browse</span>
                    <input type="file" data-error="#e8" name="profile-image">
                </div>
                <div class="file-path-wrapper">
                    <input class="file-path validate" type="text" placeholder="Add profile picture." value="<?php echo $_SESSION['user']['image'];?>">
                </div>
                <div class="input-field">
                    <div id="e8"></div>
                </div>
                </div>
            </div>

              <div class="row">
                <div class="input-field col s12">
                  <input id="phone" name="phone" type="number" class="validate" data-error="#e5" value="<?php echo $_SESSION['user']['number'];?>">
                  <label for="phone">Contact Number</label>
                  <div id="e5"></div>
                </div>
              </div>
  
              <div class="row">
                  <div class="input-field col s12">
                  <input id="address" name="address" type="text" class="validate" data-error="#e6" value="<?php echo $_SESSION['user']['address'];?>">
                  <label for="address">House Address</label>
                  <div id="e6"></div>
                  </div>
              </div>
  
              <div class="row">
                  <div class="input-field col s12">
                  <select id="district" name="district" class="validate" data-error="#e7"  onchange="districtSelected();" >  
                    <option disabled selected value="">Choose District</option> 
                    <option value="<?php echo $_SESSION['user']['district'];?>" selected><?php echo $_SESSION['user']['district'];?></option>      
                      <script>
                        Object.keys(areas).forEach((area)=>{
                            document.write(`<option value="${area}">${area}</option>`); });
                      </script>
                  </select>
                  <div id="e7"></div>
                  </div>
              </div>
              
              <div class="row">
                <div class="input-field col s12">
                <select id="city" name="city" class="validate" data-error="#e8">
                  <option value="<?php echo $_SESSION['user']['city'];?>" selected><?php echo $_SESSION['user']['city'];?></option>     
                  <option disabled selected value="">Choose City</option>
                </select>
                <div id="e8"></div>
                </div>
            </div>
  
            
  
              <div class="row">
                  <div class="input-field col s12">
                  <label for="birthdate">Date of Birth</label>
                  <input type="date" id="birthdate" class="datepicker" name="birthdate" data-error="#e9" value="<?php echo $_SESSION['user']['birthday'];?>">
                  <div id="e9"></div>
                  </div>
              </div>

                

                
                <div class="row">
                    <div class="input-field col m6 s12">
                    <button type="submit" class="waves-effect waves-light btn-large" name="update-profile"><i class="material-icons right" >update</i>Update Profie</button>
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
    
        <div class="fixed-action-btn" style="bottom: 45px; right: 24px;">
        <a class="btn-floating btn-large red" href="/" title="go home">
            <i class="large material-icons">home</i>
        </a>
        </div>
    
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/jquery.validate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.15.0/additional-methods.min.js"></script>
    
    </body>
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
    require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");?>