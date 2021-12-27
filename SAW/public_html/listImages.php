<?php
    require_once "config.php";
    $sql = "SELECT id FROM user ORDER BY id DESC"; 
    $result = mysqli_query($link, $sql);
?>
<HTML>
<HEAD>
<TITLE>List BLOB Images</TITLE>
<link href="imageStyles.css" rel="stylesheet" type="text/css" />
</HEAD>
<BODY>
<?php
	while($row = mysqli_fetch_array($result)) {
	?>
		<img src="imageView.php?image_id=<?php echo $row["id"]; ?>" /><br/>
	
<?php		
	}
    mysqli_close($link);
?>
</BODY>
</HTML>