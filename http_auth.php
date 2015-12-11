<?php

class http_auth 
{
	function run()
	{
		$realm_name = 'A2J connector';
		
		// HTTP Authentication
		if (!isset($_SERVER['PHP_AUTH_USER'])) 
		{		    
		    header('WWW-Authenticate: Basic realm="' . $realm_name . '"');
			header('HTTP/1.0 401 Unauthorized');
		    exit;
		}
		
		else if (!isset($_SERVER['PHP_AUTH_PW'])) 
		{
			header('WWW-Authenticate: Basic realm="' . $realm_name . '" stale="FALSE"');
			header('HTTP/1.0 401 Unauthorized');
			exit();
		}
		
		else 
		{
			$username = mysql_real_escape_string($_SERVER['PHP_AUTH_USER']);
			$password = mysql_real_escape_string(md5($_SERVER['PHP_AUTH_PW']));
			
			$resource = mysql_connect('localhost', $username);
			
			if (!$resource)
			{
				header('WWW-Authenticate: Basic realm="' . $realm_name . '" stale="FALSE"');
				header('HTTP/1.0 401 Unauthorized');
				exit();
			}
			
			else
			{
				header('Location: /cxn/cxn.php/select_db');
			}
		}
	}
}