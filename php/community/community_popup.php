<?php
session_start();
include_once("../connections/main.php");

$cid = $_SESSION['cid'];
if(isset($_GET['id'])){
	$qry = $conn->query("SELECT * FROM topics where id=".$_GET['id'])->fetch_array();
	foreach($qry as $k =>$v){
		$$k = $v;
	}
}

?>

<div class="container-fluid">
	<form action="" id="manage-topic">
				<input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id']:'' ?>" class="form-control">
		<div class="row form-group">
			<div class="col-md-8">
				<label class="control-label">Title</label>
				<input type="text" name="title" class="form-control" value="<?php echo isset($title) ? $title:'' ?>">
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-8">
				<label class="control-label">Tags/Category</label>
				<select name="category_ids[]" id="category_ids" multiple="multiple" class="custom-select select2">
					<option value=""></option>
				<?php
				$tag = $conn->query("SELECT * FROM categories order by name asc");
				while($row= $tag->fetch_assoc()):
				?>
					<option value="<?php echo $row['id'] ?>" <?php echo isset($category_ids) && in_array($row['id'], explode(",",$category_ids)) ? "selected" : '' ?>><?php echo $row['name'] ?></option>
			<?php endwhile; ?>
			</select>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-md-12">
				<label class="control-label">Content</label>
				<textarea name="content" class="text-jqte"><?php echo isset($content) ? $content : '' ?></textarea>
			</div>
		</div>
	</form>
</div>
<script>
    $('.text-jqte').jqte();
</script>