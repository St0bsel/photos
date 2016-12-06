<?php
//show errors and warnings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//required files
require 'sql_connect.php';
require 'config.php';

//functions
function make_thumb($src, $dest, $desired_width) {

	/* read the source image */
	$source_image = imagecreatefromjpeg($src);
	$width = imagesx($source_image);
	$height = imagesy($source_image);

	/* find the "desired height" of this thumbnail, relative to the desired width  */
	$desired_height = floor($height * ($desired_width / $width));

	/* create a new, "virtual" image */
	$virtual_image = imagecreatetruecolor($desired_width, $desired_height);

	/* copy source image at a resized size */
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);

	/* create the physical thumbnail image to its destination */
	imagejpeg($virtual_image, $dest);
}

//functions end

$user_dirs = scandir(PICTUREDIR);

//get usernames form db
$users_db = mysqli_fetch_all(mysqli_query($sql_connection, "SELECT UserName FROM Users"), MYSQLI_NUM);
//convert array to one dimensional array
$users_db_conv = array();
for ($i=0; $i < count($users_db); $i++) {
  $users_db_conv[$i] = $users_db[$i][0];
}

//are all userdirs for users in database
for ($i_userdir=0; $i_userdir < count($user_dirs); $i_userdir++) {
  $user_dir = $user_dirs[$i_userdir];
  //if user is in db get pictures from users folder
  if(in_array($user_dir, $users_db_conv)){
    $user_pictures = scandir(PICTUREDIR.$user_dir."/");

    //get files which are allready in db
    $user_pictures_db = mysqli_fetch_all(mysqli_query($sql_connection, 'SELECT Pictures.PictureName, Pictures.PicturePath FROM Pictures JOIN Users ON Pictures.PictureUploadedBy = Users.UserId WHERE Users.Username ="'.$user_dir.'"'), MYSQLI_NUM);
    $user_pictures_db_conv = array();
    for ($i=0; $i < count($user_pictures_db); $i++) {
      $user_pictures_db_conv[$i] = $user_pictures_db[$i][0];
    }

    //for each file in users folder
    for ($i_fileindir=0; $i_fileindir < count($user_pictures); $i_fileindir++) {
      $picture_indir = $user_pictures[$i_fileindir];
      //check if file is picture
      $ext = strtolower(pathinfo($picture_indir, PATHINFO_EXTENSION));
      if (in_array($ext, $allowed_filetyps)) {
        //check if file is not allready in db
        if (in_array($picture_indir, $user_pictures_db_conv) != 1) {
          //insert into database
          $picture_name = $picture_indir;
          $picture_path = PICTUREDIR.$user_dir."/";
          $picture_thumb = THUMBDIR.$user_dir."/";
          $userid = mysqli_fetch_array(mysqli_query($sql_connection, 'SELECT UserId FROM Users WHERE UserName="'.$user_dir.'"'));
          $picture_uploadedby = $userid[0];
          $picture_location = 1;
          //get date from file and format correct
          $picture_date = date("Y-m-d", filemtime($picture_path.$picture_name));
          mysqli_query($sql_connection,
          "INSERT INTO `Pictures`(`PictureName`, `PicturePath`, `PictureUploadedBy`, `PictureLocation`, `PictureDate`)
          VALUES ('$picture_name', '$picture_path', '$picture_uploadedby', '$picture_location', '$picture_date')");
          echo "file ".$picture_indir." was added for user ".$user_dir."<br>";

          //create thumbnail
          make_thumb($picture_path.$picture_name, $picture_thumb.$picture_name, 200);

        }
      }
    }

    //check if file was deleted
    for ($i_fileindb=0; $i_fileindb < count($user_pictures_db_conv); $i_fileindb++) {
      $picture_indb = $user_pictures_db_conv[$i_fileindb];
      //if file from db dont exisits on server
      if (in_array($picture_indb, $user_pictures) != 1) {
        //delet from db
        mysqli_query($sql_connection, 'DELETE FROM Pictures WHERE PictureName="'.$picture_indb.'"');
        //check if thumb exisits and delet it
        $thumb_pictures = scandir(THUMBDIR.$user_dir."/");
        if (in_array($picture_indb, $thumb_pictures)) {
          unlink(THUMBDIR.$user_dir."/".$picture_indb);
        }
        echo "picture ".$picture_indb." was deleted from user ".$user_dir."<br>";

      }
    }
  }
}

 ?>
