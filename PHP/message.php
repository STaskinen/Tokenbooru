<!DOCTYPE html>
<div id=messagebox>
<?php
    echo ("<p id=messagetext >" . $_SESSION['message'] . "</p>");
?>
<form id=messageclear method="post">
<input type="submit" name="clrmsg" value="X"/>
</form>
<?php
if(($_POST['clrmsg'])){
    unset($_POST['clrmsg']);
    unset($_SESSION['message']);
    redirect($_SERVER['PHP_SELF']);
}
?>
</div>