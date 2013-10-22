<?php
if (!empty($_POST['language'])) {
	$_SESSION['language'] = $_POST['language'];
}
elseif (empty($_SESSION['language'])) {
	$_SESSION['language'] = 'ukr';
}
function translate($text) {
	$database_handle=new PDO("mysql:host=localhost;dbname=mobile",'root','idevels');
	$statement_handle = $database_handle -> prepare('SELECT * FROM table_translate WHERE eng = :text');
	$statement_handle -> bindParam('text', $text, PDO::PARAM_STR);
	$statement_handle -> execute();
	$mas = $statement_handle -> fetch();
	if(strcasecmp($_SESSION['language'], 'ukr') == 0) {
		return $mas["ukr"];
  }
  else {
    return $mas["eng"];
  }
}
?>