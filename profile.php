<?php
include("PHP/iheader.php");
if($_SESSION['logged'] != true){
    redirect('posts.php');
}
?>
<!DOCTYPE html>
<html>
<head></head>
<body>
    <fieldset class="prof">
    <legend id="logintitle">Upload Files</legend>
        <form action="PHP/upload.php" method="post" enctype="multipart/form-data">
            <input type="file" name="fileToUpload" id="fileToUpload">
            <input type="submit" value="Upload Image" name="UPLOAD">
        </form>
    </fieldset>
    <?php
    //$_POST['UPLOAD']
        if(isset($_FILES['fileToUpload'])){
            include('PHP/upload.php');
        }
    ?>
    <fieldset class="prof">
        <legend id="logintitle">Your Tokens</legend>
        <table>
        <?php
        $UIMG = $DBH->prepare("SELECT filename FROM BO_images WHERE owner_id =" . $_SESSION['ID'] . ";");
        $UIMG->execute();
        $IMGCOUNT = $UIMG->rowCount();
        if(0 < $IMGCOUNT){
            for($i=0;$i<$IMGCOUNT;$i++){
                $USAGE = $UIMG->fetch();
                echo('<tr>
                    <td><a href="show.php?post=' . $USAGE['filename'] . '">'. ($USAGE['filename']) . '</a></td>
                    </tr>');
            };
        }else{
            echo 'You have not uploaded any tokens';
        }
        ?>
            </table>
    </fieldset>
    <?php
    echo $_SESSION['UT'];
    ?>
    </body>
</html>