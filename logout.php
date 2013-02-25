<?php
/**
 * Created by JetBrains PhpStorm.
 * User: slacker
 * Date: 2/11/13
 * Time: 11:24 PM
 * To change this template use File | Settings | File Templates.
 */

include_once "templates/header.php";

if(isset($_SESSION['username'])) {
    $_SESSION = array(); //Empty session data
    session_destroy();
    session_commit();
    header("Location: http://{$_SERVER['HTTP_HOST']}/POS/index.php"); // Redirect to index page

}