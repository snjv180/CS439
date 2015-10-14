<?php

	session_start();
	if(isset($_SESSION['username'])){
		session_destroy();
		header("Location: index.php");
	}
	else{
		echo "You are not authorized!!!";
	}
?>