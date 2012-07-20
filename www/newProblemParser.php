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

if (isset($_POST['course']))
	$coursecode = $_POST['course'];
else
{
	alertGoback('Please fill in target course code.');
	gotoUrl('addProblem.php');
	exit;
}

$DOCUMENT_ROOT=$_SERVER['DOCUMENT_ROOT'];
if (isset($_FILES['problemFile']['name']) && $_FILES['problemFile']['name'] != '')
{
	$tempFile="problemFile";
	$regex = '/^[^\/\\:\*\?\"<>\|]+\.txt$/';
	$destDir = "$DOCUMENT_ROOT/../gradingsystem/";
	if(!file_exists($destDir))
		if(!mkdir($destDir, 0777, true))
		{
			alertGoback("Cannot create folder");
			gotoUrl('addProblem.php');
			exit;
		}
	
	$uploadedFilePath = $destDir.uploadFile($tempFile, $regex, $destDir);
	
	parseProblemFile($uploadedFilePath, $coursecode);
}
?>
<?php
function parseProblemFile($uploadedFilePath, $coursecode)
{
	$conn = dbConnect();
	
	$fileContent = file_get_contents($uploadedFilePath);
	$fileLineArr = preg_split('/(\r\n)|(\r)|(\n)/', $fileContent);
	
	$parseErr = false;
	$newProblem = false;
	$testCase = false;
	$testInput = false;
	$testOutput = false;
	$prevStatus = null;
	$lineNum = 0;
	$testCaseNum = -1;
	$problemValues = array();
	$testCaseValues = array();
	$problemEntryStatus = array('tutorial' => false, 'p_num' => false, 'p_name' => false, 'lang' => false, 'explanation' => false, 'description' => false,
	'input' => false, 'output' => false, 'sample_input' => false, 'sample_output' => false, 'hint' => false);
	foreach ($fileLineArr as $line)
	{
		if ($parseErr)
			break;
		switch(strtolower(trim($line)))
		{
			case '#tutorial:':
				//check whether last problem ends properly.
				if (!$newProblem)
				{
					$newProblem = true;
					$testCaseNum = 0;
					resetStatus($problemEntryStatus);
					$problemEntryStatus['tutorial'] = true;
				}
				else
				{
					$parseErr = true;
					echo 'Problem file parse error at <strong>Line '.$lineNum.'</strong>. The problem does not finish properly.';
					continue;
				}
				break;
			case '#problem number:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['p_num'] = true;
				break;
			case '#title:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['p_name'] = true;
				break;
			case '#language:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['lang'] = true;
				break;
			case '#brief description:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['explanation'] = true;
				break;
			case '#full description:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['description'] = true;
				break;
			case '#input description:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['input'] = true;
				break;
			case '#output description:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['output'] = true;
				break;
			case '#sample input:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['sample_input'] = true;
				break;
			case '#sample output:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['sample_output'] = true;
				break;
			case '#hint:':
				$prevStatus = getCurrentStatus($problemEntryStatus);
				resetStatus($problemEntryStatus);
				$problemEntryStatus['hint'] = true;
				break;
			case '#test case:':
				$testCase = true;
				$testCaseNum++;
				break;
			case '#test input:':
				$testInput = true;
				$testOutput = false;
				break;
			case '#test output:':
				$testInput = false;
				$testOutput = true;
				break;
			//clear all data and do database manipulation
			case '#end':
				$prevStatus = null;
				resetStatus($problemEntryStatus);
				if ($newProblem)
				{
					$p_id = insertProblem($conn, $problemValues, $coursecode);
					if ($p_id == null)
					{
						alertGoback('Error occurred during inserting problems');
						gotoUrl('addProblem.php');
						exit;
					}
					$testCaseNum++;
					//add sample test case into test case
					$testCaseValues[$testCaseNum] = array('input' => $problemValues['sample_input'], 'output' => $problemValues['sample_output']);
					insertTestCase($conn, $p_id, $testCaseValues);
				}
				unset($problemValues);
				unset($testCaseValues);
				$testCase = false;
				$newProblem = false;
				$testInput = false;
				$testOutput = false;
				break;
			default:
				if ($newProblem)
				{
					if(!$testCase)
					{
						if ($prevStatus == 'explanation' || $prevStatus == 'p_name')
						{
							if (trim($problemValues[$prevStatus]) == '')
							{
								$parseErr = true;
								echo 'Problem file parse error at <strong>Line '.($lineNum-1).'</strong>. Please fill in explanation or title.';
								continue;
							}
						}
						$currentStatus = getCurrentStatus($problemEntryStatus);
						if ($currentStatus == 'tutorial' || $currentStatus == 'p_num')
							$problemValues[$currentStatus] = (int)(0 + $line);
						else if($currentStatus == 'lang')
						{
							$line = strtolower($line);
							if ($line == 'java')
								$problemValues['lang'] = 'Java';
							else if ($line == 'c++')
								$problemValues['lang'] = 'C++';
							else
							{
								$parseErr = true;
								echo 'Problem file parse error at <strong>Line '.$lineNum.'</strong>. Please fill in a correct target language.';
								//ignore this problem, seek for another.
								$newProblem = false;
								continue;
							}
						}
						else
						{
							if (!isset($problemValues[$currentStatus]))
								$problemValues[$currentStatus] = escapeString($conn, $line);
							else $problemValues[$currentStatus] .= "\n".escapeString($conn, $line);
						}
					}
					else
					{
						if ($testInput)
						{
							if (!isset($testCaseValues[$testCaseNum]['input']))
								$testCaseValues[$testCaseNum]['input'] = escapeString($conn, $line);
							else $testCaseValues[$testCaseNum]['input'] .= "\n".escapeString($conn, $line);
						}
						else if ($testOutput)
						{
							if (!isset($testCaseValues[$testCaseNum]['output']))
								$testCaseValues[$testCaseNum]['output'] = escapeString($conn, $line);
							else $testCaseValues[$testCaseNum]['output'] .= "\n".escapeString($conn, $line);
						}
					}
						
				}

		}
		$lineNum++;
	}
	//check whether the last problem is finished with #End
	if (isset($problemValues) || isset($testCaseValues))
		echo 'Problem file parse error at <strong>Line '.$lineNum.'</strong>. The problem does not finish properly.';
}
function insertProblem($conn, $problemValues, $coursecode)
{
	$columnName = '';
	$entryValue = '';
	foreach ($problemValues as $key => $value)
	{
		$columnName .= $key.',';
		if ($key == 'tutorial' || $key == 'p_num')
			$entryValue .= $value.',';
		else $entryValue .= "'".$value."',";
	}
	$columnName .= 'course_code';
	$entryValue .= "'".$coursecode."'";
	if (dbQuery($conn, 'insert into problem ('.$columnName.') values ('.$entryValue.')'))
	{
		echo 'Problem <Strong>'.$problemValues['p_num'].' '.$problemValues['title'].'</strong> is added.<br/>';
		return $conn->insert_id;
	}
	else return null;
}
function insertTestCase($conn, $pid, $testCaseValues)
{
	if($pid != null)
	{
		$insertStr = 'insert into testcase (p_id, inputs, outputs) values ';
		$testValueStr = '';
		foreach ($testCaseValues as $inOutPair)
		{
			$testValueStr .= "($pid, '".$inOutPair['input']."','".$inOutPair['output']."'),";
		}
		$testValueStr = substr($testValueStr, 0, -1);
		$queryStr = $insertStr.$testValueStr;
		if(!dbQuery($conn, $queryStr))
		{
			alertGoback('Error occurred during inserting testcase');
			gotoUrl('addProblem.php');
			exit;
		}
		else
		{
			$testCaseCount = count($testCaseValues);
			if ($testCaseCount <= 1)
				echo '<strong>'.$testCaseCount.' test case</strong> is added.<br/><br/><br/>';
			else echo '<strong>'.$testCaseCount.' test cases</strong> are added.<br/><br/><br/>';
		}
	}
}
function getCurrentStatus($statusArr)
{
	foreach($statusArr as $key => $statusValue)
	{
		if ($statusValue == true)
			return $key;
	}
	return null;
}
function resetStatus(&$statusArr)
{
	foreach ($statusArr as $key => $statusValue)
		$statusArr[$key] = false;
}
?>
