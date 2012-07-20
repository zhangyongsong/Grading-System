<?php
	session_start();
	require_once('userFuncts.php');
	require_once('processFile.php');
	require_once('utilityFunctions.php');
	
	doHtmlHeader('Add Problem and Test Case');
	doHtmlMenu();
	echo '<h2>Add Problem</h2>';
	checkValidLogin();
	checkValidInstru();

	$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];

	$conn = dbConnect();
	
	$course = $_POST['course'];
	if (isset($_FILES['problemFile']['name']))
	{
		$tempFile="problemFile";
		$regex = '/^[^\/\\:\*\?\"<>\|]+\.xml$/';
		$destDir = "$DOCUMENT_ROOT/../gradingsystem/";
		if(!file_exists($destDir))
			if(!mkdir($destDir, 0777, true))
			{
				alertGoback("Cannot create folder");
				gotoUrl('index.php');
				exit;
			}
		
		$xmlFile = $destDir.uploadFile($tempFile, $regex, $destDir);
		//echo $xmlFile;
		
		$xmlElement = new SimpleXmlElement($xmlFile, null, true);
		foreach ($xmlElement->tutorial as $tut)
		{	
			//if tutorial number is not specified, ignore that record in xml
			if (!isset($tut->tutorialNumber))
				continue;
			echo '<b>Tutorial '.(int)$tut->tutorialNumber.'</b>: <br/>';
			$tutNum = (int)$tut->tutorialNumber;

			foreach ($tut->problems->problem as $prob)
			{
				//if problem title, problem language, or brief description field is not set, ignore
				if (!isset($prob->title) || !isset($prob->language) || !isset($prob->briefDescription))
					continue;
				$title = escapeString($conn, (string)$prob->title);
				$language = escapeString($conn, (string)$prob->language);
				$briefDescription = escapeString($conn, (string)$prob->briefDescription);
				
				if (isset($prob->description))
					$description = escapeString($conn, (string)$prob->description);
				else $description = '';
				if (isset($prob->inputDescription))
					$inputDesc = escapeString($conn, (string)$prob->inputDescription);
				else $inputDesc = '';
				if (isset($prob->outputDescription))
					$outputDesc = escapeString($conn, (string)$prob->outputDescription);
				else $outputDesc = '';
				if (isset($prob->sampleInput))
					$sampleInput = escapeString($conn, (string)$prob->sampleInput);
				else $sampleInput ='';
				if (isset($prob->sampleOutput))
					$sampleOutput = escapeString($conn, (string)$prob->sampleOutput);
				else $sampleOutput = '';
				if (isset($prob->hint))
					$hint = escapeString($conn, (string)$prob->hint);
				//in the same course we do not allow two problem with the same name
				$result = dbQuery($conn, "SELECT p_id FROM problem WHERE p_name = '$title' AND course_code = '$course'");
				if ($result->num_rows == 0)
				{
					$query = "INSERT INTO problem (tutorial, p_name, course_code, lang, explanation, description, input, output, sample_input, sample_output, hint) VALUES ($tutNum, '$title', '$course', '$language', '$briefDescription', '$description', '$inputDesc', '$outputDesc', '$sampleInput', '$sampleOutput', '$hint')";
					//echo $query;
					$insertResult = dbQuery($conn, $query);
					if (!$insertResult)
					{
						alertGoback("Cannot add this problem");
						gotoUrl('index.php');
						exit;
					}
				}
				else 
				{
					echo 'Problem <b>'.$title.'</b> already exits.<br/>';
					continue;
				}
				echo 'Problem <b>'.$title.'</b> is added.<br/>';
				
				$query = "SELECT p_id FROM problem WHERE p_name = '$title' AND course_code = '$course'";
				$result = dbQuery($conn, $query);
				$row = $result->fetch_assoc();
				$pid = $row['p_id'];
				$query = "INSERT INTO testcase (p_id, inputs, outputs) VALUES ";
				$count = 0;
				foreach($prob->testcases->testcase as $testcase)
				{
					if (isset($testcase->testInput))
						$testInput = escapeString($conn, (string)$testcase->testInput);
					else $testInput = '';
					//a problem must have test output
					if (!isset($testcase->testOutput))
						continue;
					$testOutput = escapeString($conn, trim((string)$testcase->testOutput));
					$query = $query."($pid, '$testInput', '$testOutput'), ";
					$count++;
				}
				if ($count !=0)
				{
					$query = trim($query, ", ");
					$result = dbQuery($conn, $query);
				}
			}
		}
	}
	
	doHtmlFooter();
?>
