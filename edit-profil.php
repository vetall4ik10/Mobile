<?php
ini_set('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
include 'form.php';
if ( !empty($_SESSION['id']) || ($_SESSION['access'] == 4 )) {
	if ($_SESSION['access'] == 4 ) {
		if (!empty($_GET['id']) ) {
			$id = $_GET['id'];
		}
		else {
			$id = $_SESSION['id'];
		}
	}
	else {
		$id = $_SESSION['id'];
	}
	$statement_handle = $database_handle -> prepare(" SELECT * FROM table_profils WHERE id = :id");
  $statement_handle -> bindParam('id', $id, PDO::PARAM_STR);
  $statement_handle -> execute();
  $mas_profil = $statement_handle -> fetch();
  if (!empty($_POST['password_old'] ) ) {
  	$password_old = $_POST['password_old'];
  	$password1 = $_POST['password1'];
  	$password2 = $_POST['password2'];
  	if ( strcmp($password_old, $mas_profil['password']) == 0 ) {
  		if ( strcmp($password1, $password2) == 0) {
  			$statement_handle = $database_handle -> prepare(" UPDATE table_profils SET password = :password WHERE id = :id");
  			$statement_handle -> bindParam('password', $password1, PDO::PARAM_STR);
  			$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  			$statement_handle -> execute();
  			$site = $_SERVER['REQUEST_URI'];
				header("Location:$site");
  		} else {
  			$error = 2;//Passwords do not match
  		}
  	} else {
  		$error = 1;//Incorrectly entered password
  	}
  }
  if ( !empty($_POST['name']) ) {
  	$name = $_POST['name'];
  	$surname = $_POST['surname'];
  	$email = $_POST['email'];
  	if ( preg_match('/[\w]+\@{1}[\w]+\.[\w]/',$email) ) {
  		$statement_handle = $database_handle -> prepare(" UPDATE table_profils SET name = :name, surname = :surname, email = :email WHERE id = :id");
  		$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  		$statement_handle -> bindParam('name', $name, PDO::PARAM_STR);
  		$statement_handle -> bindParam('surname', $surname, PDO::PARAM_STR);
  		$statement_handle -> bindParam('email', $email, PDO::PARAM_STR);
  		$statement_handle -> execute();
  		$site = $_SERVER['REQUEST_URI'];
			header("Location:$site");
  	} else {
  		$error = 3;//email is inappropriate
  	}
  }
  if (!empty($_FILES['filename']['tmp_name'])) {
  	$filename = "img/id".$_SESSION['id'].".jpg";
  	$width = 150;
	  $height = 150;
	  $img_src = $_FILES['filename']['tmp_name'];
	  $image = imagecreatefromjpeg($img_src);
	  list($image_width, $image_height) = getimagesize($img_src);
	  $tmp_img = imagecreatetruecolor($width, $height);
	  imagecopyresampled($tmp_img, $image, 0, 0, 0, 0, $width, $height, $image_width, $image_height);
	  imagejpeg($tmp_img, "$filename", 100);
	  $statement_handle = $database_handle -> prepare(" UPDATE table_profils SET image = :image WHERE id = :id");
  	$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  	$statement_handle -> bindParam('image', $filename, PDO::PARAM_STR);
  	$statement_handle -> execute();
	  imagedestroy($tmp_img);
	  imagedestroy($image);
	}
	if (!empty($_POST['access'])) {
		$access = (int)$_POST['access'];
		$id = (int)$id;
		$statement_handle = $database_handle -> prepare('UPDATE table_profils SET access = :access WHERE id = :id ');
		$statement_handle -> bindParam('access', $access, PDO::PARAM_INT);
		$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  	$statement_handle -> execute();
  	$site = $_SERVER['REQUEST_URI'];
		header("Location:$site");
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel = "stylesheet" type = "text/css" href = "css/style.css">
</head>
<body>
	<header ><a href = "index.php">MOBILE</a><?php include 'translate.php';?></header>
	<?php
	include 'form.html';
	if( !empty($_SESSION['id']) ) :
	?>
	<div class="edit-data">
		PASSWORD
		<form method="POST">
			old password
			<input name = "password_old" type = "password" required size="40">
			<br >
			new password
			<input name = "password1" type = "password" required size="40">
			<br >
			repeat password
			<input name = "password2" type = "password" required size="40">
			<br >
			<input type = "submit" value = "edit">
		</form>
		DATA
		<form method = "POST">
			name
			<input name = "name" type = "text" value = "<?php print( $mas_profil['name'] ); ?>" required size="40">
			<br >
			surname
			<input name = "surname" type = "text" value = "<?php print( $mas_profil['surname'] ); ?>" required size="40">
			<br >
			email
			<input name = "email" type = "text" value = "<?php print( $mas_profil['email'] ); ?>" required size="40">
			<br >
			<input type = "submit" value = "edit">
		</form>

		<form method='POST' enctype='multipart/form-data'>
  		<input type = "file", name = "filename">
  		<input type = "submit" value = "edit" required accept="image/jpeg,image/png,image/gif">
  	</form>
  	<?php if ($_SESSION['access'] == 4): ?>
  		<form method = "POST">
  		key
  			<select name = "access">
  			<?php for($i = 1; $i <= 4; $i++): ?>
  				<?php if($i == $mas_profil['access']): ?>
  					<option selected value = "<?php print $mas_profil['access']; ?>"><?php print $mas_profil['access']; ?></option>
  				<?php else: ?>
  				<option value = "<?php print $i?>"> <?php print $i?></option>
  			<?php endif; ?>
  			<?php endfor; ?>
  			</select>
  			<input type = "submit" value = "edit">
  		</form>
  	<?php endif; ?>
		<h3>
	<?php
	if(!empty($error)) {
	 switch ($error) {
	  	case '1':
	  		print ('Incorrectly entered password!');
	  		break;

	  	case '2':
				print ('Passwords do not match!');
				break;

	  	case '3':
	  		print ('Email is inappropriate!');
	  		break;
	  }
	}
	?>
	</h3>
	</div>
	<?php
 endif; ?>
</body>
</html>

