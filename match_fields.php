<?php

function build_match_options($prefix, $columns, $selected)
{
	$z = '' . $selected;
	
	foreach ($columns as $value)
	{
		$y = $prefix . "." . $value;
		
		if ($y == $selected)
		{
			$z .= '<option selected>';
		}
		
		else
		{
			$z .= '<option>';
		}
		
		$z .= $y . '</option>';
	}
	
	return $z;
}

class match_fields
{
	function run()
	{
		/*
			I used the following SQL to build the first few variables.
			
			set session group_concat_max_len = 8192;
			select group_concat(column_name) from information_schema.columns
			where table_name = 'cases' and table_schema = 'cms';
			select group_concat(column_name) from information_schema.columns
			where table_name = 'contacts' and table_schema = 'cms';
			select group_concat(column_name) from information_schema.columns
			where table_name = 'activities' and table_schema = 'cms';
		*/
		$case_columns = array('office', 'problem', 'sp_problem', 'status', 'open_date', 'funding', 'referred_by', 'intake_type', 'intake_user_id', 'income', 'assets', 'poverty', 'income_type0', 'annual0', 'income_type1', 'annual1', 'income_type2', 'annual2', 'income_type3', 'annual3', 'income_type4', 'annual4', 'asset_type0', 'asset0', 'asset_type1', 'asset1', 'asset_type2', 'asset2', 'asset_type3', 'asset3', 'asset_type4', 'asset4', 'adults', 'children', 'persons_helped', 'citizen', 'citizen_check', 'client_age', 'lsc_income_change', 'sex_assault', 'stalking', 'rural', 'elig_notes', 'cause_action', 'lit_status', 'judge_name', 'court_name', 'court_address', 'court_address2', 'court_city', 'court_state', 'court_zip', 'docket_number', 'referral_date', 'dom_viol', 'veteran_household');
		$contact_columns = array('first_name', 'middle_name', 'last_name', 'extra_name', 'alt_name', 'title', 'address', 'address2', 'city', 'state', 'zip', 'county', 'area_code', 'phone', 'phone_notes', 'area_code_alt', 'phone_alt', 'phone_notes_alt', 'email', 'org', 'birth_date', 'ssn', 'language', 'gender', 'ethnicity', 'notes', 'disabled', 'residence', 'marital', 'frail');
		$op_columns = array('first_name', 'middle_name', 'last_name', 'extra_name', 'alt_name', 'title', 'address', 'address2', 'city', 'state', 'zip', 'county', 'area_code', 'phone', 'phone_notes', 'area_code_alt', 'phone_alt', 'phone_notes_alt', 'email', 'org', 'notes');
		$note_columns = array('notes0', 'notes1', 'notes2', 'notes3', 'notes4', 'notes5', 'notes6', 'notes7', 'notes8', 'notes9');
		$lookup = array();
		
		if (!isset($_FILES['uploads']))
		{
			// The user bypassed the previous form.
			die('Steps 1 and 2 were skipped.');
		}
		
		if ($_FILES['uploads']['name'][0] == '')
		{
			// The user did not upload any files.
			die('No files were uploaded.');
		}
		
		if (sizeof($_FILES['uploads']['name']) > 2)
		{
			die('Too many files were uploaded.');
		}
		
		for ($i = 0; $i < sizeof($_FILES['uploads']['name']); $i++)
		{
			if ($_FILES['uploads']['type'][$i] == 'application/zip')
			{
				$zip_path = $_FILES['uploads']['tmp_name'][$i];
			}
			
			else
			{
				// This file is hopefully a previously build .php file.  Extract the
				// JSON lookup table from it, and use that to prepopulate the matching
				// table to save the user time.
				$s = file_get_contents($_FILES['uploads']['tmp_name'][$i]);
				$t = explode("\n", $s);
				$lookup = json_decode($t[4], 1);
			}
		}
		
		//TODO:  Check ['zip']['type'], ['error']
		$zip = new ZipArchive();
		
		$zip->open($zip_path);
		$xml = $zip->getFromName('Guide.xml');
		$a2j_file = new SimpleXMLElement($xml);
		//echo "<pre>=== A2J Answer File export ===\n";
		//print_r($a2j_file);
		
		$safe_url = htmlentities($_POST['url']);
		echo "
		<h2>Step 3:  Add custom fields</h2>
		<p class=\"help-block\">The data matching menus in Step 4 only include the 
		standard CMS fields as options.  If your CMS has custom database fields that 
		will receive data from your A2J interview, use this form to add new options
		to the data matching menus.</p>
		<p class=\"help-block\">To enter a new field option, first the name of the 
		field in the text box.  Then select the table	record where the field appears
		(Case, Client, Opposing Party, or Opposing Party's Attorney).  Then press 
		the Add Field button. 
		Your additions will appear at the very bottom of the menus.
		You can add as many new options as needed.</p>
		<form class=\"form-inline\" id=\"data_matching\">
	  <div class=\"form-group\">
		<label for=\"newOptionName\">Field Name</label>
		<input type=\"text\" id=\"newOptionName\" class=\"form-control\">
		<label class=\"radio-inline\">
		  <input type=\"radio\" name=\"table\" id=\"table0\" value=\"cases\" checked> Case
		</label>
		<label class=\"radio-inline\">
		  <input type=\"radio\" name=\"table\" id=\"table1\" value=\"contacts\"> Client
		</label>
		<label class=\"radio-inline\">
		  <input type=\"radio\" name=\"table\" id=\"table2\" value=\"op\"> Opposing Party
		</label>
		<label class=\"radio-inline\">
		  <input type=\"radio\" name=\"table\" id=\"table3\" value=\"opa\"> Opposing Party's Attorney
		</label>		
			</div>
			<button id=\"add\" class=\"btn btn-default\">Add Field</button>
			<span style=\"display: none\" id=\"myElem\">Field Added!</span>
			</form>
			<h2>Step 4:  Match fields</h2>
	<form action=\"/cxn/cxn.php/build_php\" method=\"POST\">
  <div class=\"form-group\">
    <label for=\"url\">CMS URL</label>
    <input type=\"text\" class=\"form-control\" id=\"url\" name=\"url\" placeholder=\"CMS URL\" value=\"{$safe_url}\">
  </div>
  	
	<div class=\"form-group\">
	<p class=\"help-block\">Here is a list of fields entered during the interview.  Match each one up
		to a field in your CMS database.</p>

	<table class=\"table table-striped\">
		<thead>
		<tr><th>Interview Field</th><th>CMS Field</th></tr>
		</thead>
		<tbody>
";
		$i = 0;
		foreach ($a2j_file->VARIABLES->VARIABLE as $val) 
		{
			$a = $val->attributes();
			// Remove spaces from the names of the HTML select elements.  Having
			// names in them makes the jquery code ugly.
			$menu_name = str_replace(" ", "_", $a['NAME']);
			$selected = null;
			
			if (array_key_exists($menu_name, $lookup))
			{
				$selected = $lookup[$menu_name];
			}
			
			echo "	
			<tr><td>{$menu_name}</td>
			<td>
		<select class=\"form-control\" name=\"{$menu_name}\" id=\"match{$i}\">
		<option></option>\n";
echo "<optgroup label=\"Client Information\">\n";
echo build_match_options('contacts', $contact_columns, $selected);
echo "</optgroup>\n";
echo "<optgroup label=\"Case Notes\">\n";
echo build_match_options('notes', $note_columns, $selected);
echo "</optgroup>\n";
echo "<optgroup label=\"Case Information\">\n";
echo build_match_options('cases', $case_columns, $selected);
echo "</optgroup>\n";
echo "<<optgroup label=\"Opposing Party\">\n";
echo build_match_options('op', $contact_columns, $selected);
echo "</optgroup>\n";
echo "<optgroup label=\"Opposing Party Attorney\">\n";
echo build_match_options('opa', $contact_columns, $selected);
echo "</optgroup>\n";
echo "<optgroup label=\"Custom Fields Added in Step 3\">\n";
echo "</optgroup>\n";
echo "</select>
<script>
$('#add').click(function(event){
	event.preventDefault();
	
	/* var optionExists = ($('#match" . $i . " option[value=' + $(this).val() + ']').length > 0);

	if(!optionExists)
	{ */
		var optionName = $('input[name=table]:checked', '#data_matching').val() + '.' + $('#newOptionName').val();
		$('#match" . $i . "').append(\"<option value='\"+optionName+\"'>\"+optionName+\"</option>\");
		$(\"#myElem\").show();
		setTimeout(function() { $(\"#myElem\").hide(); }, 5000);
	/* } */
});
</script>
		</td></tr>
";
		
		$i++;
		}
		





		echo <<<EOF
		</tbody>
	</table>

	</div>
	
	<h2>Download your completed PHP file</h2>
	<p>Click the button below to start the download the interview connector source code file.
		Once downloaded, save it to the folder on the web server where your interview resides.
		No additional configuration is needed.</p>
	<input type="submit" class="btn btn-default btn-lg" value="Download">
	</form>
EOF;

	}
}