<?php
	require_once('userFuncts.php');
	session_start();
	doHtmlHeader('Edit Problem');
	doHtmlMenu();
	checkValidLogin();
	checkValidInstru();
	
	echo '<h2>Edit Problem</h2><br/>';
	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
	$conn=dbConnect();
	$pid=$_POST['problemId'];
	$language=$_POST['language'];
	$result=dbQuery($conn, "SELECT * FROM problem WHERE p_id = '$pid'");
	$row=$result->fetch_assoc();
	$coursecode = $row['course_code'];
	$problemName='';
	$explanation='';
	$description='';
	$input='';
	$output='';
	$sampleInput='';
	$sampleOutput='';
	$hint='';
	
	$update="UPDATE problem SET ";
	if ($language != '0' && $language != $row['lang'])
		$update = $update."lang = '$language', ";
	if(isset($_POST['problemName']))
	{
		$pName = $row['p_name'];
		$problemName = escapeString($conn, $_POST['problemName']);
		if(strcasecmp($pName, $problemName) != 0)
		{
			$update = $update."p_name = '$problemName', ";
			$dir = "$DOCUMENT_ROOT/../uploads/$coursecode/$pName/";
			if (file_exists($dir))
				rename($dir, "$DOCUMENT_ROOT/../gradingsystem/uploads/$coursecode/$problemName/");
			else mkdir("$DOCUMENT_ROOT/../gradingsystem/uploads/$coursecode/$problemName/", 0777, true);
			$pName = $problemName;
		}
	}
	if(isset($_POST['explanation']))
	{
		$explanation =  escapeString($conn, $_POST['explanation']);
		if (strcasecmp($explanation, $row['explanation']) != 0)
			$update = $update."explanation = '$explanation', ";
	}
	if(isset($_POST['description']))
	{
		$description =  escapeString($conn, $_POST['description']);
		if (strcasecmp($description, $row['description']) != 0)
			$update = $update."description = '$description', ";
	}
	if(isset($_POST['input']))
	{
		$input = escapeString($conn, $_POST['input']);
		if (strcasecmp($description, $row['input']) != 0)
			$update = $update."input = '$input', ";
	}
	if(isset($_POST['output']))
	{
		$output = escapeString($conn, $_POST['output']);
		if (strcasecmp($output, $row['output']) != 0)
			$update = $update."output = '$output', ";
	}
	if(isset($_POST['sampleInput']))
	{
		$sampleInput = escapeString($conn, $_POST['sampleInput']);
		if (strcasecmp($sampleInput, $row['sample_input']) != 0)
			$update = $update."sample_input = '$sampleInput', ";
	}
	if(isset($_POST['sampleOutput']))
	{
		$sampleOutput = escapeString($conn, $_POST['sampleOutput']);
		if (strcasecmp($sampleOutput, $row['sample_output']) != 0)
			$update = $update."sample_output = '$sampleOutput', ";
	}
	if(isset($_POST['hint']))
	{
		$hint = escapeString($conn, $_POST['hint']);
		if (strcasecmp($hint, $row['hint']) != 0)
			$update = $update."hint = '$hint', ";
	}
	$update = trim($update, ", ");
	$update = $update." WHERE p_id = '$pid'";
	//echo $update.'<br/>';
	$result = dbQuery($conn, $update);
	if (!$result)
	{
		$conn->close();
		alertGoback('Error! ');
		gotoUrl('editProblem.php');
		exit;
	}
	else
	{
		$result = dbQuery($conn, "SELECT course_code, tutorial FROM problem WHERE p_id = $pid");
		$row = $result->fetch_assoc();
		$result->free();
		echo "Problem <b>$pName</b> in <b>".$row['course_code']."</b>, <b>Tutorial ".$row['tutorial']."</b> is updated successfully.<br/>";
		echo "<br/><a href='descProblem.php?pid=$pid'>See full details here</a>";
	}
	$conn->close();
	doHtmlFooter();
?>
