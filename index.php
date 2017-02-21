
<?php
include("PHP/iheader.php");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">
</head>

    <body class="MPage">
    <form id=indexsearch class="MPsearch">
        <input type="text" name="tsearch"/>
            <input type="submit" name="search" value="Search"/>
            </form>
            <?php
            if($_GET['search']){
                redirect('posts.php?tsearch=' . $_GET['tsearch']);
            }
            ?>
    </body>
</html>