<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 3/9/13
 * Time: 3:18 PM
 * Licenced under the GPL v3
 */

if(isset($message)) {
	echo '<div class="message">' . $message . '</div>';
}

// The flash data from products->stock
$flashdata = $this->session->flashdata('flash_stock');

if($flashdata) {
	// Check if array
	if(is_array($flashdata))
		foreach($flashdata as $flash) 
			echo '<p class="message">'. $flash . '</p>';
	// Else single item
	else 
		echo '<p class="message">'. $flashdata . '</p>';
	}

?>

<br>

<p> Welcome back <?php echo $this->session->userdata('first_name'); ?></p>

<p>
	Last login from IP-Address: <?php echo $this->session->userdata('ip_address'); ?>
</p>

<code>If this is not you, please <?php echo anchor('home/logout', 'Logout'); ?></code>


<br><br>
<?php echo anchor('home/change_password', 'Change Password'); ?>