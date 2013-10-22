<?php
ini_set("include_path",getenv("DOCUMENT_ROOT")."/function");
include "connected.php";
?>
<link rel = "stylesheet" type = "text/css" href = "css/style.css">
<div class = "leftpane">

	<form method = "POST">
		<select name = "table_mobile">
			<option value ='table_nokia'>Nokia</option>
			<option value = 'table_HTC'>HTC</option>
		</select>
		<?php
		 if(!empty($_POST['table_mobile'])) {
		 	$table_mobile = $_POST['table_mobile'];
		 	$statement_handle = $database_handle->prepare("SELECT * FROM $table_mobile");
		 	$statement_handle -> execute();
		 	$mas_table = $statement_handle->fetchAll();
		 }
		?>
		<select name="mob_name">
		<?php foreach ($mas_table as $mas) : ?>
			<option value="<?php print $mas['id']?>"><?php print $mas['name']; ?></option>
		<?php endforeach; ?>
		</select>
		<input type="submit" value="Activate the changes">
	</form>
</div>

<?php
//if(!empty($_POST['mob_name']))
$z=$_POST['mob_name'];
print ($mas_table[$z-1]['id']);
?>