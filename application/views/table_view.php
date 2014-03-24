<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/10/13
 * Time: 1:27 PM
 * Licenced under the GPL v3
 */

?>


<br />

<?php if(isset($message)): ?>
<p class="message"><?php echo $message; ?></p>
<?php endif; ?>

<br />
<strong> 
<?php echo isset($insert_location) ? anchor($insert_location, 'Insert New Item') : NULL; ?>
</strong>

<br /><br />
<?php if(isset($table_data)) : ?>

<?php $this->table->set_heading($headings); ?>

<?php echo $this->table->generate($table_data); ?>

<?php else: ?>
	<p class="error">No data found!</p>

<?php endif; ?>

<br>
<br>

<div id="page_nav">
<?php 

if(isset($curr_page) && $curr_page > 0) {
	$prev_page = $curr_page - 1;
	echo anchor("{$controller}/index/{$prev_page}", 'Previous') . '&nbsp;&nbsp;';
}

?>

<?php
if(isset($num_pages) && $num_pages > 1 && $curr_page != $num_pages -1) {
	$next_page = $curr_page + 1; 
	echo anchor("{$controller}/index/{$next_page}", 'Next');
}

?>
</div>

