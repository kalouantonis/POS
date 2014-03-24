<html>
<head>
	<title><?php echo  'Print ' . $page_title; ?></title>
</head>
<body onload="printPage()">

	<style>
	.info           {
	    width:35%;
	    float:left;
	    border:3px solid #ccc;
	    padding:0.5em;
	}

	.details        {
	    width:55%;
	    float:left;
	    border:3px solid #ccc; 
	    padding:0.5em;
	    height:43.6em;
	}

	table {
		width: 100%;
	}

	thead {
		text-align: left;
	}
			
	</style>

	<script type="text/javascript" charset="utf-8">

		function printPage() {
			window.print();
		}

	</script>

	<h1><?php echo $page_title; ?></h1>

	<br>
	<div class="info">
	<?php foreach($info_data as $display => $value): ?>

	<h3><?php echo $display . ':'; ?></h3>
	<p><?php echo $value; ?></p>
	<hr>

	<?php endforeach; ?>
	</div>

	<div class="details">
	<h3>Details:</h3>
	<?php if(isset($details)) : ?>
		<?php 

		$this->table->set_heading(array('Product Ref', 'Type', 'Subtype', 'Stock Level'));

		foreach($details as $row) { 
			$table_data[] = array(
				anchor("products/info/{$row['product']->id}", $row['product']->id),
				anchor("types/info/{$row['type']->id}", $row['type']->name),
				anchor("subtypes/info/{$row['subtype']->id}", $row['subtype']->name),
				$row['product']->stock
			);
		}

		echo $this->table->generate($table_data);
		?>	

	<?php else: ?>
		No Products assigned to this supplier!
	<?php endif; ?>

	<hr>

</body>
</html>