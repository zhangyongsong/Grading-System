<?php
	require_once('userFuncts.php');
	
	session_start();
	
	doHtmlHeader('Add Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayProblemAdder();
	doHtmlFooter();
?>
<?php
function displayProblemAdder()
{
	$conn=dbConnect();
	$instruName=$_SESSION['valid_user'];
	//query for instructor's course
	$query="SELECT course_code FROM enrollment WHERE username = '$instruName' ORDER BY course_code ASC";
	$result=$conn->query($query);
	if (!$result)
	{
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$courseCount = $result->num_rows;
	$conn->close();
?>
	

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
		if (validateRequired(problemFile, "Please upload a problem file.")==false)
		{
			return false;
		}
		else return true;
	}
	}
	</script>
	<h2>Add Problem</h2>
	<form action="newProblemParser.php" onsubmit="return validateForm(this)" method="post" enctype="multipart/form-data">
	Please create a problem for course <select name = "course">
												<?php for ($i = 0; $i < $courseCount; $i++)
													{
														$row = $result->fetch_assoc();
														echo '<option value='.$row['course_code'].'>'.$row['course_code'].'</option>';
													}
													$result->free();
												?> </select>here:<br/><br/>
		Upload XML file to add problems and test cases.<br/>
		<input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
		<label for="userfile">Select your file: </label>
		<input type="file" name="problemFile" id="problemFile"/><br/><br/><br/>
		<input type="submit" value="Submit" />&nbsp&nbsp&nbsp&nbsp
		<input type="reset" value="Reset" /><br/>
	</form>
	<p><a href="problem_with_proper_heading.txt" target="_blank">Sample Problem File</a></p>
<?php
}
?>
