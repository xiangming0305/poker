<?php

	require_once "../../config.inc";

	$name = $_GET['name'];
	
	$id = $_GET[id];
	$nest = $_GET[nest];
	
	$catalog = new RCatalog($name);
	
	echo $catalog->addItemTo($id, $nest);
	echo mysqli_error();
	
?>