<?php
function checkValidLogin()
{
	//see if somebody is logged in and notify them if not
	//will not check db
	if (!isset($_SESSION['valid_user']))
	{
		//not logged in!
		echo 'You are not logged in. <br/>';
		gotoUrl('login.php');
		doHtmlFooter();
		exit;
	}
}

function checkValidAdmin()
{
	//see if somebody has enough privilege
	if ((!isset($_SESSION['priv'])) || ($_SESSION['priv'] != 'administrator'))
	{
		echo 'You are not an adminstrator. <br/>';
		gotoUrl('index.php');
		doHtmlFooter();
		exit;
	}
}

function checkValidInstru()
{
	if ((!isset($_SESSION['priv'])) || ($_SESSION['priv'] != 'instructor'))
	{
		echo 'You are not an instructor. </br>';
		gotoUrl('index.php');
		doHtmlFooter();
		exit;
	}
}
?>