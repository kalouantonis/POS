<?php foreach($info_data as $display => $value): ?>

<h3><?php echo $display . ':'; ?></h3>
<p><?php echo $value; ?></p>
<hr>

<?php endforeach; ?>

<?php if(isset($controller)) :  ?>
<h1>
<?php echo anchor("{$controller}/edit/{$info_data['Reference Number']}", "Edit"); ?>
</h1>

<h1>
<?php echo anchor("{$controller}/info/{$info_data['Reference Number']}/print", "Print"); ?>
</h1>

<?php endif; ?>
