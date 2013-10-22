<?php
ini_set("include_path", getenv("DOCUMENT_ROOT"). "/function");
include 'connected.php';
include 'form.php';
include 'translate.php';
if (!empty($_GET['news']) ) {
	$id = $_GET['news'];
	$news_name = "news_name_".$_SESSION['language'];
	$news_text = "news_text_".$_SESSION['language'];
	$news = $database_handle->prepare("SELECT table_news.*,table_profils.login FROM table_news INNER JOIN table_profils ON table_profils.id = table_news.author_id WHERE table_news.id = :id_news ");
	$news->bindParam('id_news', $id, PDO::PARAM_INT);
	$news->execute();
	$mas_news = $news->fetch();
	if (isset($_SESSION['access'])) {
		if($_SESSION['access'] > 2 || strcmp($mas_news['news_author'], $_SESSION['login']) == 0) {
			if (!empty ($_POST['news_name'] )) {
				$news_text2 = trim(strip_tags($_POST['news_text']));
				$news_name2 = trim(strip_tags($_POST['news_name']));
				$time = date('d F Y (H:i:s)');
				if (!empty($news_name2) && !empty($news_text2) ) {
					$statement_handle = $database_handle -> prepare("UPDATE table_news SET $news_name = :news_name, $news_text = :news_text, author_id = :author_id, time = :time WHERE id = :id");
					$statement_handle->bindParam('id', $id,  PDO::PARAM_INT);
				  $statement_handle->bindParam('news_name', $news_name2,  PDO::PARAM_STR);
				  $statement_handle->bindParam('news_text', $news_text2, PDO::PARAM_STR );
				  $statement_handle->bindParam('author_id', $_SESSION['id'], PDO::PARAM_INT);
				  $statement_handle->bindParam('time', $time, PDO::PARAM_STR);
				  $statement_handle->execute();
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
	}
	else {
		$error = 3;
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
	<?php include 'form.html'; ?>
	<?php if (!empty($_GET['news']) ) : ?>
		<?php if(isset($_SESSION['id'])): ?>
			<?php if($_SESSION['access'] > 2 || strcmp($mas_news['news_author'], $_SESSION['login']) == 0): ?>
				<div class = "add">
					<form method = "POST">
						NEWS NAME
						<input type = "text" name = "news_name" size = "119" maxlength="100" value="<?php print ($mas_news[$news_name]);?>" required>
						<br >
						<textarea name = "news_text" cols = "100" rows = "20" required><?php print ($mas_news[$news_text]);?></textarea>
						<br >
						<input type = 'submit' value = 'add'>
					</form>
				</div>
			<?php endif; ?>
		<?php endif; ?>
		<?php if (isset($error)): ?>
				<?php if ($error == 1 ):?>
					<p><?php print('text')?></p>
				<?php elseif($error == 2): ?>
					<p><?php print('text2')?></p>
				<?php elseif($error == 3): ?>
					<div class = "error">
						<?php print ('You are not logged in!');?>
					</div>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
</body>
</html>