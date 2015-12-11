<?php

class select_db
{
	function run()
	{
		echo <<<EOF
	<h2>Select your Pika CMS database</h2>	
	<p>Select the database to which the interviews should be saved.</p>
	<div class="form-group">
	<form method="GET" action="/cxn/cxn.php/match_fields">
	<input type="submit">
EOF;
		$result = mysql_query("SHOW DATABASES");
		
		while ($row = mysql_fetch_assoc($result))
		{
			echo "<div class=\"radio\"><label><input type=\"radio\" name=\"db\" value=\"{$row['Database']}\">{$row['Database']}</label></div>\n";
		}
		/*
	<label><input type="radio" name="a"> western_michigan</label>
	<label><input type="radio" name="a"> northern_michigan</label>
	<label><input type="radio" name="a"> eastern_michigan</label>
	<label><input type="radio" name="a"> south_east_michigan</label>
	</div>
*/
		echo "</form>\n<div>\n";
	}
}