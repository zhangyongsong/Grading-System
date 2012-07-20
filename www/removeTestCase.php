<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Delete Test Case');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	displayRemoveTestCase();
	doHtmlFooter();
?>
<?php
function displayRemoveTestCase()
{
	echo '<h2>Delete Test Case</h2>';
	if (!isset($_GET['ioid']) || !is_numeric($_GET['ioid']))
	{
		gotoUrl('problem.php');
		exit;
	}
	
	$conn = dbConnect();
	$testcase = $_GET['ioid'];
	$instruName = $_SESSION['valid_user'];
	
	
	$query = "select problem.p_id, p_name, problem.course_code, tutorial from problem, enrollment, testcase
		 where enrollment.course_code = problem.course_code and problem.p_id = testcase.p_id and username = '$instruName' and io_id = $testcase";
	$result = dbQuery($conn, $query);
	//echo  $query;
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
	$result->free();
	echo "<h4>Course: $coursecode</h4><h4>Tutorial $tutorial</h4><h4>Problem: $pName</h4>";
	$delete = "DELETE FROM testcase WHERE io_id = $testcase";

	dbQuery($conn, $delete);

	echo "1 test case is deleted from problem <b>$pName</b>. <br/>";
	echo "<br/><a href='testCase.php?pid=$pid'>Test case summery of Problem <b>$pName</b>.</a><br/>";
	$conn->close();
}
?>
