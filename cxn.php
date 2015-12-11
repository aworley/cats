<?php


function __autoload($class_name) 
{
    include $class_name . '.php';
}








// Identify what to do based on URI.
$question_position = strpos($_SERVER['REQUEST_URI'], '?');

if ($question_position)
{
	$uri = substr($_SERVER['REQUEST_URI'], 0, $question_position);
}

else
{
	$uri = $_SERVER['REQUEST_URI'];
}

$api_request = explode('/', $uri);
array_shift($api_request);  //  Remove '/'
array_shift($api_request);  //  Remove 'cxn/'
array_shift($api_request);  //  Remove 'cxn.php'

/*	If the URL has a trailing '/', an empty element will be tacked on the end of the $api_request
	array.  Clean this up with the following code.
*/
if ('' == $api_request[sizeof($api_request) - 1])
{
	array_pop($api_request);
}


if (sizeof($api_request) == 0)
{
	$api_request[0] = 'upload_prompt';
}

// Make sure the request is valid.
switch ($api_request[0])
{
	case 'upload_prompt':
	case 'match_fields':
	
	$controller = new $api_request[0];
	break;


	case 'build_php':
	
	$controller = new $api_request[0];
	echo $controller->run();
	exit();
	break;


	case 'http_auth':
	
	$controller = new $api_request[0];
	$controller->run();
	break;

	case 'select_db':
	
	mysql_connect('localhost', $_SERVER['PHP_AUTH_USER']);
	$controller = new $api_request[0];
	break;
	
	default:
	
	print_r($api_request);
	trigger_error('Invalid request');
	exit();	
}

include('header.php');
echo $controller->run();
include('footer.php');

exit();


/*

if (isset($_GET['action']))
{
	$action = 
}
else
$action = $_GET['action'];	
*/
