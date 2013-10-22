<?php
ini_set('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
$id = $_GET['news'];
$statement_handle2 = $database_handle->prepare('SELECT * FROM table_news WHERE id = :id_news ');
$statement_handle2 -> bindParam('id_news', $id, PDO::PARAM_INT);
$statement_handle2 -> execute();
$mas_news = $statement_handle2 -> fetch();
if($_SESSION['access'] > 2 OR  strcmp($mas_news['news_author'], $_SESSION['login']) == 0) {
	$statement_handle = $database_handle->prepare('DELETE FROM table_news WHERE id = :id');
	$statement_handle -> bindParam('id', $id, PDO::PARAM_INT);
  $statement_handle -> execute();
  header('Location: index.php');
}
else {
	header('Location: index.php');
}
?>