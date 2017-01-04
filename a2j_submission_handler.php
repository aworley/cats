<?php

function post_request($action, $data, $url, $username, $password)
{
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($c, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);	
	$post_string = "action={$action}&";
	$post_string .= 'payload='.urlencode(serialize($data));
	//set the number of POST vars, POST data
	curl_setopt($c,CURLOPT_POST, 2);
	curl_setopt($c,CURLOPT_POSTFIELDS, $post_string);	
	$status_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
	$result=curl_exec($c);
	curl_close ($c);
	return $result;
}

function pika_cms_transfer_v2_submit($data, $url, $username, $password)
{
	//require_once('JSON.php');
	//$json = new Services_JSON;
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($c, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);	
	$post_string = json_encode($data);
	curl_setopt($c,CURLOPT_POST, true);
	curl_setopt($c,CURLOPT_POSTFIELDS, $post_string);	
	$result=curl_exec($c);
	$status_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close ($c);
	echo $status_code;
	//if ( $status_code != 201 ) 
	if (false && $result != '1')
	{
    	die("An error occurred. URL: $url, status: $status_code, curl_error " . curl_error($c) . ", curl_errno " . curl_errno($c));
	}

	return $result;
}


$a2j_file = new SimpleXMLElement($_POST['AnswerKey']);
//print_r($a2j_file);
$case_record = array();
$contact_record = array();
$op_record = array();
$oc_record = array();
$notes_record = array();

foreach ($a2j_file->Answer as $val) 
{			
	//print_r($val);
	$a = $val->attributes();
	$a2j_field_name = str_replace(' ', '_', $a['name']);
	
	$usv = null;
	$cms_field_name = null;
	
	if (isset($val->TextValue))
	{
		$usv = (string) $val->TextValue;
	}
	
	else if (isset($val->DateValue))
	{
		$x = (string) $val->DateValue;
		$y = explode('/', $x);
		$usv = $y[1] . '/' . $y[0] . '/' . $y[2];
	}
	
	else if (isset($val->MCValue->SelValue))
	{
		$usv = (string) $val->MCValue->SelValue;
	}
	
	else if (isset($val->NumValue))
	{
		$usv = (string) $val->NumValue;
	}
	
	else if (isset($val->TFValue))
	{
		if ($val->TFValue == 'true')
		{
			$usv = '1';
		}
		
		else
		{
			$usv = '0';
		}
	}
	
	else if (isset($val->Text))
	{
		$usv = (string) $val->Text;
	}
		
	if (array_key_exists($a2j_field_name, $lookup))
	{
		$cms_field_name = $lookup[$a2j_field_name];
	}
	
	//echo "$a2j_field_name : $cms_field_name = $usv\n";
	
	
	$x = explode('.', $cms_field_name);
	if ($x[0] == 'cases')
	{
		$case_record[$x[1]] = $usv;
	}
	
	else if ($x[0] == 'contacts')
	{
		$contact_record[$x[1]] = $usv;
	}
	
	else if ($x[0] == 'op')
	{
		$op_record[$x[1]] = $usv;
	}
	
	else if ($x[0] == 'oc')
	{
		$oc_record[$x[1]] = $usv;
	}

	else if ($x[0] == 'notes')
	{
		$notes_record[$x[1]] = $usv;
	}
}

$bundle = array('case' => $case_record, 'client' => $contact_record);

if (sizeof($op_record) > 0)
{
	$bundle['op'] = $op_record;
}

if (sizeof($oc_record) > 0)
{
	$bundle['oc'] = $oc_record;
}

if (sizeof($notes_record) > 0)
{
	$bundle['notes'] = $notes_record;
}

$case_id = pika_cms_transfer_v2_submit($bundle, $url, $username, $password);

if (false)
{
	echo "<h1>I'm sorry, but a system error has occurred.</h1>";
	exit();
}

if (!isset($program_name))
{
	$program_name = 'Legal Services';
}

if (!isset($contact_email))
{
	$contact_email = 'we_forgot_to_set_the_contact_email_address@bestlegalaid.org';
}

/*	The HTML is enclosed in a Heredoc variable so that everything the program needs
	is encapsulated in a single file, making install easier.
*/
echo <<<EOT
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">

  <title>Thank you.  Your request has been successfully submitted.</title>
  <meta name="description" content="">
  <meta name="author" content="">
  <style>
	body {
	font-family: sans-serif;
	background: white;
	/*
	background: linear-gradient(to bottom, #ffffff 0%, #ffffff 25%, #c6c6c6 100%);
	background-repeat:   no-repeat;
	background-position: 0px 0px;
	*/
	}
	.masthead {
  display:block;
  margin-left:auto;
  margin-right:auto;
  	}
	h1 {
    text-align: center;
    font-size: 1.1em;
    }
	.main {
	background: #b8cade;
    width: 30%;
    margin: 0 auto;
    padding: 2em;
    margin-top: 3em;
    -webkit-box-shadow: 0px 0px 15px 0px rgba(50, 50, 50, 0.75);
	-moz-box-shadow:    0px 0px 15px 0px rgba(50, 50, 50, 0.75);
	box-shadow:         0px 0px 15px 0px rgba(50, 50, 50, 0.75);
	}
  </style>
	  

</head>

<body>
	<img src="program-logo.jpg" class="masthead">
	<h1>{$program_name}</h1>
	<div class="main">
	<p>Your request has been successfully submitted.</p>
	<p>Your request tracking number is <strong>{$case_id}</strong>.</p>
	<p>If you do not hear from {$program_name} by email or telephone within 3 business days, please contact us at <a href="mailto:{$contact_email}">{$contact_email}</a>.  Be sure the include the tracking number above in your email message.</p>
</body>
</html>
EOT;
exit();

?>
