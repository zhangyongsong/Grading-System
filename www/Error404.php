<?php
	require_once('userFuncts.php');
	// Header 
	doHtmlHeader(getXmlValue('/GradingSystem/page/problemTitle'));
	
	echo '<br/><h1>Error occurred... </h1>';
	echo '<br/><p>Your requested page is not found.</p><p>If you encounter any problem, please contact us.</p>';
	
	doHtmlfooter();
?>
