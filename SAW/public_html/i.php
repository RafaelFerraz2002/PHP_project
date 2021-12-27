<?php

$servername = "localhost";
$username = "root";
$password = "passwordSAW";
$db = "saw";

// Create connection
$conn = new mysqli($servername, $username, $password, $db);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

$numero = "";
$nome = "";
$login = "";
$password = "";
$nivel = "";
$email = "";
?>
<html>
    <form name="registo" method="POST"> <!-- não preciso de definir action porque quero submeter para o mesmo ficheiro-->
        <div>
        <label for="numero">Numero</label>
        <input type="text" name="numero" id="numero" value="<?=$numero?>">
        </div>
        <div>
        <label for="nome">Nome</label>
        <input type="text" name="nome" id="nome" value="<?=$nome?>">
        </div>
        <div>
        <label for="login">Login</label>
        <input type="text" name="login" id="login" value="<?=$login?>">
        </div>
        <div>
        <label for="password">Password</label>
        <input type="password" name="password" id="password" value="<?=$password?>">
        </div>
        <div>
        <label for="nivel">Nivel</label>
        <input type="text" name="nivel" id="nivel" value="<?=$nivel?>">
        </div>
        <div>
        <label for="email">Email</label>
        <input type="text" name="email" id="email" value="<?=$email?>">
        </div>
        <div>
        <input type="submit" value="Submit">
        </div>
    </form>
</html>
<?php
  
$numero= $_REQUEST['numero'];
$nome=$_REQUEST['nome'];
$login=$_REQUEST['login'];
$password=$_REQUEST['password'];
$nivel=$_REQUEST['password'];
$email=$_REQUEST['email'];
// Performing insert query execution
// here our table name is college
$sql = "INSERT INTO utilizadores  VALUES ('$numero', 
    '$nome','$login','$password','$nivel','$email')";
  
if(mysqli_query($conn, $sql)){
    echo "<h3>data stored in a database successfully." 
        . " Please browse your localhost php my admin" 
        . " to view the updated data</h3>"; 

    echo nl2br("\n$numero\n $nome\n "
        . "$login\n $password\n $nivel\n $email");
} else{
    echo "ERROR: Hush! Sorry $sql. " 
        . mysqli_error($conn);
}

?>

