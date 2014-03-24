<html>
<head>
	<title><?php echo  'Print ' . $page_title; ?></title>
</head>
<body onload="printPage()">

	<style>
		body {
		}
	</style>

	<h1><?php echo $page_title; ?></h1>


	<?php foreach($info_data as $display => $value): ?>

	<h3><?php echo $display . ':'; ?></h3>
	<p><?php echo $value; ?></p>
	<hr>

	<?php endforeach; ?>

	<script type="text/javascript">

	function printPage() {
		window.print();
	}

	</script>

</body>
</html>