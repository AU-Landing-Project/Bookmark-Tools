<?php

	$page_owner = elgg_get_page_owner_entity();
	
	// show sorting options
	$title = elgg_echo('bookmark_tools:list:bookmarks:options:sort_title');
	
	// sort options
	$sort_value = 'e.time_created';
	if(isset($_SESSION["bookmark_tools"]) && is_array($_SESSION["bookmark_tools"]) && !empty($_SESSION["bookmark_tools"]["sort"])){
		$sort_value = $_SESSION["bookmark_tools"]["sort"];
	} else {
		if(elgg_instanceof($page_owner, "group") && !empty($page_owner->bookmark_tools_sort)){
			$sort_value = $page_owner->bookmark_tools_sort;
		} elseif($site_sort_default = elgg_get_plugin_setting("sort", "bookmark_tools")){
			$sort_value = $site_sort_default;
		}
	}
	
	$body = elgg_view('input/dropdown', array('name' => 'bookmark_sort',
													'value' => $sort_value,
													'options_values' => array(
																		'oe.title' 			=> elgg_echo('title'), 
																		'oe.description'	=> elgg_echo('description'), 
																		'e.time_created' 	=> elgg_echo('bookmark_tools:list:sort:time_created'))));
	
	// sort direction
	$sort_direction_value = 'asc';
	if(isset($_SESSION["bookmark_tools"]) && is_array($_SESSION["bookmark_tools"]) && !empty($_SESSION["bookmark_tools"]["sort_direction"])){
		$sort_direction_value = $_SESSION["bookmark_tools"]["sort_direction"];
	} else {
		if(elgg_instanceof($page_owner, "group") && !empty($page_owner->bookmark_tools_sort_direction)){
			$sort_direction_value = $page_owner->bookmark_tools_sort_direction;
		} elseif($site_sort_direction_default = elgg_get_plugin_setting("sort_direction", "bookmark_tools")){
			$sort_direction_value = $site_sort_direction_default;
		}
	}
	
	$body .= "<br />";
	$body .= elgg_view('input/dropdown', array('name' => 'bookmark_sort_direction',
												'value' => $sort_direction_value,
													'options_values' => array(
																		'asc' 	=> elgg_echo('bookmark_tools:list:sort:asc'), 
																		'desc'	=> elgg_echo('bookmark_tools:list:sort:desc'))));
	// output sorting module
	//echo elgg_view_module("aside", $title, $body, array("id" => "bookmark_tools_list_bookmarks_sort_options"));