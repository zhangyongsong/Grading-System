<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	
	$conn = dbConnect();

	echo '<h2>Test Case</h2>';
	if (!isset($_GET['pid']) || !is_numeric($_GET['pid']))
	{	
		gotoUrl('problem.php');
		exit;
	}
	$pid = $_GET['pid'];
	$instruName = $_SESSION['valid_user'];
	$result = dbQuery($conn, "SELECT problem.* FROM problem INNER JOIN enrollment ON problem.course_code = enrollment.course_code
				 WHERE enrollment.username = '$instruName' AND problem.p_id = $pid");
	if (!$result && $result->num_rows == 0)
	{
		echo '<h4>Error: The target problem is not found.</h4>'.
		exit;
	}
	$row = $result->fetch_assoc();
	echo '<h4>Course: '.$row['course_code'].'</h4>';
	echo '<h4>Tutorial '.$row['tutorial'].'</h4>';
	echo '<h4>'.$row['p_num'].' '.$row['p_name'].'</h4><br/>';
	$result = dbQuery($conn, "SELECT * FROM testcase WHERE p_id = $pid");
	if (!$result)
	{
		gotoUrl('problem.php');
		exit;
	}
	
	$count = $result->num_rows;
	if ($count == 0)
		echo '<h4>No testcase is defined in this problem</h4>';
	else
	{
		echo '<hr/><br/>';
		for($i = 1; $i <= $count; $i++)
		{
			$row = $result->fetch_assoc();
			echo '<b>Test case '.$i.'</b><br/><br/>';
			echo "<font color=\"0077AA\">Test input:</font><br/><pre class=\"wrap\">".$row['inputs'].'</pre><br/><br/>';
			echo "<font color=\"0077AA\">Test output:</font><br/><pre class=\"wrap\">".$row['outputs'].'</pre><br/><br/>';
			echo "<a href=\"editTestCase.php?ioid=".$row['io_id']."\">Edit</a>&nbsp&nbsp&nbsp";
			echo "<a href=\"removeTestCase.php?ioid=".$row['io_id']."\">Delete</a><br/><br/>";
			echo '<hr/><br/>';
		}

		$result->free();
		echo "<br/><a href='addTestCase.php?pid=$pid'>Add more test cases</a>";
	}
	$conn->close();
	doHtmlFooter();
?>
