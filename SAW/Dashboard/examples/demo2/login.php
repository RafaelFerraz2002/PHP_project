<?php
require("config.php");

if(isset($_POST["submit"])){

  if(!empty($_POST["remember"])) {
    setcookie ("username",$_POST["email"],time()+ 3600);
    setcookie ("password",$_POST["pass"],time()+ 3600);
  } else {
    setcookie("email","");
    setcookie("pass","");
  }
  
session_start();

$email = $_POST['email'];
$pass = $_POST['pass'];
// A variavel $result pega as varias $login e $senha, faz uma
//pesquisa na tabela de usuarios


$sql = "SELECT * FROM user WHERE email = '$email'";




$result = mysqli_query($link, $sql);

if(mysqli_num_rows($result) > 0 ){
   $row = $result->fetch_assoc();
   
    $confirmPass = md5($pass);
    
    echo $confirmPass;
    echo "<br>";
    echo $row['pass'];

    if($confirmPass == $row["pass"]){
      if($row["roles"]=="Administrator"){
        $_SESSION['id'] = $row["id"];
        $_SESSION['nome'] = $row["nome"];
        $_SESSION['imageData'] = $row["imageData"];
        $_SESSION['imageType'] = $row["imageType"];
        $_SESSION['roles'] = $row["roles"];
        $_SESSION['email'] = $row["email"];
        header('location:ReadUser.php');
       }
       else{
    // remove all session variables
    session_unset();
    
    // destroy the session
    session_destroy();
    echo "fiz login mas nao sou admin";
    // header('location: index.php');
     
       }
    }
    else {
      //AVISO - PALAVRA PASS ERRADA
      echo "pass errada";
    }
}
else{
  // remove all session variables
session_unset();

// destroy the session
session_destroy();
echo "nao fiz login";

  }
}
?>