<?php
require("db/dbconfig.php");

date_default_timezone_set("Asia/Kolkata");

/*GLOBAL IMAGE formats*/
$image_formats = array('jpg', 'jpeg', 'png', 'gif', 'raw', 'jfif', 'webp');
$audio_formats = array('mp3', 'wav', 'wma', 'ogg', 'mpeg');
$video_formats = array('mp4', 'mkv');
//get current file format
function getfileformat($file)
{
    return substr($file, strrpos($file, ".") + 1);
}


function dbget($sql)
{
    global $db;
    $datas = array();
    $results = mysqli_query($db, $sql);
    if ($results) {
        if ($results->num_rows > 0) {
            while ($row = mysqli_fetch_assoc(($results))) {
                array_push($datas, $row);
            }
            return $datas;
        }
    } else {
        die(mysqli_error($db));
    }
}



function dbcmd($sql)
{
    global $db;
    if (!(mysqli_query($db, $sql))) {
        die(mysqli_error($db));
    } else {
        return true;
    }
}

function uploadFiles($file, $dir)
{
    $path = $_SERVER['DOCUMENT_ROOT'] . "/assets/uploads/$dir";
    $name = $file["name"];
    $imageFileType = strtolower(pathinfo($name,PATHINFO_EXTENSION));
    // Remove dangerous characters from filename.
    $name = preg_replace('/[^a-zA-Z0-9-.-]/', '-', $name);

    if (!file_exists($path)) {
        mkdir($path, 0777, true);
    }
    
    
    if (move_uploaded_file($file["tmp_name"], "$path/$name")) {
        $thumb_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/uploads/thumbnails/$name";
        move_uploaded_file($file["tmp_name"], $thumb_dir);
        if($imageFileType === "jpg" || $imageFileType === "jpeg"){
            imagecropper("$path/$name",$thumb_dir);
        }
        return "/assets/uploads/$dir/$name";
    }
    
}




function imagecropper($imag,$destination){

    $image = imagecreatefromjpeg($imag);
    
    $thumb_width = 500;
    $thumb_height = 500;
    
    $width = imagesx($image);
    $height = imagesy($image);
    
    $original_aspect = $width / $height;
    $thumb_aspect = $thumb_width / $thumb_height;
    
    if ( $original_aspect >= $thumb_aspect )
    {
       // If image is wider than thumbnail (in aspect ratio sense)
       $new_height = $thumb_height;
       $new_width = $width / ($height / $thumb_height);
    }
    else
    {
       // If the thumbnail is wider than the image
       $new_width = $thumb_width;
       $new_height = $height / ($width / $thumb_width);
    }
    
    $thumb = imagecreatetruecolor( $thumb_width, $thumb_height );
    
    // Resize and crop
    imagecopyresampled($thumb,
                       $image,
                       0 - ($new_width - $thumb_width) / 2, // Center the image horizontally
                       0 - ($new_height - $thumb_height) / 2, // Center the image vertically
                       0, 0,
                       $new_width, $new_height,
                       $width, $height);
    imagejpeg($thumb, $destination, 80);
    

}







function deleteFiles($files = array())
{
    foreach ($files as $file) {
        unlink($file);
    }
}

function sendNotification($user_id, $text, $url)
{
    //sending notification
    dbcmd(
        "INSERT INTO user_notification(
            user_id,
            text,
            page
        )
        VALUES(
            $user_id,
            '$text',
            '$url'
        )"
    );
}
