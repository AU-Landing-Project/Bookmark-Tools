<?php

	$bookmark_guids = get_input("bookmark_guids");
	$folder_guids = get_input("bmfolder_guids");
	
	if(!empty($bookmark_guids) || !empty($folder_guids)){
		// remove all bookmarks
		if(!empty($bookmark_guids)){
			$bookmark_count = 0;
			
			foreach($bookmark_guids as $guid){
				if(($bookmark = get_entity($guid)) && elgg_instanceof($bookmark, "object", "bookmarks")){
					if($bookmark->canEdit()){
						if($bookmark->delete()){
							$bookmark_count++;
						}
					}
				}
			}
			
			if(!empty($bookmark_count)){
				system_message(elgg_echo("bookmark_tools:action:bulk_delete:success:bookmarks", array($bookmark_count)));
			} else {
				register_error(elgg_echo("bookmark_tools:action:bulk_delete:error:bookmarks"));
			}
		}
		
		// remove folders
		if(!empty($folder_guids)){
			$folder_count = 0;
			
			foreach($folder_guids as $guid){
				if(($folder = get_entity($guid)) && elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE)){
					if($folder->canEdit()){
						if($folder->delete()){
							$folder_count++;
						}
					}
				}
			}
			
			if(!empty($folder_count)){
				system_message(elgg_echo("bookmark_tools:action:bulk_delete:success:folders", array($folder_count)));
			} else {
				register_error(elgg_echo("bookmark_tools:action:bulk_delete:error:folders"));
			}
		}
	} else {
		register_error(elgg_echo("InvalidParameterException:MissingParameter"));
	}
	
	forward(REFERER);