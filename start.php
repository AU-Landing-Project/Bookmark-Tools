<?php

	define("BOOKMARK_TOOLS_SUBTYPE", 		"bmfolder");
	define("BOOKMARK_TOOLS_RELATIONSHIP", 	"folder_of");
	define("BOOKMARK_TOOLS_BASEURL", 		elgg_get_site_url() . "bookmark_tools/");

	require_once(dirname(__FILE__) . "/lib/functions.php");
	require_once(dirname(__FILE__) . "/lib/events.php");
	require_once(dirname(__FILE__) . "/lib/hooks.php");
	require_once(dirname(__FILE__) . "/lib/page_handlers.php");

	function bookmark_tools_init() {
		// extend CSS
		elgg_extend_view("css/elgg", "bookmark_tools/css/site");
		if(bookmark_tools_use_folder_structure()){
			elgg_extend_view("groups/edit", "bookmark_tools/group_settings");
		}
		
		// extend js
		elgg_extend_view("js/elgg", "bookmark_tools/js/site");
		
		// register JS libraries
		$vendors = elgg_get_site_url() . "mod/bookmark_tools/vendors/";
		
		elgg_register_js("jquery.tree", $vendors . "jstree/jquery.tree.min.js");
		elgg_register_css("jquery.tree", $vendors . "jstree/themes/default/style.css");
		
		elgg_register_js("jquery.hashchange", $vendors . "hashchange/jquery.hashchange.js");
		
		// register page handler for nice URL's
		elgg_register_page_handler("bookmark_tools", "bookmark_tools_page_handler");
		
		// make our own URLs for folders
		elgg_register_entity_url_handler("object", BOOKMARK_TOOLS_SUBTYPE, "bookmark_tools_folder_url_handler");
		
		// make our own URLs for folder icons
		elgg_register_plugin_hook_handler("entity:icon:url", "object", "bookmark_tools_folder_icon_hook");
		
		// register group option to allow management of bookmark tree structure
		add_group_tool_option("bookmark_tools_structure_management", elgg_echo("bookmark_tools:group_tool_option:structure_management"));
		
		// register events
		elgg_register_event_handler("create", "object", "bookmark_tools_object_handler");
		elgg_register_event_handler("update", "object", "bookmark_tools_object_handler");
		elgg_register_event_handler("delete", "object", "bookmark_tools_object_handler_delete");
		
		// register hooks
		elgg_register_plugin_hook_handler("register", "menu:entity", "bookmark_tools_entity_menu_hook");
		elgg_register_plugin_hook_handler("permissions_check:metadata", "object", "bookmark_tools_can_edit_metadata_hook");
		elgg_register_plugin_hook_handler("route", "bookmarks", "bookmark_tools_bookmark_route_hook");
		elgg_register_plugin_hook_handler("route", "bookmarks_tree", "bookmark_tools_forward_old_link");
		
		elgg_register_plugin_hook_handler("register", "menu:bookmark_tools_folder_breadcrumb", "bookmark_tools_folder_breadcrumb_hook");
		elgg_register_plugin_hook_handler("register", "menu:bookmark_tools_folder_sidebar_tree", "bookmark_tools_folder_sidebar_tree_hook");
		
		// register actions
		elgg_register_action("bookmark_tools/groups/save_sort", dirname(__FILE__) . "/actions/groups/save_sort.php");
		elgg_register_action("bookmark_tools/folder/edit", dirname(__FILE__) . "/actions/folder/edit.php");
		elgg_register_action("bookmark_tools/bmfolder/delete", dirname(__FILE__) . "/actions/folder/delete.php");
		elgg_register_action("bookmark_tools/folder/reorder", dirname(__FILE__) . "/actions/folder/reorder.php");
		elgg_register_action("bookmark_tools/bookmarks/hide", dirname(__FILE__) . "/actions/bookmarks/hide.php");
		
		elgg_register_action("bookmark/move", dirname(__FILE__) . "/actions/bookmarks/move.php");
		elgg_register_action("bookmark/bulk_delete", dirname(__FILE__) . "/actions/bookmarks/bulk_delete.php");
		
	}
  

	function bookmark_tools_folder_url_handler($entity) {
		$container = $entity->getContainerEntity();
		
		if(elgg_instanceof($container, "group")){
			$result = "bookmarks/group/" . $container->getGUID() . "/all#" . $entity->getGUID();
		} else {
			$result = "bookmarks/owner/" . $container->username . "#" . $entity->getGUID();
		}
		
		return $result;
	}

	// register default Elgg events
	elgg_register_event_handler("init", "system", "bookmark_tools_init");
	