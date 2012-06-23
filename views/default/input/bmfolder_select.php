<?php 

	$folder_guid = (int) get_input('bmfolder_guid');

	$container_guid = elgg_extract("container_guid", $vars, elgg_get_page_owner_guid());
	$current_folder	= elgg_extract("bmfolder", $vars, $folder_guid);
	$type = elgg_extract("type", $vars);
	
	unset($vars["bmfolder"]);
	unset($vars["type"]);
	unset($vars["container_guid"]);
	
	if($type == 'bmfolder') {
		if(!elgg_extract("value", $vars)) {
			if(!empty($current_folder)) {
				$vars["value"] = get_entity($current_folder)->parent_guid;
			}
		}
	} elseif(!elgg_extract("value", $vars)) {
		$vars["value"] = $current_folder;
	}
	
	$folders = bookmark_tools_get_folders($container_guid);
	
	$options = array(
		0 => elgg_echo("bookmark_tools:input:folder_select:main")
	);
	
	if(!empty($folders)) {
		$options = $options + bookmark_tools_build_select_options($folders, 1);
	}
	
	$vars["options_values"] = $options;
	
	echo elgg_view("input/dropdown", $vars);
	