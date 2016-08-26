<?php 

class upload_prompt 
{
	function run()
	{
		echo <<<EOF
	<form enctype="multipart/form-data" action="/cxn/cxn.php/match_fields" method="POST">
		<h2>Step 1:  Enter the Web Address (URL) for your CMS</h1>
  <div class="form-group">
    <label for="url">CMS URL</label>
    <input type="text" class="form-control" id="url" name="url" placeholder="https://pikasoftware.com/legalservices/">
    <p class="help-block">This should be the same URL you enter in your web browser to log into your CMS.</p>
  </div>
	<h2>Step 2:  Upload the A2J interview source files</h2>
  <div class="form-group">
	<p>Please upload the ZIP file for the interview that you want to connect to your CMS.
	This should be the ZIP file you downloaded from the A2J Author site.<br>
	<input type="file" name="zip">
	</p>
	
	<p>Optionally, upload a previously created A2J_ViewerAnswerSet.php file for this interview.  
	CATS will load your previous settings from this file.<br>
	<input type="file" name="previous_matches">
	</p>
	</div>
	<input type="submit" class="btn btn-default btn-lg" value="Click Here to Upload and Continue to Step 3">
	</form>
EOF;
	}
}