<?php

	global $CONFIG;

	$old_context = elgg_get_context();

	$page_owner_guid 	= elgg_get_page_owner_guid();
	$page_owner 		= elgg_get_page_owner_entity();
	$folder_guid 		= (int) get_input("bmfolder_guid", 0);
	$draw_page 			= get_input("draw_page", true);

	$sort_by 			= get_input('sort_by');
	$direction 			= get_input('direction');
	
	if(empty($sort_by)){
		$sort_value = 'e.time_created';
		if($page_owner instanceof ElggGroup && !empty($page_owner->bookmark_tools_sort)){
			$sort_value = $page_owner->bookmark_tools_sort;
		} elseif($site_sort_default = elgg_get_plugin_setting("sort", "bookmark_tools")){
			$sort_value = $site_sort_default;
		}
		
		$sort_by = $sort_value;
	} 
	
	if(empty($direction)){
		$sort_direction_value = 'asc';
		if($page_owner instanceof ElggGroup && !empty($page_owner->bookmark_tools_sort_direction)){
			$sort_direction_value = $page_owner->bookmark_tools_sort_direction;
		} elseif($site_sort_direction_default = elgg_get_plugin_setting("sort_direction", "bookmark_tools")){
			$sort_direction_value = $site_sort_direction_default;
		}
		
		$direction = $sort_direction_value;
	}
	
	if(!empty($page_owner) && (($page_owner instanceof ElggUser) || ($page_owner instanceof ElggGroup))) {
		group_gatekeeper();

		$wheres = array();
		$wheres[] = "NOT EXISTS (
					SELECT 1 FROM {$CONFIG->dbprefix}entity_relationships r 
					WHERE r.guid_two = e.guid AND
					r.relationship = '" . BOOKMARK_TOOLS_RELATIONSHIP . "')";

		$bookmarks_options = array(
				"type" => "object",
				"subtype" => "bookmarks",
				"limit" => false,
				"container_guid" => $page_owner_guid
			);

		$bookmarks_options["joins"][] = "JOIN {$CONFIG->dbprefix}objects_entity oe on oe.guid = e.guid";

		$bookmarks_options["order_by"] = $sort_by . ' ' . $direction;

		$folder = false;
		if($folder_guid !== false) {
			if($folder_guid == 0) {
				$bookmarks_options["wheres"] = $wheres;
				
				$bookmarks = elgg_get_entities($bookmarks_options);	
			} else {
				$folder = get_entity($folder_guid);

				$bookmarks_options["relationship"] = BOOKMARK_TOOLS_RELATIONSHIP;
				$bookmarks_options["relationship_guid"] = $folder_guid;
				$bookmarks_options["inverse_relationship"] = false;
				
				$bookmarks = elgg_get_entities_from_relationship($bookmarks_options);	
			}	
		}

		if(!$draw_page) {
			echo elgg_view("bookmark_tools/list/bookmarks", array("bmfolder" => $folder, "bookmarks" => $bookmarks, 'sort_by' => $sort_by, 'direction' => $direction));
		} else {
			// build breadcrumb
			elgg_push_breadcrumb(elgg_echo('bookmarks'), "bookmarks/all");
			elgg_push_breadcrumb($page_owner->name);
			
			// register title button to add a new bookmark
			elgg_register_title_button();
			
			// get data for tree
			$folders = bookmark_tools_get_folders($page_owner_guid);

			// build page elements
			$title_text = elgg_echo("bookmarks:owner", array($page_owner->name));
			
			$body = elgg_view("bookmark_tools/list/bookmarks", array("bmfolder" => $folder, "bookmarks" => $bookmarks, 'sort_by' => $sort_by, 'direction' => $direction));
			//$body = "<div id='bookmark_tools_list_bookmarks_container'>" . elgg_view("graphics/ajax_loader", array("hidden" => false)) . "</div>";
			//$body = "<div id='bookmark_tools_list_bookmarks_container'>" . elgg_view("bookmark_tools/list/bookmarks", array("bmfolder" => $folder, "bookmarks" => $bookmarks, 'sort_by' => $sort_by, 'direction' => $direction)) . "</div>";
			if (elgg_get_viewtype() == 'default') {
			  $body = "<div id='bookmark_tools_list_bookmarks_container'>" . $body . "</div>";
			}
			
			// make sidebar
			$sidebar = elgg_view("bookmark_tools/list/tree", array("bmfolder" => $folder, "bmfolders" => $folders));
			$sidebar .= elgg_view("bookmark_tools/sidebar/sort_options");
			$sidebar .= elgg_view("bookmark_tools/sidebar/info");
			$sidebar .= elgg_view('page/elements/tagcloud_block', array('subtypes' => 'bookmarks', 'owner_guid' => elgg_get_page_owner_guid()));
			
			// build page params
			$params = array(
				'title' => $title_text,
				'content' => $body, 
				'sidebar' => $sidebar
			);
			
			if(elgg_instanceof($page_owner, "user")){
        if($page_owner->guid == elgg_get_logged_in_user_guid()){
          $params["filter_context"] = "mine";
        } else {
          $params["filter_context"] = $page_owner->username;
        }
			} else {
				$params["filter"] = false;
			}
			
			echo elgg_view_page($title_text, elgg_view_layout("content", $params));
		}
	} else {
		forward();
	}