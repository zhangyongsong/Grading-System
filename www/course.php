<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Course');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	echo '<h2>Course</h2>';	

	displayCourse();
	doHtmlFooter();
?>
<?php
function displayCourse()
{
	$conn=dbConnect();
	$query='SELECT * FROM course';
	$result=$conn->query($query);
	if (!$result)
	{
		$conn->close();
		alertGoback('No Course is registered.');
		gotoUrl('index.php');
		exit;
	}
	$num = $result->num_rows;
	$conn->close();
	if ($num == 0)
		echo "<h3>No Course is registered.</h3><br/>";
	else
	{
?>
		<table class="result">
		<tr>
			<th>Course Code</th>
			<th>Course Name</th>
			<th>Action</th>
		</tr>
<?php
		for($i=0; $i< $num; $i++)
		{
			$row = $result->fetch_assoc();
			echo '<tr><td>'.$row['course_code'].'</td><td>'.$row['course_name'].
			"</td><td><a href='removeCourse.php?course=".rawurlencode($row['course_code'])."'>Delete</a></td></tr>";
		}
?>
		</table>
<?php
	}
?>

	<a href="addCourse.php">Add new course</a>
<?php
}
