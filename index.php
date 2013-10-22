<?php
ini_set('include_path', getenv('DOCUMENT_ROOT'));
include 'function/connected.php';
include 'form.php';
include 'translate.php';
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
///////////////////////////////////////////////////////
//							Визначення сторінки 								 //
///////////////////////////////////////////////////////
if (!empty($_POST['current_page'])) {
	$_SESSION['current_page'] = $_POST['current_page'];
	}
else if (empty($_SESSION['current_page'])) {
	$_SESSION['current_page'] = 1;
}
///////////////////////////////////////////////////////
//	 Повернення 10 новин враховуючи номер сторінки   //
///////////////////////////////////////////////////////
$first = ($_SESSION['current_page']-1)*10;
$last = 10;
$news_name = "news_name_".$_SESSION['language'];
$news_text = "news_text_".$_SESSION['language'];
$news = $database_handle -> prepare("SELECT table_news.*,table_profils.login FROM table_news INNER JOIN table_profils ON table_profils.id = table_news.author_id LIMIT :first, :last ");
$news -> bindParam('first', $first, PDO::PARAM_INT);
$news -> bindParam('last', $last, PDO::PARAM_INT);
$news -> execute();
$mas_of_news = $news -> fetchAll(PDO::FETCH_ASSOC);
///////////////////////////////////////////////////////
//						Знаходження скільки новин у бд 				 //
///////////////////////////////////////////////////////
$news = $database_handle -> prepare("SELECT id FROM table_news");
$news -> execute();
$all_news = $news -> rowCount();
$limit_page = ceil($all_news / 10);//кількість сторінок
////////////////////////////////////////////////////////
//							Добавлення оцінки новині						  //
////////////////////////////////////////////////////////
if (isset ($_POST['rating']) && isset($_SESSION['id'])) {
	$rating = $_POST['rating'];
	$id_profils = $_SESSION['id'];
	$id_news = $_POST['news'];
	$rating_PDO = $database_handle->prepare('INSERT INTO table_rating(id_news, id_profils, rating) VALUES (:id_news, :id_profils, :rating)');
	$rating_PDO -> bindParam('id_news', $id_news, PDO::PARAM_INT);
	$rating_PDO -> bindParam('id_profils', $id_profils, PDO::PARAM_INT);
	$rating_PDO -> bindParam('rating', $rating, PDO::PARAM_INT);
	$rating_PDO -> execute();
	unset($_POST['rating']);
	$site = $_SERVER['REQUEST_URI'];
	header("Location:$site");
}

?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header >
		<a href = "index.php">MOBILE</a>
		<?php include 'translate.html';?>
	</header>
	<?php include 'form.html' ?>
	<div class="block1"> </div>
	<!--Пейжер-->
	<div class="pager">
		<form method = "post">
		  <select name = "current_page" value = "$_SESSION['current_page']" >
		    <?php for($i = 1; $i <= $limit_page; $i++): ?>
		      <?php if ($i == $_SESSION['current_page']) : ?>
		    		<option selected value = "<?php print $i; ?>"><?php print $i; ?></option>
		      <?php else: ?>
		    		<option value = "<?php print $i; ?>"><?php print $i; ?></option>
		    	<?php endif; ?>
		    <?php endfor; ?>
		  </select>
		  <input type = "submit" value="<?print translate('move') ?> "/>
		</form>
	</div>
	<!--Новини-->
	<div class = "all_news">
	 <a href="add.php">
	 	<div class="go_add"><?print translate('add news') ?></div>
	 </a>
<?php
////////////////////////////////////////////////////
// 				Знаходження та виведення рейтингу		    //
////////////////////////////////////////////////////
	foreach($mas_of_news as $key => $value):
	$rating_PDO = $database_handle->prepare('SELECT * FROM table_rating WHERE id_news = :id_news');
	$rating_PDO->bindParam('id_news', $value['id'], PDO::PARAM_INT);
	$rating_PDO->execute();
	$rating_count = $rating_PDO -> rowCount();
	$rating_mas = $rating_PDO -> fetchAll();
		if (!empty($rating_mas) ) {
			$rating_all = 0;
			foreach ($rating_mas as $array) {
				$rating_all = $rating_all + $array['rating'];
				if (isset($_SESSION['id'])) {
					if ($array['id_profils'] == $_SESSION['id']) {
					$rating_profile[$i] = $array['rating'];
					}
				}
			}
			$rating_news = sprintf("%.2f",$rating_all/$rating_count);
		}
		else {
			$rating_news = translate('not votes');
		}
?>
		<div class ="news">
				<h1><?php print $value[$news_name]; ?></h1>

				<?php if(isset($rating_news )): ?>
						<?print translate('Rating news'); ?>- <?php print $rating_news; ?> <br >
				<?php endif;?>
								<!--
				/////////////////////////////////////////////
				//				Рейтинг початок	профілю					 //
				/////////////////////////////////////////////
																									-->
				<?php if(isset($_SESSION['id']) ):
							if (isset($rating_profile[$i])): ?>
								<?print translate('Your vote')?> - <?php print $rating_profile[$i]; ?>
								<form method="POST">
									<input type = "hidden" name = "delete_rating" value = "<?php print $value['id']; ?>" >
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
				 			<input type="submit" value = "<?print translate('vote') ?>">
						</form>
				<?php endif; ?>
				<!-- Кінець -->
							 <?php if ($_SESSION['access'] > '2' ||  $value['id'] == $_SESSION['id']):?>
						<a href="edit.php?news=<?php print $value['id']; ?>"><?print translate('Edit') ?></a>
						<a href="delete-news.php?news=<?php print $value['id']; ?>"><?print translate('Delete') ?></a>
					<?php endif; ?>
				<?php endif; ?>
				<p> <?php print $value['time']; ?> </p>
				<?php
					if (strlen($value[$news_text]) > 155) {
						print substr($value[$news_text], 0, strpos($value[$news_text], ' ', 155)) ;
					}
					else {
						print $value[$news_text];
					}
				?>
				<br >
				<a href="news.php?news=<?php print $value['id']; ?>"> <?print translate('READ MORE') ?> </a>
				<br >
				<?print translate('author'); ?> - <a href ="profils.php?profils=<?php print $value['author_id'];?>"><?php print $value['login'];?> </a>
		</div>
	<?php endforeach; ?>
</div>
</body>
</html>