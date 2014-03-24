<?php 

function db_error()
{
	show_error("If this issue continues, keep calm and contact the Developer", 500,
		'Could not find information on this record.');

	// No need for other data, the traceback and the date in the logs should show needed data
	#log_message('error', 'There was a DB error!');
	log_message('error', 'There was a DB error: ' . mysql_error());
}

function direct_error()
{
	show_error('Please do not access page directly!', 500, 'Data not found!');
}