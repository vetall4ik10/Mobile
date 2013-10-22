<?php
	ini_set('include_path', getenv('DOCUMENT_ROOT'));
  include 'function/connected.php';
  if (!empty($_POST)) {
	 	$login = $_POST['login'];
	 	$password1 = $_POST['password1'];
	 	$password2 = $_POST['password2'];
	 	$email = $_POST['email'];
	 	$image = "img/standart.jpg";
	 	$time = date('d F Y(H:i:s )');
		$statement_handle = $database_handle -> prepare(" SELECT * FROM table_profils WHERE login = :login  ");
    $statement_handle -> bindParam('login', $login, PDO::PARAM_STR);
    $statement_handle -> execute();
    $mas = $statement_handle -> fetch();
    if ( empty($mas) ) {
    	$statement_handle = $database_handle -> prepare(" INSERT INTO table_profils (login, password, email, date_of_registration, date_activity, image) VALUES (:login, :password, :email, :time1, :time2, :image)  ");
    	$statement_handle -> bindParam('login', $login, PDO::PARAM_STR);
    	$statement_handle -> bindParam('password', $password1, PDO::PARAM_STR);
    	$statement_handle -> bindParam('email', $email, PDO::PARAM_STR);
    	$statement_handle -> bindParam('time1', $time, PDO::PARAM_STR);
    	$statement_handle -> bindParam('time2', $time, PDO::PARAM_STR);
    	$statement_handle -> bindParam('image', $image, PDO::PARAM_STR);
    	$statement_handle -> execute();
    	$statement_handle = $database_handle -> prepare(" SELECT * FROM table_profils WHERE login = :login");
      $statement_handle -> bindParam('login', $login, PDO::PARAM_STR);
      $statement_handle -> execute();
      $mas = $statement_handle -> fetch();
      $_SESSION['id'] = $mas['id'];
     	$_SESSION['login'] = $mas['login'];
     	$_SESSION['access'] = $mas['access'];
     	header('Location: edit-profil.php');
    } else {
    	$error=1;
    }

}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="main.js"></script>
</head>
<body>
	<header ><a href = "index.php">MOBILE</a></header>
	<div class="sing-up">
	Sing up
		<form name = "form" method="post" onsubmit="return match()">
			login
			<input required name="login" type="text" maxlength="25" size="60" id = "login">
			<br >
			password
			<input required name="password1" type="password" size="60" id = "pass1">
			<br >
			please repeat the password
			<input required name="password2" type="password" size="60" id = "pass2" >
			<br >
			email
			<input required name="email" type="text" size="60" id = "email">
			<br >
			<input type="submit">
			<span id= "error"></span>
		</form>
		<?php
		if (!empty($error) ) {
			print('A nickname already exists');
		}
		?>
	</div>
</body>
</html>