<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Edit Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayEditProblem();
	doHtmlFooter();
?>
<?php
function displayEditProblem()
{
	echo '<h2>Edit Problem</h2>';
	$conn = dbConnect();
	if (isset($_GET['pid']) && is_numeric($_GET['pid']))
		$pid = $_GET['pid'];
	else
	{
		gotoUrl('problem.php');
		exit;
	}
	
	//check validity of pid
 	$instruName = $_SESSION['valid_user'];
	$result = dbQuery($conn, "SELECT problem.* FROM problem INNER JOIN enrollment ON problem.course_code = enrollment.course_code
				 WHERE enrollment.username = '$instruName' AND problem.p_id = $pid");
	if (!$result)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('problem.php');
		exit;
	}
	if ($result->num_rows != 1)
		echo '<h4>Error: The target problem is not found.</h4>';
	else
	{
		$row = $result->fetch_assoc();
?>
	<script type="text/javascript">
	onload = function()
	{
		document.getElementById("<?php echo $row['lang']?>").selected = true;
	}
	</script>
	<form action="modifyProblem.php" method="post">
	<table class="result">
	<tr>
		<td>Edit Problem Name:<br/>(max. 30 chars)</td>
		<td><input type = "text" name = "problemName" maxlength = "30" size = "45" value = "<?php echo htmlspecialchars($row['p_name']);?>"/></td>
	</tr>
	<tr>
		<td>Edit Language: </td>
		<td><select id = "language" name = "language">
				<option value = "0">--select Language--</option>
				<option id="Java" value="Java">Java</option>
				<option id="C++" value="C++">C++</option>
			</select>
		</td>
	</tr>
	<tr>
		<td>Edit Brief Description: </td>
		<td><textarea name = "explanation" cols = "40" rows = "2"><?php echo htmlspecialchars($row['explanation']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Problem Description: </td>
		<td><textarea name = "description" cols = "40" rows = "7"><?php echo htmlspecialchars($row['description']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Input Description: </td>
		<td><textarea name = "input" cols = "40" rows = "4"><?php echo htmlspecialchars($row['input']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Output Description: </td>
		<td><textarea name = "output" cols = "40" rows = "4"><?php echo htmlspecialchars($row['output']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Sample Input: </td>
		<td><textarea name = "sampleInput" cols = "40" rows = "4"><?php echo htmlspecialchars($row['sample_input']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Sample Output: </td>
		<td><textarea name = "sampleOutput" cols = "40" rows = "4"><?php echo htmlspecialchars($row['sample_output']);?></textarea></td>
	</tr>
	<tr>
		<td>Edit Hint: </td>
		<td><textarea name = "hint" cols = 40 rows = 3><?php echo htmlspecialchars($row['hint']);?></textarea></td>
	</tr>
	</table>
	<input type="hidden" name="problemId" value="<?php echo $pid;?>">
	
	<input type = "submit" value = "Edit"/>&nbsp&nbsp&nbsp&nbsp
	<input type = "reset"  value = "Reset"/>
</form>
<?php
		$result->free();		
	}
	$conn->close();
}
?>
