<?php

class build_php
{
	function run()
	{
		$lookup = $_POST;
		unset($lookup['url']);
		
		header("Content-Type: text/plain");
		echo "<?php\n";
		echo "\$username = '';\n";
		echo "\$password = '';\n";
		echo "\$lookup = " . var_export($lookup, true) . ";\n";
		echo "\$url = '" . $_POST['url'] . '/services/transfer_case_v2.php' . "';\n";		
		echo "?>";
		echo file_get_contents('a2j_submission_handler.php');
		exit();
		
		//echo print_r($_POST); print_r($_GET); exit();
		//TODO:  Check ['zip']['type'], ['error']
		$zip = new ZipArchive();
		
		$zip->open($_FILES['zip']['tmp_name']);
		$xml = $zip->getFromName('Guide.xml');
		$a2j_file = new SimpleXMLElement($xml);
		//echo "<pre>=== A2J Answer File export ===\n";
		//print_r($a2j_file);
		
		$safe_url = htmlentities($_POST['url']);
		echo "
  <div class=\"form-group\">
    <label for=\"url\">CMS URL</label>
    <input type=\"text\" class=\"form-control\" id=\"url\" placeholder=\"CMS URL\" value=\"{$safe_url}\">
  </div>
  	<h2>Step 3:  Match fields</h2>
	<div class=\"form-group\">
	<p class=\"help-block\">Here is a list of fields entered during the interview.  Match each one up
		to a field in your CMS database.</p>
		
	<table class=\"table table-striped\">
		<thead>
		<tr><th>Interview Field</th><th>CMS Table</th><th>CMS Field</th></tr>
		</thead>
		<tbody>
";

		foreach ($a2j_file->VARIABLES->VARIABLE as $val) 
		{			
			//print_r($val);
			$a = $val->attributes();
			echo "	
			<tr><td>{$a['NAME']}</td>
		<td>
		</td><td>
		<select class=\"form-control\">
		<option selected></option>
<option label>cases</option>
<option>cases.case_id</option>
<option>cases.number</option>
<option>cases.client_id</option>
<option>cases.user_id</option>
<option>cases.cocounsel1</option>
<option>cases.cocounsel2</option>
<option>cases.office</option>
<option>cases.problem</option>
<option>cases.sp_problem</option>
<option>cases.status</option>
<option>cases.open_date</option>
<option>cases.close_date</option>
<option>cases.close_code</option>
<option>cases.reject_code</option>
<option>cases.poten_conflicts</option>
<option>cases.conflicts</option>
<option>cases.funding</option>
<option>cases.undup</option>
<option>cases.referred_by</option>
<option>cases.intake_type</option>
<option>cases.intake_user_id</option>
<option>cases.last_changed</option>
<option>cases.created</option>
<option>cases.income</option>
<option>cases.assets</option>
<option>cases.poverty</option>
<option>cases.income_type0</option>
<option>cases.annual0</option>
<option>cases.income_type1</option>
<option>cases.annual1</option>
<option>cases.income_type2</option>
<option>cases.annual2</option>
<option>cases.income_type3</option>
<option>cases.annual3</option>
<option>cases.income_type4</option>
<option>cases.annual4</option>
<option>cases.asset_type0</option>
<option>cases.asset0</option>
<option>cases.asset_type1</option>
<option>cases.asset1</option>
<option>cases.asset_type2</option>
<option>cases.asset2</option>
<option>cases.asset_type3</option>
<option>cases.asset3</option>
<option>cases.asset_type4</option>
<option>cases.asset4</option>
<option>cases.adults</option>
<option>cases.children</option>
<option>cases.persons_helped</option>
<option>cases.citizen</option>
<option>cases.citizen_check</option>
<option>cases.client_age</option>
<option>cases.outcome</option>
<option>cases.lsc_income_change</option>
<option>cases.just_income</option>
<option>cases.main_benefit</option>
<option>cases.sex_assault</option>
<option>cases.stalking</option>
<option>cases.case_county</option>
<option>cases.rural</option>
<option>cases.good_story</option>
<option>cases.case_zip</option>
<option>cases.elig_notes</option>
<option>cases.cause_action</option>
<option>cases.lit_status</option>
<option>cases.judge_name</option>
<option>cases.court_name</option>
<option>cases.court_address</option>
<option>cases.court_address2</option>
<option>cases.court_city</option>
<option>cases.court_state</option>
<option>cases.court_zip</option>
<option>cases.docket_number</option>
<option>cases.date_filed</option>
<option>cases.protected</option>
<option>cases.why_protected</option>
<option>cases.pba_id1</option>
<option>cases.pba_id2</option>
<option>cases.pba_id3</option>
<option>cases.referral_date</option>
<option>cases.compensated</option>
<option>cases.thank_you_sent</option>
<option>cases.date_sent</option>
<option>cases.payment_received</option>
<option>cases.program_filed</option>
<option>cases.dollars_okd</option>
<option>cases.hours_okd</option>
<option>cases.destroy_date</option>
<option>cases.dom_viol</option>
<option>cases.veteran_household</option>
<option>cases.source_db</option>
<option>cases.in_holding_pen</option>
<option>cases.doc1</option>
<option>cases.doc2</option>
<option>cases.vawa_served</option>
<option>cases.litc_ci_1</option>
<option>cases.litc_ci_2</option>
<option>cases.litc_ci_3</option>
<option>cases.litc_ci_4</option>
<option>cases.litc_ci_5</option>
<option>cases.litc_ci_6</option>
<option>cases.litc_ci_7</option>
<option>cases.litc_ci_8</option>
<option>cases.litc_ci_9</option>
<option>cases.litc_ci_10</option>
<option>cases.litc_ci_11</option>
<option>cases.litc_ci_12</option>
<option>cases.litc_ci_13</option>
<option>cases.litc_ci_14</option>
<option>cases.litc_ci_15</option>
<option>cases.litc_ci_16</option>
<option>cases.litc_ci_17</option>
<option>cases.litc_ci_18</option>
<option>cases.litc_ci_19</option>
<option>cases.litc_ci_20</option>
<option>cases.litc_ci_21</option>
<option>cases.litc_ci_22</option>
<option>cases.litc_ci_23</option>
<option>cases.litc_ci_24</option>
<option>cases.litc_ci_25</option>
<option>cases.litc_ci_26</option>
<option>cases.litc_ci_27</option>
<option>cases.litc_ci_28</option>
<option>cases.litc_ci_29</option>
<option>cases.litc_ci_30</option>
<option>cases.litc_ci_31</option>
<option>cases.litc_ci_32</option>
<option>cases.litc_ci_33</option>
<option>cases.litc_ci_34</option>
<option>cases.litc_ci_35</option>
<option>cases.litc_ci_36</option>
<option>cases.litc_ci_37</option>
<option>cases.litc_ci_38</option>
<option>cases.litc_ci_39</option>
<option>cases.litc_ci_40</option>
<option>cases.litc_ci_41</option>
<option>cases.litc_ci_42</option>
<option>cases.litc_ci_43</option>
<option>cases.litc_ci_44</option>
<option>cases.litc_ci_45</option>
<option>cases.litc_ci_46</option>
<option>cases.litc_ci_47</option>
<option>cases.litc_ci_48</option>
<option>cases.litc_ci_49</option>
<option>cases.litc_ci_50</option>
<option>cases.litc_ci_51</option>
<option>cases.litc_ci_52</option>
<option>cases.litc_ci_53</option>
<option>cases.litc_ci_54</option>
<option>cases.litc_ci_55</option>
<option>cases.litc_ci_56</option>
<option>cases.litc_ci_57</option>
<option>cases.litc_ci_58</option>
<option>cases.litc_ci_59</option>
<option>cases.litc_ci_60</option>
<option>cases.litc_poa_irs</option>
<option>cases.litc_irs_funct</option>
<option>cases.litc_controversy_gt50k</option>
<option>cases.litc_irs_matter</option>
<option>cases.litc_tax_years_involved</option>
<option>cases.litc_taxpayer_esl</option>
<option>cases.litc_joint_rep</option>
<option>cases.litc_rep_vol</option>
<option>cases.litc_stm_handled</option>
<option>cases.litc_taxpayer_compliance_filing</option>
<option>cases.litc_taxpayer_compliance_collection</option>
<option>cases.litc_refund_amount</option>
<option>cases.litc_corrected_liabilities</option>
<option>cases.google_drive_folder_id</option>
<option label>contacts</option>
<option>contacts.contact_id</option>
<option>contacts.first_name</option>
<option>contacts.middle_name</option>
<option>contacts.last_name</option>
<option>contacts.extra_name</option>
<option>contacts.alt_name</option>
<option>contacts.title</option>
<option>contacts.mp_first</option>
<option>contacts.mp_last</option>
<option>contacts.mp_alt</option>
<option>contacts.address</option>
<option>contacts.address2</option>
<option>contacts.city</option>
<option>contacts.state</option>
<option>contacts.zip</option>
<option>contacts.county</option>
<option>contacts.area_code</option>
<option>contacts.phone</option>
<option>contacts.phone_notes</option>
<option>contacts.area_code_alt</option>
<option>contacts.phone_alt</option>
<option>contacts.phone_notes_alt</option>
<option>contacts.email</option>
<option>contacts.org</option>
<option>contacts.birth_date</option>
<option>contacts.ssn</option>
<option>contacts.language</option>
<option>contacts.gender</option>
<option>contacts.ethnicity</option>
<option>contacts.notes</option>
<option>contacts.disabled</option>
<option>contacts.residence</option>
<option>contacts.marital</option>
<option>contacts.frail</option>
</select>
		</td></tr>
";
		}




		echo <<<EOF
		</tbody>
	</table>

	</div>
	
	<h2>Download your completed PHP file</h2>
	<p>Click the button below to start the download the interview connector source code file.
		Once downloaded, save it to the folder on the web server where your interview resides.
		No additional configuration is needed.</p>
	<input class="btn btn-default btn-lg" value="Download">
EOF;

	}
}