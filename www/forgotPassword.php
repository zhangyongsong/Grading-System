<?php
	require_once('userFuncts.php');
	session_start();
	session_unset();
	session_destroy();
	 
	doHtmlHeader('Reset Password');
	echo "<div id='content'>";
	
	forgotForm();
	doHtmlFooter();

function forgotForm(){
?>
	<form method="post" action="resetPassword.php" id="loginForm">
	<table id="forgotTable">
	<tr><td colspan="2"><h3>Reset your password</h3></tr>
	<tr><td>Enter your username: <td><input type="text" name="username"></td></tr>
	
	<tr>
		<td colspan="2"><input type="submit" value="Submit" id="button"/></td>
	</tr>
	<tr><td colspan="2"><span>The new password will be sent to your NTU email account.</span></td></tr>
	<tr><td colspan="2"> <a href='login.php'>Or log in here</a></td></tr>
	</table>
	</form>
<?php
}
?>
