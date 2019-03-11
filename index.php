
<?php
include("PHP/iheader.php");
?>
<!DOCTYPE html>
<html>
    <body class="MPage">
    <form class="MPsearch">
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