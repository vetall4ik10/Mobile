<?php
ini_set('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
include 'form.php';
include 'translate.php';
if (!empty($_SESSION['access'])) {
	if ( !empty($_SESSION['id']) || ($_SESSION['access'] == 4 )) {
		if ($_SESSION['access'] == 4 ) {
			if (!empty($_GET['profils']) ) {
				$id = $_GET['profils'];
			}
			else {
				$id = $_SESSION['id'];
			}
		}
		else {
			$id = $_SESSION['id'];
		}
	}
} else $id = $_GET['profils'];
	$statement_handle = $database_handle -> prepare(" SELECT * FROM table_profils WHERE id = :id");
  $statement_handle -> bindParam('id', $id, PDO::PARAM_STR);
  $statement_handle -> execute();
  $mas_profil = $statement_handle -> fetch();
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<link rel = "stylesheet" type = "text/css" href = "css/style.css">
	<script type="text/javascript">
  function confirmDelete() {
   return confirm("Are you sure you want to delete the account?");
	}
	</script>
</head>
<body>
	<header ><a href = "index.php">MOBILE</a>		<?php include 'translate.html';?></header>
	<?php
	include 'form.html';

	?>
	<div class = "profil">
		<div class = "h2">
		 <h2>Profil</h2>
		 <?php if (isset($_SESSION['id'])): ?>
			 <?php if ($id == $_SESSION['id'] || $_SESSION['access'] == 4): ?>
				 <a href = "edit-profil.php?id=<?php print($id); ?>"> Edit </a>
				 <a href = "delete.php?id=<?php print($id); ?>" onclick="return confirmDelete();">,delete<a/>
				<?php endif; ?>
			<?php endif; ?>
		</div>
			<img src = "<?php print( $mas_profil['image'] )?>">
		<table>
			<tr>
				<td> <?php print translate('Login'); ?>: </td>
				<td> <?php print( $mas_profil['login'] ); ?> </td>
			</tr>
			<tr>
			  <td><?php print translate('Email'); ?>: </td>
			  <td> <?php print( $mas_profil['email'] ); ?> </td>
			</tr>
			<tr>
				<td>Name: </td>
				<td> <?php print( $mas_profil['name'] ); ?> </td>
			</tr>
			<tr>
				<td>Surname: </td>
				<td> <?php print( $mas_profil['surname'] ); ?> </td>
			</tr>
			<tr>
				<td>Date of registration:</td>
				<td> <?php print( $mas_profil['date_of_registration'] ); ?> </td>
			</tr>
			<tr>
				<td>Date of authorization:</td>
				<td> <?php print( $mas_profil['date_activity'] ); ?>
			</tr>
		</table>
	</div>
</body>
<html>