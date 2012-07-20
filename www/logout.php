<?php
	require_once('userFuncts.php');
	
	session_start();
	@$oldUser = $_SESSION['valid_user'];
	
	//store to test if they were logged in
	unset($_SESSION['valid_user']);
	unset($_SESSION['priv']);
	$resultDest = session_destroy();
	
	//start output html
	doHtmlHeader('Logging Out');
	doHtmlMenu();
	if(!empty($oldUser))
	{
		//if they were logged in and are now logged out
		if($resultDest)
		{
			echo 'Logged out.<br/>';
			gotoUrl('login.php');
		}
		else
			//they were logged in but could not be logged out
			echo 'Sorry, we could log you out<br/>';
	}
	else
	//if they weren't logged in but came to this page somehow
	{
		gotoUrl('login.php');
	}
	doHtmlFooter();
?>