<?php 

class login_prompt 
{
	function run()
	{
		echo <<<EOF
	<h2>Log into the database</h2>
	<p>Before we begin, you need to log into the database so we have access to it.</p>
	<a class="btn btn-default btn-lg" href="/cxn/cxn.php/http_auth" role="button">Log In</a>
EOF;
	}
}