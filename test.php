<?php
ini_set("include_path", getenv("DOCUMENT_ROOT"). "/function");
include 'connected.php';
include 'form.php';
include 'translate.php';
$id = $_GET['news'];
$news_name = "news_name_".$_SESSION['language'];
$news_text = "news_text_".$_SESSION['language'];
$news = $database_handle -> prepare("SELECT $news_name, $news_text, id, news_author, time FROM table_news WHERE id = :id_news ");
$news -> bindParam('id_news', $id, PDO::PARAM_INT);
$news -> execute();
$mas_news = $news -> fetch();
if($_SESSION['access'] > 2 OR  strcmp($mas_news['news_author'], $_SESSION['login']) == 0) {
	if (!empty($_SESSION['id'])) {
		if (!empty ($_POST['news_name_eng'] )) {
			$time = date('d F Y (H:i:s)');
			$news_name_eng = strip_tags($_POST['news_name_eng']);
			$news_name_ukr = iconv("windows-1251", "UTF-8", strip_tags($_POST['news_name_ukr']));
			$news_text_eng = strip_tags($_POST['news_text_eng']);
			$news_text_ukr = iconv("windows-1251", "UTF-8", strip_tags($_POST['news_text_ukr']));
			if(preg_match('/\w/', $news_name_eng) && preg_match('/\w/', $news_text_eng) && preg_match('/[a-ю] || \w/', $news_name_ukr) && preg_match('/[a-ю] || \w/', $news_text_ukr) ) {
				$statement_handle = $database_handle -> prepare('INSERT INTO table_news(news_name_ukr, news_name_eng, news_text_eng, news_text_ukr, news_author, time) VALUES (:news_name_ukr, :news_name_eng, :news_text_eng, :news_text_ukr, :news_author, :time) ');
			  $statement_handle -> bindParam('news_name_ukr', $news_name_ukr, PDO::PARAM_STR);
			  $statement_handle -> bindParam('news_name_eng', $news_name_eng, PDO::PARAM_STR );
			  $statement_handle -> bindParam('news_text_ukr', $news_text_ukr, PDO::PARAM_STR );
			  $statement_handle -> bindParam('news_text_eng', $news_text_eng, PDO::PARAM_STR );
			  $statement_handle -> bindParam('news_author', $_SESSION['login'], PDO::PARAM_STR);
			  $statement_handle -> bindParam('time', $time, PDO::PARAM_STR);
			  $statement_handle -> execute();
		   	header("Location:index.php");
	 		}
	 		else {
	 			$error = 1;
	 		}
	 	}
	}
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
	if($_SESSION['access'] > 2 OR  strcmp($mas_news['news_author'], $_SESSION['login']) == 0):
	 ?>
	<div class = "add">
		<form method = "POST">
			NEWS NAME
			<input type = "text" name = "news_name" size = "119" maxlength="100" value="<?php print ($mas_news[$news_name]);?>">
			<br >
			<textarea name = "news_text" cols = "100" rows = "20"><?php print ($mas_news[$news_text]);?></textarea>
			<br >
			<input type = 'submit' value = 'add'>
		</form>
		<?php if ($error == 1 ):?>
		<p><?php print('News should also contain letters and numbers!')?></p>
		<?php endif; ?>
	</div>
<?php else: ?>
	<div class = "error">
		<?php print ('You are not logged in!');?>
	</div>
<?php endif; ?>
</body>