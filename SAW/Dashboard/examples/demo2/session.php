<?php

session_start();

if(!isset($_SESSION['email']) && !isset($_SESSION['pass'])){
   
    echo "<meta http-equiv=refresh content='0; url=index.php'>";exit;
  }



?>