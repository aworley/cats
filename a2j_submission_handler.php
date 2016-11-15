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
}

else
{
	echo "<h1>Your request has been recorded.  Your request tracking number is {$case_id}.</h1>";
}

?>