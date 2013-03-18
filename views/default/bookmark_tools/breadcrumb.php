<?php 

if (elgg_get_context() != 'bookmarks') {
  return true;
}
	$folder = elgg_extract("entity", $vars);
	
	echo "<div id='bookmark_tools_breadcrumbs' class='clearfix'>";	
	echo elgg_view_menu("bookmark_tools_folder_breadcrumb", array(
		"entity" => $folder,
		"sort_by" => "priority",
		"class" => "elgg-menu-hz"
	));	
	
	echo "</div>";
	
	if($folder) {
		echo elgg_view_entity($folder, array("full_view" => true));
	}
	