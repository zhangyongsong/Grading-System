<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Edit Test Case');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayModifyTestCase();
	doHtmlFooter();
?>
<?php
function displayModifyTestCase()
{
	echo '<h2>Edit Test Case</h2>';
	if (!isset($_POST['ioId']) || !isset($_POST['inputs']) || !isset($_POST['outputs']))
	{
		gotoUrl('problem.php');
		exit;
	}
	$conn = dbConnect();
	$ioId = $_POST['ioId'];
	$inputs = $_POST['inputs'];
	$outputs = $_POST['outputs'];
	//echo "SELECT problem.p_id, p_name, tutorial, course_code FROM problem inner join testcase on problem.p_id = io.p_id where io_id = $ioId";
	$result = dbQuery($conn, "SELECT problem.p_id, p_name, tutorial, course_code FROM problem inner join testcase on problem.p_id = testcase.p_id where io_id = $ioId");
	$row = $result->fetch_assoc();
	
	echo '<h4>Course: '.$row['course_code'].'</h4>';
	echo '<h4>Tutorial '.$row['tutorial'].'</h4>';
	echo '<h4>Problem: '.$row['p_name'].'</h4>';
	
	$result->free();
	$pid = $row['p_id'];
	dbQuery($conn, "UPDATE testcase SET inputs = '$inputs', outputs = '$outputs' where io_id = $ioId");
	echo '<br/>1 test case is updated.<br/>';
	echo "<br/><a href='testCase.php?pid=$pid'>Test case summery.</a><br/>";
	
	$conn->close();
}
?>
