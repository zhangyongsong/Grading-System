<!-- This file is used to read text file for user registration uploaded by administrator.-->
<?php
	require_once('userFuncts.php');
	
	session_start();
	doHtmlHeader('');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	
	//Ask admin to upload text files for group registration
	displayGroupRegistration();
	doHtmlFooter();
?>
<?php
function displayGroupRegistration()
{
	$conn=dbConnect();
	$query='SELECT * FROM course';
	$result=$conn->query($query);
	$resultNumber = $result->num_rows;
	$conn->close();
?>
<script type="text/javascript">
function validateRequired(field)
{
with (field)
	{
		if (value==null||value=="")
			return false;
		else return true;
	}
}
function validateForm(thisform)
{
with (thisform)
{
	if (validateRequired(registrationFile)==false && validateRequired(usernames)==false)
	{
		alert("Please upload a registration file or fill in quick user registration text box.");
		return false;
	}
	else return true;
}
}
</script>
<h2>User Registration</h2>
<form name="form1" action="newUser.php" onsubmit="return validateForm(this)" method="post" enctype="multipart/form-data">
	<h4>Privileges for Registration</h4>
	<input type="radio" name="priv" value="administrator" onclick="disableField()" checked="true"/>Administrator<br/>
<?php
	if($resultNumber>0)
	{
?>
	<input type="radio" name="priv" value="instructor" onclick="enableField()" />Instructor<br/>
	<input type="radio" name="priv" value="student" onclick="enableField()"  />Student<br/>
	<h4>Select a course</h4>
	<select name="course" id="course" disabled="true">
<?php
		for($i=0; $i<$resultNumber; $i++)
		{
			$row=$result->fetch_assoc();
?>
			<option value="<?php echo $row['course_code'];?>"><?php echo $row['course_code'].'	'.$row['course_name'];?></option>
<?php
		}
?>

	</select>
	<script language="javascript">
		function enableField()
		{
			document.form1.course.disabled=false;
		}
		function disableField()
		{
			document.form1.course.disabled=true;
		}
 	</script>

<?php
	$result->free();
	
	}
	else
	{
?>
<?php
	}
?>
	<h4>Upload Text File for Group Registration</h4>
	<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
	<label for="userfile">Select your file: </label>
	<input type="file" name="registrationFile" id="registrationFile" /><br/><br/><br/>
	<h4>Enter Username for Quick User Registration</h4>
	<label for="singleUser1">Fill in usernames here, separated by space: </label><br/>
	<textarea name="usernames" id="usernames" rows="8" cols="40"></textarea><br/><br/>
	<input type="submit" value="Submit">&nbsp&nbsp&nbsp&nbsp<input type="reset" value="Reset" /><br/>
</form>
<?php
}
?>
