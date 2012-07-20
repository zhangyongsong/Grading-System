<?php
// this file contains utility functions for all php processing..

// when use this function, make sure that there is a db connection
function escapeString($conn, $string){
	if(!get_magic_quotes_gpc())
		$string = $conn->real_escape_string($string);
	return $string;
}


// the xml file path is known
// when pass in path, prepend with /
function getXmlValue($path){
	$xmlFile = "../gradingsystem/WebConfig.xml";
	$xmlElement = new SimpleXmlElement($xmlFile, null, true);
	$values = array();
	foreach ($xmlElement->xpath($path) as $value){
		array_push($values, $value);
	}
	$length = count($values);
	if($length >1)
		return $values;
	elseif($length ==1)
		return $values[0];
	else return null;
}

function getXmlAttribute($xmlPath, $attrName){
	$xmlFile = "../gradingsystem/WebConfig.xml";
	$xmlElement = new SimpleXmlElement($xmlFile, null, true);
	$attributes=array();
	foreach($xmlElement->xpath($xmlPath) as $obj){
		$attr = $obj[$attrName];
		array_push($attributes, $attr);
	}
	if(count($attributes)>0)
		return $attributes[0];
	else return null;
}

// this function checks whether status is Yes or not and add html color for it
function interpretStatus($status){
	if(stristr($status, 'yes')===false)
		return "<font color='red'>".$status."</font>";
	else 
		return "<font color='green'>".$status."</font>";
}
?>
