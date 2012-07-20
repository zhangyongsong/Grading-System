<?php
	require_once('userAuthentication.php');
	require_once('utilityFunctions.php');
	require_once('output.php');

	session_start();
	doHtmlHeader('');
	doHtmlMenu();
	checkValidLogin();
	
	displayChangePwd();
	doHtmlFooter();
?>
<?php
	function displayChangePwd()
{
?>
<h2>Change Password</h2><br/>
<h4>Please enter your old password and new password.</h4>
<script type="text/javascript">
function chkNewPwd() { 
  var old = document.getElementById("oldPassword");
  var init = document.getElementById("password");
  var sec = document.getElementById("password2");

//  if(init.value.length<6 || init.value.length >16)
//  {
//	alert("New password length must between 6 to 16 chars. Please enter again.");
//	init.focus();
//	init.select();
//	return false;
//  } 
//  else 
  if(init.value == old.value){
     alert("You did not choose a new password.");
	init.focus();
	init.select();
	return false
  }
  else if (init.value != sec.value) {
    alert("The two passwords you entered are not the same \n" +
          "Please re-enter both now");
    sec.focus();
    sec.select();
    return false;
  } 
  else
    return true;
}
//function chkOldPwd()
//{
//	var pwd = document.getElementById("oldPassword");
//	if(pwd.value.length<6 || pwd.value.length>16)
//	{
//		alert("You old password is not valid!");
//		pwd.focus();
//		return false;
//	}
//	else 
//		return true;
//}

function checkNewPwd()
{
	if(chkNewPwd()==false)
		return false;
	else return true;
}

</script>
<form action="newPassword.php" onsubmit="return checkNewPwd()" method="post">
	<table border = "0">
		<tr>
			<td>Please enter your current password: </td>
			<td><input type="password" name ="oldPassword" id="oldPassword" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td>Please enter your new password: </td>
			<td><input type="password" name="newPassword" id="password" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td>Please re-enter your new password: </td>
			<td><input type="password" name="newPassword2" id="password2" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Submit"/></td>
		</tr>
	</table>
</form>
<?php
}
?>
