<?php 

	
	$folder_guid = (int) get_input("bmfolder_guid", 0);
	$parent_guid = (int) get_input("parent_guid", 0);
	$order = get_input("order");
	
	if(!empty($folder_guid) && (!empty($parent_guid) || $parent_guid == 0)) {
		// if parent guid, check if it is a folder
		if(!empty($parent_guid)) {
			if(!($parent = get_entity($parent_guid)) || !elgg_instanceof($parent, "object", BOOKMARK_TOOLS_SUBTYPE)) {
				unset($parent_guid);
			}
		}
		
		// get folder from folder_guid and check if it is a folder
		if(($folder = get_entity($folder_guid)) && !is_null($parent_guid)) {
			if(elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE) && $folder->canEditMetadata("parent_guid")) {
				// set new parent_guid
				$folder->parent_guid = $parent_guid;
				$folder->save();
			}
		}
		
		// reorder
		if(!empty($order) && !is_array($order)) {
			$order = array($order);
		}
		
		if(!empty($order) && !is_null($parent_guid)) {
			foreach($order as $index => $order_guid) {
				if($folder = get_entity($order_guid)) {
					if(elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE) && $folder->canEditMetadata("order")) {
						if($folder->parent_guid == $parent_guid) {
							$folder->order = $index;
							$folder->save();
						}
					}
				}
			}
		}
		
		system_message(elgg_echo("bookmark_tools:action:folder:reorder:success"));
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);