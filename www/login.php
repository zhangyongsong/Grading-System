<script type="text/javascript">
	onload = function(){
		input = document.getElementById("username");
		input.focus();
	};
</script>

<?php
	require_once('userFuncts.php');
	session_start();

	doHtmlHeader(getXmlValue('/GradingSystem/page/loginTitle'));
	echo "<div id='content'>";
	
	if(!isset($_SESSION['valid_user']))
	{
		displayLoginForm();
	}else{
		gotoUrl('index.php');
	}
	doHtmlFooter();
?>
<?php
function displayLoginForm()
{
?>	
<script type="text/javascript">

function validate_required(field,alerttxt)
{
with (field)
  {
  if (value==null||value=="")
    {alert(alerttxt);return false}
  else {return true}
  }
}

function validate_form(thisform)
{
with (thisform)
  {
  if (validate_required(username,"Username must be filled out!")==false)
    {username.focus();return false}
  else if (validate_required(password,"Password must be filled out!")==false)
    {password.focus();return false}
  }
}
</script>
	<form action = "index.php" onsubmit="return validate_form(this)" method = "post" id="loginForm">
	<p>Log in</p>
		<table id="loginTable">
			<tr>
				<td>Username: </td>
				<td><input type = "text" name= "username" id= "username" maxlength = "20" size = "25"/></td>
			</tr>
			<tr>
				<td>Password: </td>
				<td><input type = "password" name = "password" maxlength = "20" size = "25"/></td>
			</tr>
			<tr>
				<td>Domain:</td>
				<td><select name="domain" id="domain">
						<option value="student" selected>Student</option>
						<option value="instructor">Instructor</option>
						<option value="administrator">Admin</option>
					</select>
				</td>
			<tr>
			<tr>
				<td colspan="2"><input type = "submit" value = "Log in" id="button" /></td>
			</tr>
			<tr>
				<td colspan="2"><a href="forgotPassword.php">Forgot Password</a></td>
			</tr>
		</table>
	    
	</form>
	
<?php
}
?>
