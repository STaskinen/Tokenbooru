<?php
include("PHP/iheader.php");
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/stylesheet.css">

</head>


<body>
    
    <fieldset>
        <legend id="logintitle">Login</legend>
			<hr style="height:1px; visibility:hidden;" />
        <form method="post">
            <legend>Username</legend>
            <p><input type="text" name="uname" placeholder=" "/></p>
            <legend>Password</legend>
            <p><input type="password" name="Pwr" placeholder=" "/></p>
            <p><input id="loginsubmit" type="submit" name="Log" value="Submit"/></p>
            	<br>
            <p><input type="reset"/></p>
        </form>
        <?php
        if(isset($_POST['Log'])){
            $user = login($_POST['uname'], $_POST['Pwr'], $DBH);
            if($user){
                $_SESSION['uname'] = $user->uname;
                $_SESSION['ID'] = $user->UID;
                $_SESSION['UT'] = $user->userclass;
                $_SESSION['logged'] = 'true';
                $_SESSION['message'] = "Login successful";
                redirect('posts.php');
            }else{
                $_SESSION['message'] = "Wrong username or password";
            }
        }
        ?>
    </fieldset>
<br></br>
    <fieldset>
        <legend id="logintitle">Register</legend>
			<hr style="height:1px; visibility:hidden;" />
        <form method="post">
            <legend>Username</legend>
            <p><input type="text" name="runame" placeholder=" " required/></p>
            <legend>Email</legend>
            <p><input type="email" name="email" placeholder="_______@___________.__" required
                      oninvalid="setCustomValidity('Needs be (something@something.SOMT)')"
                      onchange="try{setCustomValidity('')}catch(e){}"/></p>
            <legend>Password</legend>
            <p><input type="password" name="Pw1" placeholder="once" required/></p>
            <p><input type="password" name="Pw2" placeholder="again" required
                      oninvalid="setCustomValidity('The passwords don't match.')"
                      onchange="try{setCustomValidity('')}catch(e){}"/></p>
            <p><input id="loginsubmit" type="submit" name="Reg" value="Submit"/></p>
				<br>
            <p><input type="reset"/></p>
        </form>
        <script>
    var PSW1 = document.querySelector('input[name="Pw1"]');
    var PSW2 = document.querySelector('input[name="Pw2"]');
            var fillPattern = function(){
                PSW2.pattern = this.value;
            }
    PSW1.addEventListener('keyup', fillPattern);
</script>
        <p><?php
        if(isset($_POST['Reg'])){
            if($_POST['Pw1'] === $_POST['Pw2']){
          include("saveUser.php");
            }
        }
        ?></p>
    </fieldset>
</body>


</html>