<?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php"); ?>
<!DOCTYPE html>
<html lang="en">


<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }

        /* Style the body */
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* headder/logo Title */
        .headder {
            padding: 80px;
            text-align: center;
            background: #1abc9c;
            color: white;
        }

        /* Increase the font size of the heading */
        .headder h1 {
            font-size: 40px;
        }

        /* Sticky navbar - toggles between relative and fixed, depending on the scroll position. It is positioned relative until a given offset position is met in the viewport - then it "sticks" in place (like position:fixed). The sticky value is not supported in IE or Edge 15 and earlier versions. However, for these versions the navbar will inherit default position */
        .navbar {
            overflow: hidden;
            background-color: #333;
            position: sticky;
            position: -webkit-sticky;
            top: 0;
        }

        /* Style the navigation bar links */
        .navbar a {
            float: left;
            display: block;
            color: white;
            text-align: center;
            padding: 14px 20px;
            text-decoration: none;
        }


        /* Right-aligned link */
        .navbar a.right {
            float: right;
        }

        /* Change color on hover */
        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Active/current link */
        .navbar a.active {
            background-color: #666;
            color: white;
        }

        /* Column container */
        .row {
            display: -ms-flexbox;
            /* IE10 */
            display: flex;
            -ms-flex-wrap: wrap;
            /* IE10 */
            flex-wrap: wrap;
        }

        /* Create two unequal columns that sits next to each other */
        /* Sidebar/left column */
        .side {
            -ms-flex: 30%;
            /* IE10 */
            flex: 30%;
            padding: 20px;

            margin: auto;
        }

        /* Main column */
        .main {
            -ms-flex: 70%;
            /* IE10 */
            flex: 70%;
            background-color: white;
            padding: 20px;
        }

        /* Fake image, just for this example */
        .fakeimg {
            background-color: #aaa;
            width: 100%;
            padding: 20px;
        }
    </style>
    <?php include($_SERVER["DOCUMENT_ROOT"] . "/assets/php/nav.php"); ?>
</head>

<body class="desk-mw">

    <div class="headder desk-mw">
        <h1>ABOUT US</h1>
    </div>



    <div class="row">
        <div class="side desk-mw">
            <h2>OUR STORY</h2>
            <p>An Educational Website with PlantFree Game and Live chat feature..</p>
            <br>
            <h3 style="text-align:center;">CONTACT US</h3>
            <div class="fakeimg" style="height:60px;"><i style="padding-right:10px;" class="fa fa-phone"></i>0775449598</div><br>
            <div class="fakeimg" style="height:60px;"><i style="padding-right:10px;" class="fa fa-send"></i>xpointsofficial@gmail.com</div><br>
            <div class="fakeimg" style="height:60px;"><i style="padding-right:10px;" class="fa fa-whatsapp"></i>0775449598</div><br>
            <div class="fakeimg" style="height:60px;"><i style="padding-right:10px;" class="fa fa-facebook"></i>Facebook/xpointslk</div><br>

        </div>
    </div>
</body>

</html>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>