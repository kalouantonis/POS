<?php
/**
 * Created by JetBrains PhpStorm.
 * Developed By: Antonis Kalou
 * Date: 2/20/13
 * Time: 5:10 PM
 * Licenced under the GPL v3
 */

require "templates/header.php";

require "templates/table.php";

$entry = new Row(array(0=>'This', 3 => 'test', 1 => 'for', 2 => 'a'));
$entry2 = new Row(array(0=> 'These', 1 => 2, 2=> 'rows', 3=>'exist'));
$table = new Table(array('First', 'Second', 'Third', 'Fourth'), array($entry, $entry2));
echo $table->render();

require "templates/footer.php";