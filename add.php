<?php
ini_set("include_path", getenv("DOCUMENT_ROOT"). "/function");
include 'connected.php';
include 'form.php';
include 'translate.php';
if (!empty($_SESSION['id'])) {
	if (!empty ($_POST['news_name_eng'] )) {
		$time = date('d F Y (H:i:s)');
		$news_name_eng = strip_tags($_POST['news_name_eng']);
		$news_name_ukr =  strip_tags($_POST['news_name_ukr']);
		$news_text_eng = strip_tags($_POST['news_text_eng']);
		$news_text_ukr = strip_tags($_POST['news_text_ukr']);
		if(preg_match('/\w/', $news_name_eng) && preg_match('/\w/', $news_text_eng) && preg_match('/[a-ю] || \w/', $news_name_ukr) && preg_match('/[a-ю] || \w/', $news_text_ukr) ) {
			$statement_handle = $database_handle -> prepare('INSERT INTO table_news(news_name_ukr, news_name_eng, news_text_eng, news_text_ukr, author_id, time) VALUES (:news_name_ukr, :news_name_eng, :news_text_eng, :news_text_ukr, :author_id, :time) ');
		  $statement_handle -> bindParam('news_name_ukr', $news_name_ukr, PDO::PARAM_STR);
		  $statement_handle -> bindParam('news_name_eng', $news_name_eng, PDO::PARAM_STR );
		  $statement_handle -> bindParam('news_text_ukr', $news_text_ukr, PDO::PARAM_STR );
		  $statement_handle -> bindParam('news_text_eng', $news_text_eng, PDO::PARAM_STR );
		  $statement_handle -> bindParam('author_id', $_SESSION['id'], PDO::PARAM_INT);
		  $statement_handle -> bindParam('time', $time, PDO::PARAM_STR);
		  $statement_handle -> execute();
	   	header("Location:index.php");
	 	}
	 	else {
	 		$error = 1;
	 	}
	}
}
else {
	$error = 2;
}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header ><a href = "index.php">MOBILE</a><?php include 'translate.html';?></header>
	<?php
	include 'form.html';
	if (!empty($_SESSION['id']) ):
	 ?>
	<div class = "add">
		<form method = "POST">
			NEWS NAME
			<input type = "text" name = "news_name_eng" size = "119" maxlength="100">
			<br >
			<textarea name = "news_text_eng" cols = "100" rows = "10" placeholder = "News content"></textarea>
			<input type = "text" name = "news_name_ukr" size = "119" maxlength="100">
			<textarea name = "news_text_ukr" cols = "100" rows = "10" placeholder = "News content"></textarea>
			<br >
			<input type = 'submit' value = 'add'>
		</form>
	</div>
<?php endif; ?>
		<?php if (isset($error)): ?>
			<?php if ($error == 1 ):?>
				<p><?php print('News should also contain letters and numbers!')?></p>
			<?php else: ?>
				<div class = "error">
					<?php print ('You are not logged in!');?>
				</div>
			<?php endif; ?>
		<?php endif; ?>
</body>
</html>
