<?php
include('iheader.php');
$target_dir = "../uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}
// Check file size
if ($_FILES["fileToUpload"]["size"] > 500000000) {
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
}
// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
/*function IDING(){
    $okay = 0;
    try{
        $DBH->prepare("SELECT UID FROM BO_users WHERE uname =" . $name . ";");
        $ownerid = $DBH->execute();
        $okay = 1;
    }catch(PDOException $e){
        file_put_contents('./log/DBErrors.txt', 'Connection: '.$e->getMessage()."\n", FILE_APPEND);
    };
    if($okay === 1){
        return $ownerid;
    }else{
    return "fuck";
    }
};*/
/*function DATABASING($data){ 
    try{
    print_r($data);
        $UPPIC = $DBH->prepare("INSERT INTO BO_images (owner_id, filename, filesize, ext, isource, width, height) VALUES (:iowner, :iname, :isize, :itype, :isource, :iwidth, :iheight);");
        if($UPPIC->execute($data)){
            $_SESSION['message'] = 'Everything is good';
            return 'true';
        }else{
            $_SESSION['message'] = 'Something went wrong';
            return 'false';
        };
    }catch(PDOException $e){
        echo 'FUCK';
        print_r($e);
        file_put_contents('./log/DBErrors.txt', 'Connection: '.$e->getMessage()."\n", FILE_APPEND);
    };
};*/
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
        $upimg['isize'] = $_FILES['fileToUpload']['size'];
        $upimg['iname'] = $_FILES['fileToUpload']['name'];
        $upimg['itype'] = $imageFileType;
        $upimg['iowner'] = $_SESSION['ID'];
        $upimg['isource'] = "uploads/" . $_FILES["fileToUpload"]["name"];
        list($upimg['iwidth'],$upimg['iheight']) =  getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        try{
    print_r($upimg);
        $UPPIC = $DBH->prepare("INSERT INTO BO_images (owner_id, filename, filesize, ext, isource, width, height) VALUES (:iowner, :iname, :isize, :itype, :isource, :iwidth, :iheight);");
        if($UPPIC->execute($upimg)){
            $_SESSION['message'] = 'Everything is good';
            $DBC = 'true';
        }else{
            $_SESSION['message'] = 'Something went wrong';
            $DBC = 'false';
        };
    }catch(PDOException $e){
        echo 'FUCK';
        print_r($e);
        file_put_contents('./log/DBErrors.txt', 'Connection: '.$e->getMessage()."\n", FILE_APPEND);
    };
    if ($DBC == 'true'){
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Your image has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
    }else{
        echo "Sorry, there was a DB error while uploading your file.";
    }
}
header('Location: ../profile.php');
?>
