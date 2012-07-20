<?php
	require_once('userAuthentication.php');
	require_once('utilityFunctions.php');
	require_once('output.php');

	session_start();
	doHtmlHeader('');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	
	displayCourseAdder();
	doHtmlFooter();
?>
<?php
function displayCourseAdder()
{
?>
<h2>Add Course</h2>
<h4>Please fill in course code and course name.</h4>
<script type="text/javascript">
function validateRequired(field,alerttxt)
{
with (field)
{
	if (value==null||value=="")
	{
		alert(alerttxt);
		return false;
	}
	else return true;
}
}
function validateForm(thisform)
{
with (thisform)
{
	if (validateRequired(coursecode, "Please fill in course code.")==false)
	{
		coursecode.focus();
		return false;
	}
	else if (validateRequired(coursename, "Please fill in course name.")==false)
	{
		coursename.focus();
		return false
	}
	else return true;
}
}
</script>
<form action="newCourse.php" onsubmit="return validateForm(this)" method="post">
	<table border = "0">
		<tr>
			<td>Please enter course code: </td>
			<td><input type="text" name ="coursecode" id="coursecode" maxlength="20" size="20"/></td>
		</tr>
		<tr>
			<td>Please enter coursename: </td>
			<td><input type="coursename" name="coursename" id="coursename" maxlength="100" size="20"/></td>
		</tr>
		<tr>
			<td colspan="2"><input type="submit" value="Submit"/></td>
		</tr>
	</table>
</form>
<?php
}
?>
