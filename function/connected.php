<?php
	@session_start();
 	$database_handle=new PDO("mysql:host=localhost;dbname=mobile",'root','idevels');
	$database_handle->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>