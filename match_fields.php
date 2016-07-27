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
		$case_columns = array('case_id', 'number', 'client_id', 'user_id', 'cocounsel1', 'cocounsel2', 'office', 'problem', 'sp_problem', 'status', 'open_date', 'close_date', 'close_code', 'reject_code', 'poten_conflicts', 'conflicts', 'funding', 'undup', 'referred_by', 'intake_type', 'intake_user_id', 'last_changed', 'created', 'income', 'assets', 'poverty', 'income_type0', 'annual0', 'income_type1', 'annual1', 'income_type2', 'annual2', 'income_type3', 'annual3', 'income_type4', 'annual4', 'asset_type0', 'asset0', 'asset_type1', 'asset1', 'asset_type2', 'asset2', 'asset_type3', 'asset3', 'asset_type4', 'asset4', 'adults', 'children', 'persons_helped', 'citizen', 'citizen_check', 'client_age', 'outcome', 'outcome_notes', 'outcome_income_after_service', 'outcome_income_no_service', 'outcome_assets_after_service', 'outcome_assets_no_service', 'outcome_debt_after_service', 'outcome_debt_no_service', 'ca_outcome_amount_obtained', 'ca_outcome_monthly_obtained', 'ca_outcome_amount_reduced', 'ca_outcome_monthly_reduced', 'lsc_income_change', 'just_income', 'main_benefit', 'sex_assault', 'stalking', 'case_county', 'rural', 'good_story', 'case_zip', 'elig_notes', 'cause_action', 'lit_status', 'judge_name', 'court_name', 'court_address', 'court_address2', 'court_city', 'court_state', 'court_zip', 'docket_number', 'date_filed', 'protected', 'why_protected', 'pba_id1', 'pba_id2', 'pba_id3', 'referral_date', 'compensated', 'thank_you_sent', 'date_sent', 'payment_received', 'program_filed', 'dollars_okd', 'hours_okd', 'destroy_date', 'dom_viol', 'veteran_household', 'source_db', 'in_holding_pen', 'doc1', 'doc2', 'vawa_served');
		$contact_columns = array('contact_id', 'first_name', 'middle_name', 'last_name', 'extra_name', 'alt_name', 'title', 'mp_first', 'mp_last', 'mp_alt', 'address', 'address2', 'city', 'state', 'zip', 'county', 'area_code', 'phone', 'phone_notes', 'area_code_alt', 'phone_alt', 'phone_notes_alt', 'email', 'org', 'birth_date', 'ssn', 'language', 'gender', 'ethnicity', 'notes', 'disabled', 'residence', 'marital', 'frail');
		$note_columns = array('act_id', 'act_date', 'act_time', 'act_end_time', 'hours', 'completed', 'act_type', 'category', 'case_id', 'user_id', 'pba_id', 'funding', 'summary', 'notes', 'last_changed', 'created', 'last_changed_user_id', 'om_code', 'ph_measured', 'ph_estimated', 'estimate_notes', 'act_end_date', 'problem', 'location', 'media_items');
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
		<tr><th>Interview Field</th><th>CMS Table</th><th>CMS Field</th></tr>
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
		</td><td>
		<select class=\"form-control\" name=\"{$menu_name}\" id=\"match{$i}\">
		<option></option>
<option label>cases</option>\n";
echo build_match_options('cases', $case_columns, $selected);
echo "<option label>contacts</option>\n";
echo build_match_options('contacts', $contact_columns, $selected);
echo "<option label>opposing party</option>\n";
echo build_match_options('op', $contact_columns, $selected);
echo "<option label>opposing party attorney</option>";
echo build_match_options('opa', $contact_columns, $selected);
echo "<option label>case notes</option>\n";
echo build_match_options('notes', $note_columns, $selected);
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