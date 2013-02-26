<?php
/**
 * Developer: Antonis Kalou
 * Date: 2/11/13
 * Time: 11:16 PM
 */

$page_title = "Welcome to the POS!";
include_once "templates/header.php";

require "db_model.php";

//session_commit(); // Commit session changes

// Debugging
/*
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['username'] = 'slacker';
$_POST['password'] = 'slacker';
*/

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_SESSION['username']) and !isset($_SESSION['password'])) {
        $sent_username = $_POST['username'];
        $sent_password = $_POST['password'];

	    $users_table = new DBModel('users');

        if ($user_data = $users_table->custQuery("SELECT * FROM users WHERE username='$sent_username'
                                AND password=PASSWORD('$sent_password')")) {

            $_SESSION['username'] = $user_data['username']; // Or sent password
	        $_SESSION['first_name'] = $user_data['first_name'];

	        // Will use this later when data is entered. User ID is recorded`
	        $_SESSION['uid'] = $user_data['id'];
            header("Location: http://{$_SERVER['HTTP_HOST']}/POS/index.php"); // Refresh page
        }
        else {
           echo 'Incorrect credentials. <a href="/POS/index.php">Try again</a>';
        }
    }
}  elseif((!isset($_SESSION['username'])) and (!isset($_SESSION['password']))) { // If not POST, then it is GET
	require "templates/form.php";
	$username_input = new InputField('text', 'username', 'Username');
	$password_input = new InputField('password', 'password', 'Password');
	$login_button = new InputField('submit', 'login', null, 'Login', false);

	$form = new Form(array($username_input, $password_input, $login_button),
		"http://{$_SERVER['HTTP_HOST']}/POS/index.php");

	// Render the form
	echo $form->render();
} else {
	echo    '<ul>
                <li><a href="/POS/suppliers.php">Suppliers</a></li>
                <li><a href="/POS/products.php">Products</a></li>
                <li><a href="/POS/types.php">Types</a></li>
            </ul>';
}


include_once "templates/footer.php";
