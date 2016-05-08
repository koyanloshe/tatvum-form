<?php 
	session_start();
	$token = md5(uniqid(rand(), true));
	$_SESSION['token'] = $token;
	echo $token;
?>
