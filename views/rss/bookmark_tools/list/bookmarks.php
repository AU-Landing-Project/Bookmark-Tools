<?php

	$bookmarks = elgg_extract("bookmarks", $vars, array());
	$folder = elgg_extract("bmfolder", $vars);
	
	
	if(!($sub_folders = bookmark_tools_get_sub_folders($folder))){
		$sub_folders = array();
	}
	
	$entities = array_merge($sub_folders, $bookmarks) ;
	
	if(!empty($entities)) {
		$params = array(
			"full_view" => false,
			"pagination" => false
		);

		echo elgg_view_entity_list($entities, $params);
	}
	