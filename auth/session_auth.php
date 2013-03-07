<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/20/13
 * Time: 10:04 PM
 * Licenced under the GPL v3
 */

function checkSessionAccess() {
	if(isset($_SESSION['username']) and isset($_SESSION['uid']))
		return true;

	return false;
}