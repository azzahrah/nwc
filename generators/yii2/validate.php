<?php 
	function is_valid_domain_name($domain_name)
	{
	    return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
	            && preg_match("/^.{1,253}$/", $domain_name) //overall length check
	            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
	}

	function is_dir_empty($dir) {
	  if (!is_readable($dir)) return NULL; 
	  return (count(scandir($dir)) == 2);
	}

	$error = [];
	$data = json_decode('{{{json_data}}}');
	if (!is_valid_domain_name($data->server_name)) {
		$error['server_name'] = "Invalid domain name";
	}

	$data->root_directory = rtrim($data->root_directory,"/");
	
	if (is_dir($data->root_directory)) {
		if (!is_dir_empty($data->root_directory)) {
			$error['root_directory'] = "Directory is NOT empty";
		}
	} 
	
	if (count($error) == 0) {
		echo "SUCCESS";
	} else {
		echo json_encode($error);
	}
?>