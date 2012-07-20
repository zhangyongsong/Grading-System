<?php 
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Change Password');
	doHtmlMenu();
	checkValidLogin();
	
	//create short var names
	if(isset($_SESSION['valid_user']) && isset($_POST['newPassword']))
	{
		$username=$_SESSION['valid_user'];
		$password=$_POST['newPassword'];
	}
	else
	{
		gotoUrl('changePassword.php');
		exit;
	}
	
	//connect db
	$conn=dbConnect();
	$query = "update user set password = sha1('$password') where username = '$username'";
	$result=$conn->query($query);
	if ($result)
	{
		echo '<h2>Change Password</h2><br/>';
		echo 'Your password has changed successfully.<br/>';
	}
	else
	{
		alertGoback('An error occurred in database!');
		gotoUrl('changePassword.php');
		exit;
	}
	doHtmlFooter();	
?>