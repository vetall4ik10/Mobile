<?php
ini_set("include_path", getenv("DOCUMENT_ROOT"). "/function");
include 'connected.php';
if ($_SESSION['access'] > 2) {
	$translate = $database_handle->prepare("SELECT * FROM table_translate ");
	$translate->execute();
	$mas_translate = $translate->fetchALL(PDO::FETCH_ASSOC);
	$statement_handle = $database_handle -> prepare("UPDATE table_translate SET eng = :eng, ukr = :ukr WHERE id = :id");
	foreach ($_POST as $key => $value) {
		$id = $key;
		$eng = $value['eng'];
		$ukr = $value['ukr'];
		$statement_handle->bindParam('id', $id,  PDO::PARAM_INT);
		$statement_handle->bindParam('eng', $eng,  PDO::PARAM_STR);
		$statement_handle->bindParam('ukr', $ukr,  PDO::PARAM_STR);
		$statement_handle->execute();
		$site = $_SERVER['REQUEST_URI'];
		header("Location:$site");
	}
}
?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8"/>
	<link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
	<header ><a href = "index.php">MOBILE</a></header>
<form method="POST">
	<table class="translating-table">
	<?php foreach ($mas_translate as $key => $value): ?>
	<?php	print $key; ?>
		<tr>
			<td> <?php  print $value['id'] ?> </td>
			<td class="row">
				<input type="text" disabled="disabled"  value = "<?php print $value['eng']?>" class="form-label">
				<input type="text" name="<?php print $value['id']?>[eng]" value = "<?php print $value['eng']?>" class="form-text" oninput="form_text_change();">
			</td>
			<td class="row">
				<input type="text" disabled="disabled"  value = "<?php print $value['ukr']?>" class="form-label">
				<input type="text" name="<?php print $value['id']?>[ukr]" value = "<?php print $value['ukr']?>" class="form-text" oninput="form_text_change();">
			</td>
		</tr>
	<?php endforeach;?>
	</table>
	<input type="submit">
</form>

</body>
</html>