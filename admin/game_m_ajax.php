<?php
require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/server.php");

//when onchange
if (isset($_POST['changeWallet'])) {
    $sendNames = '';
    $selectedWalletCat  = $_POST['selected_wallet_cat'];

    $wallet_names = dbget(
        "SELECT 
            wallet_name 
        FROM 
            tg_wallets_levels

        WHERE 
            level = " . $_SESSION['tg-maintain']['selected-level'] . " and 
            cat_id =$selectedWalletCat
        "
    );

    if (!empty($wallet_names)) {
        foreach ($wallet_names as $wallet_name) {
            $sendNames .= '
            <option 
            value="' . $wallet_name['wallet_name'] . '">
            ' . $wallet_name['wallet_name'] . '
            </option>';
        }
    } else {
        $sendNames = '
            <option value="" disabled selected>
            No wallet available</option>';
    }
    $Response = array('wallet_names' => $sendNames);
    echo json_encode($Response);
}


if (isset($_POST['approveOrDecline'])) {
    if ($_POST['cmd'] == "approve") {
        dbcmd("INSERT INTO post_maintain (
        main_cat_id,
        wallet_cat_id,
        uploaded_by,
        file,
        name,
        description) 
        SELECT 
        main_cat_id,
        wallet_cat_id,
        user_id,
        post_file,
        post_title,
        post_desc 
        FROM user_post WHERE post_id = '" . $_POST['id'] . "'");
        dbcmd("DELETE FROM user_post WHERE post_id = '" . $_POST['id'] . "'");
        echo "post-approved";
    } else if ($_POST['cmd'] == "decline") {
        dbcmd("DELETE FROM user_post WHERE post_id = '" . $_POST['id'] . "'");
        echo "post-declined";
    }
}

?>

<?php require($_SERVER["DOCUMENT_ROOT"] . "/assets/php/db/dbclose.php") ?>