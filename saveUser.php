<?php
if(isset($_POST["Reg"])) {
    $datat['uname'] = $_POST['runame'];
    $datat['email'] = $_POST['email'];
    $datat['psw'] = hash('sha256',$_POST['Pw1']."prkl"); //prkl on suola
    if(filter_var($datat['email'], FILTER_VALIDATE_EMAIL)){
    try{
        $stm = $DBH->prepare("INSERT INTO BO_users (uname, pass, email) VALUES (:uname, :psw, :email);");
        if($stm->execute($datat)){
            echo("hyvin meni, toistaiseksi ainakin.");
            $_SESSION['message'] = "Registration successful. You can log in now.";
            redirect("login.php");
            //$_SESSION['message'] = "Login now available" . $_SESSION['email'];
        }else{
            $_SESSION['message'] = "SOMETHING BROKE YO! ASK THE ADMINS TO FIX THIS SHIT!";
            redirect("index.php");
        }
    }catch(PDOException $e){
        $SESSION['message'] = "DATABASE ERROR"; //. $e.getMessage()");
    }
    }
}
?>
