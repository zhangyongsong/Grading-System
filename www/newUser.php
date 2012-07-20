<?php
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	require_once('userFuncts.php');
	require_once('processFile.php');
	session_start();
	doHtmlHeader('');
	doHtmlMenu();
	checkValidLogin();
	checkValidAdmin();
	echo '<h2>User Registration</h2><br/>';
	
	//1. check radio button.
	
	if(!isset($_POST['priv']))
	{
		gotoUrl('addUser.php');
		exit;
	}
	$priv=$_POST['priv'];
	if($priv != 'administrator')
		$courseId=$_POST['course'];
	else $courseId='';
	
	$newUserFlag = false;
	$conn = dbConnect();
	if (isset($_FILES['registrationFile']['name']) && $_FILES['registrationFile']['name'] != '')
	{
		$newUserFlag = true;
		$tempFile='registrationFile';
		$regex='/^[^\/\\:\*\?\"<>\|]+\.txt$/';
		$destDir="$DOCUMENT_ROOT/../gradingsystem/";
		if(!file_exists($destDir))
			if(!mkdir($destDir, 0777, true))
			{
				alertGoback("Cannot create folder");
				gotoUrl('addUser.php');
				exit;
			}
			
		$adminRegFile=$destDir.uploadFile($tempFile, $regex, $destDir);

		//read uploaded file line by line
		@$fp = fopen($adminRegFile, 'rb');
		if(!$fp)
		{
			alertGoback('Problem: fail to open uploaded file');
			gotoUrl('addUser.php');
			exit;
		}

		echo "<h4>File Registration Result:</h4>";
		$usernameArr = getFileUsernameArr($fp);
		groupRegister($usernameArr, $conn, $priv, $courseId);
		
		
		fclose($fp);
		if(file_exists($adminRegFile))
			unlink($adminRegFile);
	}
	if (isset($_POST['usernames']) && $_POST['usernames'] != '')
	{
		$newUserFlag = true;
		$usernames = $_POST['usernames'];
		echo "<h4>Quick Registration Result:</h4>";
		$usernameArr = getQuickUsernameArr($usernames);
		groupRegister($usernameArr, $conn, $priv, $courseId);
	}
	$conn->close();
	if (!$newUserFlag)
	{
		alertGoback('Failed');
		gotoUrl('addUser.php');
		exit;
	}
?>
	<br/><a href="deleteUser.php">View All Registered Users</a><br/>
<?php
	doHtmlFooter();
?>
<?php
function register($conn, $username, $password, $email, $privilege = "student")
//register new user with db
//return true or error message
{
	$result = $conn->query("insert into user (username, password, email, privilege) 
		values ('$username', sha1('$password'), '$email', '$privilege')");
	if(!$result)
		return false;
	else return true;
}
function chkUniqueUsername($conn, $username)
{
	$result = $conn->query("select * from user where username='$username'");
	if(!$result)
		return false;
	$num = $result->num_rows;
	$result->free();
	if ($num > 0)
		return false;
	else return true;
}
function validateUsername($username)
{
	if(!preg_match('/^[a-zA-Z0-9]+$/', $username))
		return false;
	else return true;
}
function getFileUsernameArr(&$fp)
{
	$usernameArr = array();
	$i = 0;
	while(!feof($fp))
	{
		$username = strtolower (trim(fgets($fp)));
		if($username=="" || $username==null)
			break;
		else 
		{
			$usernameArr[$i] = $username;
			$i++;
		}
	}
	//print_r($usernameArr);
	return $usernameArr;
}
function getQuickUsernameArr(&$usernames)
{
	$usernameArr = array();
	$i = 0;
	$usernameArr = preg_split('/[\s]+/', trim($usernames));
	//print_r($usernameArr);
	return $usernameArr;
}
function groupRegister(&$usernameArr, &$conn, $privilege, $courseId)
{
	$failedCount = 0;
	$successCount = 0;

	foreach ($usernameArr as $username)
	{
		// change username to lowercase
		$username = strtolower($username);
		//validate username
		if(validateUsername($username))
		{
			if($privilege == 'administrator')
			{
				if(!register($conn, $username, $username, $username.'@ntu.edu.sg', $privilege))
				{
					$failedRecord[$failedCount]=$username;
					$failedCount++;
				}
				else $successCount++;
			}
			else
			{
				if(chkUniqueUsername($conn, $username))
				{
					if(!register($conn, $username, $username, $username.'@ntu.edu.sg', $privilege)||!enroll($conn, $courseId, $username))
					{
						$failedRecord[$failedCount]=$username;
						$failedCount++;
					}
					else $successCount++;
				}
				else
				{
					if (chkUserPriv($conn, $username) == 'administrator')
					{
						$failedRecord[$failedCount]=$username;
						$failedCount++;
					}
					else if (!enroll($conn, $courseId, $username))
					{
						$failedRecord[$failedCount]=$username;
						$failedCount++;
					}
					else $successCount++;
				}
			}
		}
		else
		{
			$failedRecord[$failedCount]=htmlspecialchars($username);
			$failedCount++;
		}
	}

	$totalCount = $failedCount + $successCount;
	$user = 'users';
	if($totalCount < 2)
		$user = 'user';
	echo "You tried to register <b>$totalCount</b> $user just now.<br/>";
	echo "<b>$successCount</b> succeeded and <b>$failedCount</b> failed.<br>";
	if ($failedCount > 0)
	{
		echo "Failed to register: <br/>";
		for($i=0; $i<$failedCount; $i++)
			echo '<b>'.$failedRecord[$i].'</b><br/>';
		echo "as '$privilege'.<br/>";
	}
}
function enroll($conn, $courseId, $username)
{
	$query = "SELECT * FROM enrollment where course_code='$courseId' and username='$username'";
	$result=$conn->query($query);
	if(!$result)
		return false;
	$num = $result->num_rows;
	//$result->free();
	if($num > 0)
		return false;
	else 
	{
		$query="INSERT INTO enrollment (course_code, username) VALUES ('$courseId', '$username')";
		$result=$conn->query($query);
		if($result)
			return true;
		return false;
	}
}
function chkUserPriv($conn, $username)
{
	$result = $conn->query("select privilege from user where username='$username'");
	$row = $result->fetch_assoc();
	return trim($row['privilege']);
}
?>
