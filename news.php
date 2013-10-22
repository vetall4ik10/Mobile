<?php
ini_set ('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
include 'form.php';
include 'translate.php';
if (!empty($_GET['news']) ) {
	$id = $_GET['news'];
	///////////////////////////////////////////////////////
	//				 Видалення оцінки профіля 								 //
	///////////////////////////////////////////////////////
	if(!empty($_POST['delete_rating'])) {
		$id_news = (int)$_POST['delete_rating'];
		$id_profils = (int)$_SESSION['id'];
		$rating_PDO = $database_handle->prepare('DELETE FROM table_rating WHERE id_news = :id_news && id_profils = :id_profils');
		$rating_PDO ->bindParam('id_news', $id_news, PDO::PARAM_INT);
		$rating_PDO ->bindParam('id_profils', $id_profils, PDO::PARAM_INT);
		$rating_PDO ->execute();
		$site = $_SERVER['REQUEST_URI'];
		header("Location:$site");
	}
	//////////////////////////////////////////////////
	//							Масив новини									  //
	//////////////////////////////////////////////////
	$news_name = "news_name_".$_SESSION['language'];
	$news_text = "news_text_".$_SESSION['language'];
	$news = $database_handle->prepare("SELECT table_news.*,table_profils.login FROM table_news INNER JOIN table_profils ON table_profils.id = table_news.author_id WHERE table_news.id = :id_news ");
	$news->bindParam('id_news', $id, PDO::PARAM_INT);
	$news->execute();
	$mas_news = $news->fetch();
	/////////////////////////////////////////////////
	//							Масив рейтенгу 								 //
	/////////////////////////////////////////////////
	$rating_PDO = $database_handle->prepare('SELECT * FROM table_rating WHERE id_news = :id_news');
	$rating_PDO->bindParam('id_news', $id, PDO::PARAM_INT);
	$rating_PDO->execute();
	$rating_count = $rating_PDO -> rowCount();
	$rating_mas = $rating_PDO -> fetchAll();
	$rating_all = 0;
	//Знаходження рейтингу новини
	if (!empty($rating_mas) ) {
		foreach ($rating_mas as $value) {
			$value['id_profils'] = (int)$value['id_profils'];
			$rating_all = $rating_all + $value['rating'];
	//Якщо існує логін то перевіряємо чи він голосував і ящо так то вертаємо оцінку
			if (isset($_SESSION['id'])) {
				if ($value['id_profils'] == $_SESSION['id'] ) {
					$rating_profile = $value['rating'];
				}
			}
		}
		$rating_news = sprintf("%.2f",$rating_all/$rating_count);
	}
	else {
		$rating_news = "not votes";
	}
	/////////////////////////////////////////////////
	//						Добавлення оцінки 							 //
	/////////////////////////////////////////////////
	if (!empty ($_POST['rating'])) {
		$rating = $_POST['rating'];
		$id_profils = $_SESSION['id'];
		$id_news = $_GET['news'];
		$rating_PDO = $database_handle->prepare('INSERT INTO table_rating(id_news, id_profils, rating) VALUES (:id_news, :id_profils, :rating)');
		$rating_PDO -> bindParam('id_news', $id_news, PDO::PARAM_INT);
		$rating_PDO -> bindParam('id_profils', $id_profils, PDO::PARAM_INT);
		$rating_PDO -> bindParam('rating', $rating, PDO::PARAM_INT);
		$rating_PDO -> execute();
		unset($_POST['rating']);
		$site = $_SERVER['REQUEST_URI'];
		header("Location:$site");
	}
	//////////////////////////////////////////////////
	//						Масив коментів										//
	//////////////////////////////////////////////////
	$news_comment = $database_handle->prepare('SELECT table_comment.*,table_profils.login FROM table_comment INNER JOIN table_profils ON table_profils.id = table_comment.author_id WHERE news_id = :id');
	$news_comment->bindParam('id', $id, PDO::PARAM_INT);
	$news_comment->execute();
	$mas_comment = $news_comment -> fetchAll();
	/////////////////////////////////////////////////
	//						Добавлення коменту							 //
	/////////////////////////////////////////////////
	if(!empty($_POST) && !empty($_SESSION['id'])) {
		$time = date('d F Y (H:i:s)');
		$author_id = $_SESSION['id'];
		$comment_name = trim(strip_tags($_POST['comment_name']));
		$comment_text = trim(strip_tags($_POST['comment_text']));
		if (!empty($comment_name) && !empty($comment_text)) {
			$comment = $database_handle->prepare('INSERT INTO table_comment(news_id, comment_name, comment_text, author_id, time)
				VALUES (:news_id, :comment_name, :comment_text, :author_id, :time)');
			$comment->bindParam('news_id', $id, PDO::PARAM_INT);
			$comment->bindParam('comment_name', $comment_name, PDO::PARAM_STR);
			$comment->bindParam('comment_text', $comment_text, PDO::PARAM_STR);
			$comment->bindParam('author_id', $author_id, PDO::PARAM_INT);
			$comment->bindParam('time', $time, PDO::PARAM_STR);
			$comment->execute();
			unset($_POST['comment_name'], $_POST['comment_text']);
			$site = $_SERVER['REQUEST_URI'];
			header("Location:$site");
		}
	}
}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset = "utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header ><a href = "index.php">MOBILE</a><?php include 'translate.html';?></header>
	<?php include 'form.html'; ?>
	<?php if (!empty($_GET['news'])): ?>
		<div class = "news_read">
			<h1><?php print $mas_news["$news_name"]; ?></h1>
			<br >

					<?php if(isset($rating_news )): ?>
							Rating news- <?php print $rating_news; ?> <br >
					<?php endif;?>
									<!--
					/////////////////////////////////////////////
					//				Рейтинг початок	профілю					 //
					/////////////////////////////////////////////
																										-->
					<?php if(isset($_SESSION['id']) ):
								if (isset($rating_profile)): ?>
									Your vote - <?php print $rating_profile; ?>
									<form method="POST">
										<input type = "hidden" name = "delete_rating" value = "<?php print $_GET['news']; ?>" >
										<input type="submit">
									</form>
					<?php else: ?>
							<form method = "POST">
								<input name = "news" type="hidden" value = "<?php print $value['id']; ?>">
					 			1<input name = "rating" type="radio" value="1">
					 			2<input name = "rating" type="radio" value="2">
					 			3<input name = "rating" type="radio" value="3">
					 			4<input name = "rating" type="radio" value="4">
					 			5<input name = "rating" type="radio" value="5">
					 			<br >
					 			<input type="submit" value = "vote">
							</form>
					<?php endif; ?>
				<?php endif; ?>
					<!-- Кінець -->
				<a href = "edit.php?news=<?php print $mas_news['id']?>">Edit</a>
				<p><?php print $mas_news['time']; ?></p>
				<?php print $mas_news[$news_text]; ?>
				<br >
				<a href = "profils.php?profils=<?php print $mas_news['author_id']?>"><?php print($mas_news['login']);?></a>
				<!--
				////////////////////////////////////////////////
				// 						Коменти												  //
				////////////////////////////////////////////////
																										-->
			  <?php foreach ($mas_comment as $value): ?>
					<h1><?php print($value['comment_name']);?></h1>
					<br>
					<?php print($value['time']);?>
					<br>
					<?php print($value['comment_text']);?>
					<br>
					<a href = "profils.php?profils=<?php print $value['author_id']?>"><?php print($value['login']);?></a>
					<br>
				<?php endforeach;?>
		</div>
		<?php if (!empty($_SESSION['id'])):?>
		<div class="add_comment">
			<form method = "POST">
				<input name ="comment_name" type="text" required>
				<br>
				<textarea name ="comment_text" required></textarea>
				<input type ="submit" value ="ADD" >
			</form>
		</div>
		<?php endif; ?>
	<?php else:?>
		<div class="error">NEMA</div>
	<?php endif; ?>
</body>
</html>