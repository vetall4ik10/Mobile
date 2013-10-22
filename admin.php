<?php
	ini_set('include_path', getenv('DOCUMENT_ROOT'));
  include 'function/connected.php';
  include 'form.php';
	if ($_SESSION['access'] == 4) {
		if (!empty($_POST['current_page'])) {
			$_SESSION['current_page'] = $_POST['current_page'];
		}
		else {
			$_SESSION['current_page'] = 1;
		}
		$first = ($_SESSION['current_page']-1)*10;
		$last = 100;
		$profils = $database_handle -> prepare("SELECT login, id FROM table_profils LIMIT :first, :last ");
		$profils -> bindParam('first', $first, PDO::PARAM_INT);
		$profils -> bindParam('last', $last, PDO::PARAM_INT);
		$profils -> execute();
		$limit = $profils -> rowCount();
		$mas_profils = $profils -> fetchALL();
		$profils = $database_handle -> prepare("SELECT login FROM table_profils ");
		$profils -> execute();
		$all_profils = $profils -> rowCount();
		$limit_page = ceil($all_profils / 10);
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header ><a href = "index.php">MOBILE</a><?php include 'translate.php';?></header>
	<?php include 'form.html'; ?>
	<?php if ($_SESSION['access'] == 4): ?>
	<div class="admin">
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
  <input type = "submit" value="Move "/>
</form>
<table>
<?php foreach ($mas_profils as $mas_profils): ?>
	<tr>
	 <td> <?php print $mas_profils['login']; ?></td>
	 <td> <a href="edit-profil.php?profils=<?php print $mas_profils['id']; ?>">edit</a> </td>
	 <td> <a href="profils.php?profils=<?php print $mas_profils['id']; ?>">profil</a> </td>
	</tr>
<?php endforeach; ?>
</table>
</div>
<?php endif;?>
</body>
</html>