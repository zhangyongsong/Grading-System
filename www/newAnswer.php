<?php
	require_once('userFuncts.php');
	require_once("processFile.php");

	session_start();
	
	doHtmlHeader('Upload and Process your code');
	doHtmlMenu();
	
	//check session variable first
	if(isset($_SESSION['valid_user']))
	{
		// connect to database
		$conn=dbConnect();
		
		$username=$_SESSION['valid_user'];
		$pid= (int)$_POST['p_id'];
		$comments= escapeString($conn, $_POST['comments']);

		$query ="select * from problem where p_id=$pid";
		$pResult =$conn->query($query);
		if($pResult->num_rows){
			$pRow = $pResult->fetch_assoc();
			$pName = $pRow['p_name'];
			$course = $pRow['course_code'];
			@ $pResult->free();
		}
		else{
			alertGoback("Problem not found!");
			gotoUrl('addAnswer.php');
			exit;
		}
		$FILE_ROOT=$_SERVER['DOCUMENT_ROOT']."/../gradingsystem";
		$course = trim($course);
		$userDir = "$FILE_ROOT/uploads/$course/$pName/$username";
		$regex = '/^[^\/\\:\*\?\"<>\|]+\.[A-Za-z\+]+$/';

		if(!make_path($userDir)){
			alertGoback("Unable to create directory ".$userDir);
			gotoUrl('addAnswer.php');
			exit;
		}
		
		$filename= $_FILES['userfile']['name'];
		$upfile = "$userDir/$filename";
		checkDuplicateFile($upfile,$conn, $username, $pid);
		
		$filename = uploadFile('userfile',$regex, $userDir);
		//$status = processUpload($conn, $FILE_ROOT, $upfile, $pid);
		
		date_default_timezone_set('Asia/Singapore');
		$time = date('Y-m-d H:i:s');

		/*  Jar way of handling answer
		$queryJava = "SELECT * FROM ANSWER WHERE ex_status ='Compiling' or ex_status='Running'";
		$queryResult = dbQuery($conn, $queryJava);
		if(!is_object($queryResult) || !($queryResult->num_rows)){
		// this means that the backend process not running, start it.
		$platform = getXmlValue('/GradingSystem/platform');
			// for windows
			if($platform == 'windows'){
				 $process = popen("start java -jar \"$FILE_ROOT/OnlineJudge.jar\"", "r");
				 pclose($process);
			}
			// for linux use
			elseif ($platform == 'linux')
				exec("java -jar \"$FILE_ROOT/OnlineJudge.jar\"  > /dev/null 2>&1 &");
			else  // treat as *NIX
				exec("java -jar \"$FILE_ROOT/OnlineJudge.jar\"  > /dev/null 2>&1 &");
		}
		*/
		
		$updateId = dbUpdate($conn, $username, $filename, $pid, $comments, $time);
		
		if($updateId){
			// call tomcat for handling 
			$url = 'http://localhost:8080/gs/judge?aid='.$updateId;
			echo $url;
			$ch = curl_init($url);
			curl_exec($ch);
			curl_close($ch);
			
			gotoUrl("listResult.php?pid=$pid");
		}
		else
		{
			alertGoback('An error occur in the database manipulation!');
			gotoUrl('addAnswer.php');
			exit;
		}
		//doHtmlUrl('addAnswer.php','<p>Upload file again here!');
		$conn->close();
	}
	else
	{
		echo '<p>You are not logged in!';
		gotoUrl('login.php');
		exit;
	}
	doHtmlFooter();
?>

<?php
	
	function checkDuplicateFile($filename, $conn, $username, $pid){
		$dir = dirname($filename);
		$extension = substr($filename,strrpos($filename, '.'));
		$base = basename($filename, $extension);

		$i=1;
		$temp = $base;
		$newname = "$dir/$temp".$extension;
		while(file_exists($newname)){
			$temp = $base.$i;
			$i = $i+1;
			$newname = "$dir/$temp".$extension;
		}
		if($i>1 && file_exists($filename)){
		   rename($filename, $newname);
		}
		$name = $temp.$extension;
	
		$query = "Update answer set filename = '".$name."' where username='$username' and p_id=$pid and isLatest = 'true'";
		//echo $query;
		$result = $conn->query($query);
	}
	
	function dbUpdate($conn, $username, $filename, $pid, $comments, $time){
		$filename = addslashes($filename);
//		$comments = addslashes($comments);
		$attempts = 1;
		
		$query = "SELECT * FROM answer WHERE username='$username' AND p_id=$pid ORDER BY an_id DESC";
		$result=$conn->query($query);
		if($result->num_rows){
			$row = $result->fetch_assoc();
			$attempts = $row['no_attempts']+1;
			@ $result->free();
			$update = "UPDATE answer set isLatest = 'false' where username='$username' and p_id=$pid and isLatest = 'true'";
			$updateResult = $conn->query($update);
		}
		$insert= "INSERT INTO answer (username, filename, p_id, user_comments, upload_time, no_attempts, isLatest, ex_status) Values ('"
			.$username."', '".$filename . "', ".$pid. ", '".$comments."', '".$time."', ".$attempts.", 'true', 'Waiting')";
		//echo $insert;
		$insertResult=$conn->query($insert);
		return $conn->insert_id;
		//return $insertResult;
	}
?>

