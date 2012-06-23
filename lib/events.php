<?php

	function bookmark_tools_object_handler($event, $type, $object) {
		
		if(!empty($object) && elgg_instanceof($object, "object", "bookmarks")) {
			$folder_guid = (int) get_input("bmfolder_guid", 0);

			if(!empty($folder_guid)) {
				if($folder = get_entity($folder_guid)) {
					if(!elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE)) {
						unset($folder_guid);
					}
				} else {
					unset($folder_guid);
				}
			}

			// remove old relationships
			remove_entity_relationships($object->getGUID(), BOOKMARK_TOOLS_RELATIONSHIP, true);
				
			if(!empty($folder_guid)) {
				add_entity_relationship($folder_guid, BOOKMARK_TOOLS_RELATIONSHIP, $object->getGUID());
			}
		}
	}
	
	function bookmark_tools_object_handler_delete($event, $type, $object)
	{
		if(!empty($object) && elgg_instanceof($object, "object", BOOKMARK_TOOLS_SUBTYPE)) {
			// find subfolders
			$options = array(
				"type" => "object",
				"subtype" => BOOKMARK_TOOLS_SUBTYPE,
				"owner_guid" => $object->getOwnerGUID(),
				"limit" => false,
				"metadata_name" => "parent_guid",
				"metadata_value" => $object->getGUID()
			);

			if($subfolders = elgg_get_entities_from_metadata($options)) {
				// delete subfolders
				foreach($subfolders as $subfolder) {
					$subfolder->delete();
				}
			}

			// should we remove bookmarks?
			if(get_input("bookmarks") == "yes") {
				// find bookmark in this folder
				$options = array(
					"type" => "object",
					"subtype" => "bookmarks",
					"container_guid" => $object->getContainerGUID(),
					"limit" => false,
					"relationship" => BOOKMARK_TOOLS_RELATIONSHIP,
					"relationship_guid" => $object->getGUID()
				);
					
				if($bookmarks = elgg_get_entities_from_relationship($options)) {
					// delete bookmarks in folder
					foreach($bookmarks as $bookmark) {
						$bookmark->delete();
					}
				}
			}
		}
	}