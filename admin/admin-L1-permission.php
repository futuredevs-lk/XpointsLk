<?php

if ($_SESSION['user']['admin'] != 2) {
    $_SESSION['message']['error'] = "Access Denied!!!";
    header("location:/admin/");
    exit;
}
