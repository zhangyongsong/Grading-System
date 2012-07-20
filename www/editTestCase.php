<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Edit Test Case');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayEditTestCase();
	doHtmlFooter();
?>
<?php
function displayEditTestCase()
{
	echo '<h2>Edit Test Case</h2>';
	if (!isset($_GET['ioid']) || !is_numeric($_GET['ioid']))
	{
		gotoUrl('problem.php');
		exit;
	}
	
	$conn = dbConnect();
	$instruName=$_SESSION['valid_user'];
	$ioid = $_GET['ioid'];
	
	$result = dbQuery($conn, "select problem.p_id, p_name, p_num, problem.course_code, tutorial, inputs, outputs from problem, enrollment, testcase
		 where enrollment.course_code = problem.course_code and problem.p_id = testcase.p_id and username = '$instruName' and io_id = $ioid");
	if (!$result || $result->num_rows != 1)
	{
		$conn->close();
		gotoUrl('problem.php');
		exit;
	}
	
	$row = $result->fetch_assoc();
	
	$pName = $row['p_name'];
	$coursecode = $row['course_code'];
	$tutorial = $row['tutorial'];
	$pid = $row['p_id'];
	$inputs = $row['inputs'];
	$outputs = $row['outputs'];
	$pNum = $row['p_num'];
	
	$result->free();
	echo "<h4>Course: $coursecode</h4><h4>Tutorial $tutorial</h4><h4>$pNum $pName</h4>";
?>
	<form action="modifyTestCase.php" method="post">
	Please edit test case here:<br/><br/>
	<input type="hidden" name="ioId" value="<?php echo $ioid;?>" />
	Test Input:<br/>
	<textarea name="inputs" cols="90" rows="18"><?php echo $inputs;?></textarea><br/><br/>
	Test Output:<br/>
	<textarea name="outputs" cols="90" rows="18"><?php echo $outputs;?></textarea><br/><br/>
	
	<input type="submit" value="Submit" />&nbsp&nbsp&nbsp
	<input type="reset" value="Reset" />
	</form>
<?php
	$conn->close();
}
?>
