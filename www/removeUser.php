<?php
	require_once('userFuncts.php');
	require_once('deleteFolder.php');
	session_start();
	doHtmlHeader('User De-registration');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	
	echo "<h2>De-register User</h2>";
	$isPostAdmin = false;
	$isPostNonAdmin = false;
	$conn = dbConnect();
	//deregister administrator
	if (isset($_POST['administrator']))
	{
		$isPostAdmin = true;
		$admin = $_POST['administrator'];
		$adminCount = count($admin);
		$query = "DELETE FROM user WHERE ";
		$out = "Administrator ";
		for($i=0; $i<$adminCount; $i++)
		{
			$query = $query."username = '$admin[$i]' ";
			$out = $out."<b>$admin[$i]</b>";
			if ($i != $adminCount-1)
			{
				$query = $query.'OR ';
				$out = $out.', ';
			}
			if ($i == $adminCount-2)
				$out = $out.'and ';
		}	
		$adminRes = $conn->query($query);
		if ($adminCount == 1)
			$out = $out.' is ';
		else $out = $out.' are ';
		echo '<br/>'.$out.'de-registered.<br/>';
	}
	
	//deregister user and instructor
	$courseRes = dbQuery($conn, "SELECT course_code FROM course");
	if ($courseRes && ($courseRes->num_rows)>0)
	{
		$courseCount = $courseRes->num_rows;
		for ($i = 0; $i < $courseCount; $i++)
		{
			$row = $courseRes->fetch_assoc();
			$coursePrinted = false;
			//instructor
			if (isset($_POST['instru'.$row['course_code']]))
			{
				echo '<strong>Course: '.$row['course_code'].'</strong><br/>';
				$coursePrinted = true;
				$isPostNonAdmin = true;
				$instructor = $_POST['instru'.$row['course_code']];
				$instruCount = count($instructor);
				$query = "DELETE FROM enrollment WHERE course_code = '".$row['course_code']."' AND (";
				$out = "Instructor ";
				for($j=0; $j<$instruCount; $j++)
				{
					$query = $query."username = '$instructor[$j]' ";
					$out = $out."<b>$instructor[$j]</b>";
					if ($j != $instruCount-1)
					{
						$query = $query.'OR ';
						$out = $out.', ';
					}
					if ($j == $instruCount-2)
						$out = $out.'and ';
				}	
				//echo $query.')';
				$instruRes = $conn->query($query.')');
				if ($instruCount == 1)
					$out = $out.' is ';
				else $out = $out.' are ';
				echo '<br/>'.$out.'de-registered.<br/>';
			}
			if(isset($_POST['user'.$row['course_code']]))
			{
				if (!$coursePrinted)
					echo '<strong>Course: '.$row['course_code'].'</strong><br/>';
				$isPostNonAdmin = true;
				$user = $_POST['user'.$row['course_code']];
				$userCount = count($user);
				
				$course = $row ['course_code'];//echo "user count for $course= $userCount";
				$query = "DELETE FROM enrollment WHERE course_code = '".$row['course_code']."' AND (";
				$out = "User ";
				for($j=0; $j<$userCount; $j++)
				{
					//echo "user[$j]=".$user[$j]." ";
					$queryAns = "SELECT problem.p_name from problem inner join answer on problem.p_id = answer.p_id 
								where answer.username = ".$user[$j];
					$ansRes = $conn->query($queryAns);
					if ($ansRes)
					{
						for($k=0; $k<($ansRes->num_rows); $k++)
						{
							$ansRow = $ansRes->fetch_assoc();
							$pName = $ansRow['p_name'];
							$dir = "$DOCUMENT_ROOT/../gradingsystem/uploads/$course/$pName/$user[$j]";
							if (file_exists($dir))
								deldir($dir);
						}
						@$ansRes->free();
					}
					$query = $query."username = '$user[$j]' ";
					$out = $out."<b>$user[$j]</b>";
					if ($j != $userCount-1)
					{
						$query = $query.'OR ';
						$out = $out.', ';
					}
					if ($j == $userCount-2)
						$out = $out.'and ';
				}
				$userRes = $conn->query($query.')');
				if ($userCount == 1)
					$out = $out.' is ';
				else $out = $out.' are ';
				echo '<br/>'.$out.'de-registered.<br/>';
			}
		}
		$courseRes->free();
	}
	if ($isPostAdmin == false && $isPostNonAdmin == false )
	{
		gotoUrl('deleteUser.php');
		exit;
	}
	else if ($isPostNonAdmin) //check whether the user is still enrolled a course. if not, delete the user from user list.
		dbQuery($conn, "delete user.* from user where user.privilege != 'administrator' and user.username not in (select enrollment.username from enrollment)");
	$conn->close();
?>
	<br/><a href="deleteUser.php">View All Registered Users</a><br/>
<?php
	doHtmlFooter();
?>
