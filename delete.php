<?php
ini_set('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
if (!empty($_SESSION['id']) ) {
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
	$statement_handle = $database_handle->prepare('DELETE FROM table_profils WHERE id = :id');
	$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  $statement_handle -> execute();
  if ($_SESSION['access'] != 4) {
 		unset($_SESSION['id'], $_SESSION['login'], $_SESSION['access']);
  }
  header('Location:index.php');
}
?>
