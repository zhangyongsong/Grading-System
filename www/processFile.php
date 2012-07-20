<!--
This file provides general utility for file uploading 
-->
<?php 
	function checkUploadingErrors($userfile)
	{
		if(!isset($_FILES[$userfile]))
		{
			goBack();
			exit;
		}
		//check for file uploading errors
		if($_FILES[$userfile]['error']>0)
		{
			echo 'Problem';
			$eMessage = "";
			switch ($_FILES[$userfile]['error'])
			{
				case 1: $eMessage = 'File exceeded upload_max_filesize';
				break;
				case 2: $eMessage = 'File exceeded max_file_size';
				break;
				case 3: $eMessage = 'File only partially uploaded';
				break;
				case 4: $eMessage = 'No file uploaded';
				break;
				case 6: $eMessage = 'Cannot upload file: No temp directory specified';
				break;
				case 7: $eMessage = 'Upload failed: Cannot write to disk';
				break;
			}
			if($eMessage != "")
			{
				alertGoback($eMessage);
				goBack();
				exit;
			}
			exit;
		}
		return;
	}
	
	function uploadFile($tempFile, $regex, $destDir){
		checkUploadingErrors($tempFile);
		$filename= $_FILES[$tempFile]['name'];
		
		// check file type, the filename should be ended with .java using javascript
		if(!preg_match($regex, $filename))
		{
			alertGoback('Problem: file uploaded is not a valid type.');
			goBack();
			exit;
		}
		// a sub-directory needed for each user
		$upfile="$destDir/$filename";

		if(is_uploaded_file($_FILES[$tempFile]['tmp_name'])){
			if(!move_uploaded_file($_FILES[$tempFile]['tmp_name'], $upfile))
			{
				alertGoback('Problem: Could not move file to uploads directory');
				goBack();
				exit;
			}		
		}
		return $filename;
	}
	
	// check whether path exist, if not, create it recursively
	function make_path($pathname){	
			if (!file_exists($pathname)){
				if(!mkdir($pathname, 0777, true)){
					return false;
				}
				else{
					chmod($pathname, 0777);
				 	return true;
				}
			}
			else return true;
	}
	
?>
