<?php 
require($_SERVER["DOCUMENT_ROOT"]."/assets/php/server.php");


//article-maintain.php
if(isset($_POST['selected_art_cat'])){
    $sendData = "";
    $articleMaintainTable = dbget("SELECT article_name FROM article_maintain WHERE cat_id = '".$_POST['selected_art_cat']."'");
    if(!empty($articleMaintainTable)){
        foreach($articleMaintainTable as $article_names){
            $sendData .='<option value="'.$article_names['article_name'].'">'.$article_names['article_name'].'</option>';
            
        }
    }
     echo($sendData);
}






//when onchange main category -> sub and names changes
if(isset($_POST['changeCat'])){
    $sendSubCats = "";
    $selectedMainCat = $_POST['selected_main_cat'];
    $sub_cats = dbget("SELECT sub_cat_id,sub_cat_name FROM post_category_sub WHERE main_cat_id=$selectedMainCat");

    foreach ($sub_cats as $sub_cat){
        $sendSubCats .='
        <option 
        main_cat_id="'.$_POST['selected_main_cat'].'" 
        sub_cat_id="'.$sub_cat['sub_cat_id'].'" 
        value="'.$sub_cat['sub_cat_name'].'">
        '.$sub_cat['sub_cat_name'].'
        </option>
        ';
        
    }
    $Response = array('sendSubCats' => $sendSubCats);
    echo json_encode($Response);
     
}
//when onchange sub ->only nmaes chnages
if(isset($_POST['changePost'])){
    $sendNames ='';
    $selectedMainCat = $_POST['selected_main_cat'];
    $selectedSubCat  = $_POST['selected_sub_cat'];
    $post_names = dbget("SELECT name FROM post_maintain WHERE main_cat_id=$selectedMainCat and sub_cat_id =$selectedSubCat");

    foreach($post_names as $post_name){
        $sendNames .='
        <option 
        value="'.$post_name['name'].'">
        '.$post_name['name'].'
        </option>';
    }
    $Response = array('post_names' => $sendNames);
    echo json_encode($Response);
}


if(isset($_POST['approveOrDecline'])){
    if($_POST['cmd']=="approve"){
        dbcmd("INSERT INTO post_maintain (
        main_cat_id,
        sub_cat_id,
        uploaded_by,
        file,
        name,
        description) 
        SELECT 
        main_cat_id,
        sub_cat_id,
        user_id,
        post_file,
        post_title,
        post_desc 
        FROM user_post WHERE post_id = '".$_POST['id']."'");
        dbcmd("DELETE FROM user_post WHERE post_id = '".$_POST['id']."'");
        echo "post-approved";
    }
    else if($_POST['cmd']=="decline"){
        dbcmd("DELETE FROM user_post WHERE post_id = '".$_POST['id']."'");
        echo "post-declined";
    }
}












/*----------------------SHOP-----------------------------*/
//when onchange main category -> sub and names changes
if(isset($_POST['changeShopCat'])){
    $shop_scs = "";
    $selectedMainCat = $_POST['selected_main_cat'];
    $sub_cats = dbget("SELECT id,cat_name FROM shop_sc WHERE mc_id=$selectedMainCat");
    

    foreach ($sub_cats as $sub_cat){
        $shop_scs .='
        <option 
        main_cat_id='.$selectedMainCat.' 
        sub_cat_id="'.$sub_cat['id'].'" 
        value="'.$sub_cat['cat_name'].'">
        '.$sub_cat['cat_name'].'
        </option>
        ';
        
    }
    $Response = array('shop_scs' => $shop_scs);
    echo json_encode($Response);
     
}
//when onchange sub ->only nmaes chnages
if(isset($_POST['changeProduct'])){
    $sendNames ='';
    $selectedMainCat = $_POST['selected_main_cat'];
    $selectedSubCat  = $_POST['selected_sub_cat'];
    $product_names = dbget("SELECT name FROM shop_product WHERE mc_id=$selectedMainCat and sc_id =$selectedSubCat");

    foreach($product_names as $product_name){
        $sendNames .='
        <option 
        value="'.$product_name['name'].'">
        '.$product_name['name'].'
        </option>';
    }
    $Response = array('product_names' => $sendNames);
    echo json_encode($Response);
}

if(isset($_POST['shop_approve_or_decline'])){
    if($_POST['cmd']=="approve"){
        dbcmd("INSERT INTO shop_product (
        mc_id,
        sc_id,
        seller,
        image,
        name,
        price,
        description) 
        SELECT 
        mc_id,
        sc_id,
        seller,
        image,
        name,
        price,
        description
        FROM user_shop WHERE id = '".$_POST['id']."'");
        dbcmd("DELETE FROM user_shop WHERE id = '".$_POST['id']."'");
        echo "post-approved";
    }
    else if($_POST['cmd']=="decline"){
        dbcmd("DELETE FROM user_shop WHERE id = '".$_POST['id']."'");
        echo "post-declined";
    }
}

//evaluate or block users on /adminindex.php
if(isset($_POST['user_maintain'])){
    // self maintain disabled
    if($_POST['user_id']==$_SESSION['user']['id']){
        echo "You can not execute these commands on your self!\nTry with an alternate admin account.";
        exit;
    }

    // evalate and downgrade
    if($_POST['user_admin']!=-1){
        dbcmd("UPDATE users SET admin = ".$_POST['user_admin']." WHERE id =".$_POST['user_id']." limit 1");
        if($_POST['user_admin']==1){
            echo "USER_ID_",$_POST['user_id']," has been evaluated as an admin!!";
        }
        else{
            echo "USER_ID_",$_POST['user_id']," has been downgraded as a normal user!!"; 
        }
    }


    // block a user
    else{
        dbcmd("DELETE FROM users WHERE id=".$_POST['user_id']."");
        echo "USER_ID_",$_POST['user_id'],"has been totally removed from XPOINTS.LK!!!";
    }
}



require($_SERVER["DOCUMENT_ROOT"]."/assets/php/db/dbclose.php");

?>