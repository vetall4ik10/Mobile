<?php
if (!empty($_POST['unset'])) {
 unset($_SESSION['id'], $_SESSION['access'], $_SESSION['login']);
}
if (empty($_SESSION['id'])){
	if (!empty($_POST['data']) ) {
	  $data = $_POST['data'];
	  $password = $_POST['password'];
	    if (preg_match('/[\w]+\@{1}[\w]+\.[\w]/',$data)!=0) {
	      $log_or_email = 'email';
	    }
	    else {
	      $log_or_email = 'login';
	    }
	    $statement_handle = $database_handle -> prepare(" SELECT * FROM table_profils WHERE $log_or_email = :data AND password = :password ");
	    $statement_handle -> bindParam('data', $data, PDO::PARAM_STR);
	    $statement_handle -> bindParam('password', $password, PDO::PARAM_STR);
	    $statement_handle -> execute();
	    $mas = $statement_handle -> fetch();
	    if (!empty($mas)) {
	    	if($mas['access'] != 1) {
		     	$_SESSION['id'] = $mas['id'];
		     	$_SESSION['login'] = $mas['login'];
		     	$_SESSION['access'] = $mas['access'];
		     	$site = $_SERVER['REQUEST_URI'];
		      $time = date('d F Y (H:i:s)');
		      $statement_handle = $database_handle -> prepare(" UPDATE  table_profils SET date_activity = :time WHERE $log_or_email = :data  ");
		      $statement_handle -> bindParam('time', $time, PDO::PARAM_STR);
		      $statement_handle -> bindParam('data', $data, PDO::PARAM_STR);
		      $statement_handle -> execute();
		     	header("Location:$site");
	      }
	     	else {
	     		$error_profil =3;
	     	}
	    }
	    else {
	      $error_profil = 2;
	    }
	}

}
?>