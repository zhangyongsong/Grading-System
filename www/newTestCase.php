<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Add Test Case');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	echo '<h2>Add Test Case</h2>';
	
	$conn = dbConnect();
	$pid = $_POST['problem'];
	if(isset($_POST['testInput']))
		$input = $_POST['testInput'];
	if(isset($_POST['testOutput']))
		$output = $_POST['testOutput'];
	$result = dbQuery($conn, "SELECT p_name, tutorial, course_code FROM problem WHERE p_id = $pid");
	if (!$result)
	{
		gotoUrl("addTestCase.php?pid=$pid");
		exit;
	}
	$row = $result->fetch_assoc();
	$course = $row['course_code'];
	$tutorial = $row['tutorial'];
	$pName = $row['p_name'];
	echo "<h4>Course: $course</h4>";
	echo "<h4>Tutorial $tutorial</h4>";
	echo "<h4>Problem: $pName</h4>";
	@$num = count($output);
	if ($num == 0)
	{
		//alertGoback('Error!');
		gotoUrl("addTestCase.php?pid=$pid");
		exit;
	}
	$count = 0;
	$query = "INSERT INTO testcase (p_id, inputs, outputs) VALUES ";
	for($i = 0; $i < $num; $i++)
	{
		if ($input[$i] != '' || $output[$i] != '')
		{
			$testInput = $conn->real_escape_string($input[$i]);
			$testOutput = $conn->real_escape_string($output[$i]);
			$count++;
			$query = $query."($pid, '$testInput', '$testOutput'), ";
		}
	}
	if ($count == 0)
	{
		gotoUrl("addTestCase.php?pid=$pid");
		exit;
	}
	$query = trim($query, ", ");
	//echo $query.'<br/>';
	$result = dbQuery($conn, $query);
	$conn->close();
	if(!$result)
	{
		alertGoback('Error!');
		gotoUrl("addTestCase.php?pid=$pid");
		exit;
	}
	$out = "<b>$count</b>";
	if ($count == 1)
		$out = $out.' test case is ';
	else $out = $out.' test cases are ';
	echo $out."added for problem: <b>$pName</b>.<br/>";
	
	echo "<br/><a href=testCase.php?pid=$pid>Summery of test case</a><br/>";
	doHtmlFooter();
?>
