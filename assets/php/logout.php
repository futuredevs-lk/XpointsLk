<?php
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");
session_destroy();
header('location: /');
require("db/dbclose.php");
exit();
?>