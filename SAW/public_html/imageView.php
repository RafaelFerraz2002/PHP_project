<?php
    require_once "config.php";
    if(isset($_GET['id'])) {
        $sql = "SELECT imageType,imageData FROM user WHERE imageId=" . $_GET['id'];
		$result = mysqli_query($link, $sql) or die("<b>Error:</b> Problem on Retrieving Image BLOB<br/>" . mysqli_error($link));
		$row = mysqli_fetch_array($result);
		header("Content-type: " . $row["imageType"]);
        echo $row["imageData"];
	}
	mysqli_close($link);
?>