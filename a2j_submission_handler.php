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
	require_once('JSON.php');
	$json = new Services_JSON;
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, $url);
	curl_setopt($c, CURLOPT_TIMEOUT, 60);
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
	curl_setopt($c, CURLOPT_USERPWD, "$username:$password");
	curl_setopt($c, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 2);	
	$post_string = $json->encode($data);
	curl_setopt($c,CURLOPT_POST, true);
	curl_setopt($c,CURLOPT_POSTFIELDS, $post_string);	
	$result=curl_exec($c);
	$status_code = curl_getinfo($c, CURLINFO_HTTP_CODE);
	curl_close ($c);
	echo $status_code;
	//if ( $status_code != 201 ) 
	if ($result != '1')
	{
    	die("An error occurred. URL: $url, status: $status_code, curl_error " . curl_error($c) . ", curl_errno " . curl_errno($c));
	}

	return $result;
}


$a2j_file = new SimpleXMLElement($_POST['AnswerKey']);
//print_r($a2j_file);
$case_record = array();
$contact_record = array();

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
		$usv = (string) $val->DateValue;
	}
	
	else if (isset($val->SelValue->MCValue))
	{
		$usv = (string) $val->SelValue->MCValue;
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
}

$bundle = array('case' => $case_record, 'client' => $contact_record);
pika_cms_transfer_v2_submit($bundle, $url, $username, $password);

if (false)
{
	echo "<h1>I'm sorry, but a system error has occurred.</h1>";
}

else
{
	echo "<h1>Your request has been recorded.  Your request tracking number is {$case_id}.</h1>";
}

?>