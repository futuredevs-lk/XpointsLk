<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

if (isset($_POST['remove_ad'])) {
    dbcmd("DELETE FROM vendor_manager WHERE id = {$_POST['id']}");
    echo "Ad ID:#{$_POST['id']} Removed!";
}




require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php");
