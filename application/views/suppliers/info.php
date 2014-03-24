<style>

.page {
	height: 49em;
	width: 80em;
}



</style>

<br>
<div id="info">
<?php foreach($info_data as $display => $value): ?>

<h3><?php echo $display . ':'; ?></h3>
<p><?php echo $value; ?></p>
<hr>

<?php endforeach; ?>
</div>

<div id="details">
<h3>Details:</h3>
<?php if(isset($details)) : ?>
	<?php 

	$this->table->set_heading(array('Product Ref', 'Type', 'Subtype', 'Stock Level'));

	foreach($details as $row) { 
		$table_data[] = array(
			anchor("products/info/{$row['product']->id}", $row['product']->id),
			anchor("types/info/{$row['type']->id}", $row['type']->name),
			anchor("subtypes/info/{$row['subtype']->id}", $row['subtype']->name),
			$row['product']->stock,
			anchor("products/edit/{$row['product']->id}", 'Edit'),
			anchor("products/info/{$row['product']->id}", 'Info'),
			// Change this after, no restore should be shown
			anchor("products/info/{$row['product']->id}", 'Delete')
		);
	}

	echo $this->table->generate($table_data);
	?>	

<?php else: ?>
	No Products assigned to this supplier!
<?php endif; ?>
<hr>

<br>

<h1>
<?php echo anchor("suppliers/edit/{$info_data['Reference Number']}", "Edit"); ?>
</h1>

<h1>
<?php echo anchor("suppliers/info/{$info_data['Reference Number']}/print", "Print"); ?>
</h1>

</div>