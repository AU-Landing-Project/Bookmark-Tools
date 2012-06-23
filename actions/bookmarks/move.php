<?php 

	
	$bookmark_guid 		= (int) get_input("bookmark_guid", 0);
	$folder_guid 	= (int) get_input("bmfolder_guid", 0);
	
	if(!empty($bookmark_guid)) {
		if($bookmark = get_entity($bookmark_guid)) {
			$container_entity = $bookmark->getContainerEntity();
			
			if(($bookmark->canEdit() || (elgg_instanceof($container_entity, "group") && $container_entity->isMember()))) {
				if(elgg_instanceof($bookmark, "object", "bookmarks")) {
					// check if a given guid is a folder
					if(!empty($folder_guid)) {
						if(!($folder = get_entity($folder_guid)) || !elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE)) {
							unset($folder_guid);
						}
					}
					
					// remove old relationships
					remove_entity_relationships($bookmark->getGUID(), BOOKMARK_TOOLS_RELATIONSHIP, true);
					
					if(!empty($folder_guid)) {
						add_entity_relationship($folder_guid, BOOKMARK_TOOLS_RELATIONSHIP, $bookmark_guid);
					}
					
					system_message(elgg_echo("bookmark_tools:action:move:success:bookmark"));
					
				} elseif(elgg_instanceof($bookmark, "object", BOOKMARK_TOOLS_SUBTYPE)) {
					$bookmark->parent_guid = $folder_guid;
					
					system_message(elgg_echo("bookmark_tools:action:move:success:folder"));
				}
			} else {
				register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
			}
		} else {
			register_error(elgg_echo("InvalidParameterException:NoEntityFound"));
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);