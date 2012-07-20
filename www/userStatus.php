<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('User Status');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	echo "<h2>User Status</h2>";
	if(!isset($_GET['username']) && !isset($_GET['pid']))
		displayStatistics();
	if(isset($_GET['username']) && !isset($_GET['pid']))
		displayUserStatistics();
	if(isset($_GET['username']) && isset($_GET['pid']))
		displayDetail();
	doHtmlFooter();
?>
<?php
function displayStatistics()
{
	$conn = dbConnect();
	$instruName=$_SESSION['valid_user'];
	$query="SELECT course_code FROM enrollment WHERE username = '$instruName' ORDER BY course_code ASC";
	$courseRes=$conn->query($query);
	if (!$courseRes)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$courseCount = $courseRes->num_rows;
	if ($courseCount == 0)
		echo "No course is registered.<br/>";
	else
	{
?>
		<form method="post">
		Please select a course to start:
		<select name="course" onchange="this.form.submit()">
		<option value="">--Select Course--</option>
<?php
		for ($i = 0; $i < $courseCount; $i++)
		{
			$row = $courseRes->fetch_assoc();
?>
			<option id="<?php echo $row['course_code'];?>" value="<?php echo $row['course_code'];?>" ><?php echo $row['course_code'];?><br/>
<?php
		}
		$courseRes->free();
?>
		</select><br/><br/>
<?php
		if (isset($_POST['course']) && $_POST['course'] != "")
		{
			$coursecode = $_POST['course'];
			echo "<script type='text/javascript'>document.getElementById('$coursecode').selected=true</script>";
			$query = "SELECT distinct answer.username, count(distinct p_id) as attemptNum,count(answer.username) as submissionNum,
				sum(case when isLatest='true' and status='Yes' then 1 else 0 end) as solveNum FROM answer INNER JOIN enrollment 
				ON enrollment.username = answer.username WHERE course_code = '$coursecode' group by username;";
			$result = dbQuery($conn, $query);
			if (!$result)
			{
				$conn->close();
				alertGoback('Error!');
				gotoUrl('index.php');
				exit;
			}
			$num = $result->num_rows;
			if($num == 0)
				echo 'No user status is available. <br/>';
			else
			{
?>
				<table class="result">
				<tr>
					<th>User</th>
					<th>No. of Problems Attempted</th>
					<th>No. of Problems Solved</th>
					<th>No. of Total Submission</th>
					<th>Details</th>
				</tr>
<?php
				for($i = 0; $i < $num; $i++)
				{
					$row = $result->fetch_assoc();
?>
				<tr>
					<td><?php echo $row['username'];?></td>
					<td><?php echo $row['attemptNum'];?></td>
					<td><?php echo $row['solveNum'];?></td>
					<td><?php echo $row['submissionNum'];?></td>
					<td><a href="userStatus.php?username=<?php echo $row['username'];?>">Details</a></td>
				</td>
<?php
				}
				$result->free();
?>
			</table>
		
<?php
			}
		}
		echo '</form>';
	}
	$conn->close();
}
function displayUserStatistics()
{
	$conn = dbConnect();
	$username = escapeString($conn, $_GET['username']);
	echo "User: <b>$username</b><br/>";
	$query = "SELECT answer.p_id, p_num, problem.p_name, status, tutorial, no_attempts, upload_time FROM answer INNER JOIN problem
				ON answer.p_id = problem.p_id WHERE username = '$username' AND isLatest = 'true' ORDER BY tutorial ASC";
	$result = $conn->query($query);
	if (!$result)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$num = $result->num_rows;
	if($num == 0)
		echo "<br/><b>$username</b> did not try any problem.<br/>";
	else
	{
?>
	<table class="result">
	<tr>
		<th>Tutorial</th>
		<th>Problem</th>
		<th>Status</th>
		<th>No. of Submission</th>
		<th>Last Submission Time</th>
		<th>Details</th>
	</tr>
<?php
		for($i = 0; $i < $num; $i++)
		{
			$row = $result->fetch_assoc();
?>
		<tr>
			<td><?php echo $row['tutorial'];?></td>
			<td><a href="descProblem.php?pid=<?php echo $row['p_id'];?>" target="_blank"><?php echo $row['p_num'].' '.htmlspecialchars($row['p_name']);?></a></td>
			<td><?php echo interpretStatus($row['status']);?></td>
			<td><?php echo $row['no_attempts'];?></td>
			<td><?php echo $row['upload_time'];?></td>
			<td><a href="userStatus.php?username=<?php echo $username;?>&pid=<?php echo $row['p_id'];?>">Details</a></td>
		</tr>
<?php
		}
		echo '</table>';
		$result->free();
	}
	$conn->close();
}

function displayDetail()
{
	$conn = dbConnect();
	
	$username = escapeString($conn, $_GET['username']);
	$pid = escapeString($conn, $_GET['pid']);
	$result = dbQuery($conn, "SELECT * FROM answer WHERE username = '$username' AND p_id = '$pid' ORDER BY an_id DESC");
	if (!$result)
	{
		$conn->close();
		alertGoback('Error!');
		gotoUrl('index.php');
		exit;
	}
	$num = $result->num_rows;
	if($num == 0)
		echo "<br/><b>$username</b> did not try any problem.<br/>";
	else
	{
		$pResult = dbQuery($conn, "SELECT p_name FROM problem WHERE p_id = '$pid'");
		$row = $pResult->fetch_assoc();
		$pResult->free();
		echo "User: <b>$username</b>&nbsp Problem: <b>".$row['p_name']."</b><br/>";
?>
	<table class="result">
	<tr>
		<th>Status</th>
		<th>Submission Time</th>
		<th>Details</th>
	</tr>
<?php
		for($i = 0; $i < $num; $i++)
		{
			$row = $result->fetch_assoc();
?>
		<tr>
			<td><?php echo interpretStatus($row['status']);?></td>
			<td><?php echo $row['upload_time'];?></td>
			<td><a href="descAnswer.php?aid=<?php echo $row['an_id'];?>">Details</a></td>
		</tr>
<?php
		}
		echo '</table>';
		$result->free();
	}
}
?>
