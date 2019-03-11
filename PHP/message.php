<!DOCTYPE html>
<?php
    echo($_SESSION['message']);
?>
<form method="post">
<input type="submit" name="clrmsg" value="X"/>
</form>
<?php
if(($_POST['clrmsg'])){
    unset($_POST['clrmsg']);
    unset($_SESSION['message']);
    redirect($_SERVER['PHP_SELF']);
}
?>