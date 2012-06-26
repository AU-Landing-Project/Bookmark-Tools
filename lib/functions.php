<?php
	
	function bookmark_tools_has_bookmarks($folder) {
		$bookmarks_options = array(
			"type" => "object",
			"subtype" => "bookmarks",
			"limit" => false,
			//"container_guid" => get_loggedin_userid(),
			"relationship" => BOOKMARK_TOOLS_RELATIONSHIP,
			"relationship_guid" => $folder->guid,
			"inverse_relationship" => false			
		);
		
		$bookmark_guids = array();
		
		if($bookmarks = elgg_get_entities_from_relationship($bookmarks_options)) {
			foreach($bookmarks as $bookmark) {
				$bookmark_guids[] = $bookmark->getGUID();
			}
			
			return $bookmark_guids;
		}
		
		return false;
	}

	function bookmark_tools_get_folders($container_guid = 0) {
		$result = false;
		
		if(empty($container_guid)) {
			$container_guid = elgg_get_page_owner_guid();
		}
		
		if(!empty($container_guid)) {
			$options = array(
				"type" => "object",
				"subtype" => BOOKMARK_TOOLS_SUBTYPE,
				"container_guid" => $container_guid,
				"limit" => false
			);

			if($folders = elgg_get_entities($options)) {
				$parents = array();

				foreach($folders as $folder) {
					$parent_guid = (int) $folder->parent_guid; 
					
					if(!empty($parent_guid)) {
						if($temp = get_entity($parent_guid)) {
							if($temp->getSubtype() != BOOKMARK_TOOLS_SUBTYPE) {
								$parent_guid = 0;
							}
						} else {
							$parent_guid = 0;
						}
					} else {
						$parent_guid = 0;
					}
					
					if(!array_key_exists($parent_guid, $parents)) {
						$parents[$parent_guid] = array();
					}
					
					$parents[$parent_guid][] = $folder;
				}
				
				$result = bookmark_tools_sort_folders($parents, 0);				
			}
		}
		return $result;
	}
	
	function bookmark_tools_build_select_options($folders, $depth = 0) {
		$result = array();
		
		if(!empty($folders)){
			foreach($folders as $index => $level){
				/**
				 * $level contains
				 * folder: the folder on this level
				 * children: potential children
				 * 
				 */
				if($folder = elgg_extract("bmfolder", $level)){
					$result[$folder->getGUID()] = str_repeat("-", $depth) . $folder->title;
				}
				
				if($childen = elgg_extract("children", $level)){
					$result += bookmark_tools_build_select_options($childen, $depth + 1);
				}
			}
		}
		
		return $result;
	}
	
	
	function bookmark_tools_sort_folders($folders, $parent_guid = 0) {		
		$result = false;
		
		if(array_key_exists($parent_guid, $folders)) {
			$result = array();
			
			foreach($folders[$parent_guid] as $subfolder) {
				$children = bookmark_tools_sort_folders($folders, $subfolder->getGUID());
				
				$order = $subfolder->order;
				if(empty($order)) {
					$order = 0;
				}
				
				while(array_key_exists($order, $result)) {
					$order++;
				}
				
				$result[$order] = array(
					"bmfolder" => $subfolder,
					"children" => $children
				);
			}
			
			ksort($result);
		}
		
		return $result;
	}
	
	function bookmark_tools_get_sub_folders($folder = false, $list = false) {
		$result = false;
		
		if(!empty($folder) && elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE)){
			$container_guid = $folder->getContainerGUID();
			$parent_guid = $folder->getGUID();
		} else {
			$container_guid = elgg_get_page_owner_guid();
			$parent_guid = 0;
		}
		
		$options = array(
			"type" => "object",
			"subtype" => BOOKMARK_TOOLS_SUBTYPE,
			"container_guid" => $container_guid,
			"limit" => false,
			"metadata_name" => "parent_guid",
			"metadata_value" => $parent_guid,
			"order_by_metadata" => array('name' => 'order', 'direction' => 'ASC'),
			"full_view" => false,
			"pagination" => false
		);
		
		if($list){
			$folders = elgg_list_entities_from_metadata($options);
		} else {
			$folders = elgg_get_entities_from_metadata($options);
		}
		
		if($folders) {
			$result = $folders;		
		}
		
		return $result;
	}
	
	function bookmark_tools_make_menu_items($folders){
		$result = false;
		
		if(!empty($folders) && is_array($folders)){
			$result = array();
			
			foreach($folders as $index => $level){
				if($folder = elgg_extract("bmfolder", $level)){
					$folder_menu = ElggMenuItem::factory(array(
						"name" => "folder_" . $folder->getGUID(),
						"text" => $folder->title,
						"href" => "#" . $folder->getGUID(),
						"priority" => $folder->order
					));
					
					if($children = elgg_extract("children", $level)){
						$folder_menu->setChildren(bookmark_tools_make_menu_items($children));
					}
					
					$result[] = $folder_menu;
				}
			}
		}
		
		return $result;
	}
	
	function bookmark_tools_change_children_access($folder, $change_bookmarks = false) {
		
		if(!empty($folder) && ($folder instanceof ElggObject)) {
			if($folder->getSubtype() == BOOKMARK_TOOLS_SUBTYPE) {
				// get children folders
				$options = array(
					"type" => "object",
					"subtype" => BOOKMARK_TOOLS_SUBTYPE,
					"container_guid" => $folder->getContainerGUID(),
					"limit" => false,
					"metadata_name" => "parent_guid",
					"metadata_value" => $folder->getGUID()
				);
				
				if($children = elgg_get_entities_from_metadata($options)) {
					foreach($children as $child) {
						$child->access_id = $folder->access_id;
						$child->save();
						
						bookmark_tools_change_children_access($child, $change_bookmarks);
					}
				}
				
				if($change_bookmarks) {
					// change access on bookmarks in this folder
					bookmark_tools_change_bookmarks_access($folder);
				}
			}
		}
	}
	
	function bookmark_tools_change_bookmarks_access($folder) {
		if(!empty($folder) && ($folder instanceof ElggObject)) {
			if($folder->getSubtype() == BOOKMARK_TOOLS_SUBTYPE) {
				// change access on bookmarks in this folder
				$options = array(
					"type" => "object",
					"subtype" => "bookmarks",
					"container_guid" => $folder->getContainerGUID(),
					"limit" => false,
					"relationship" => BOOKMARK_TOOLS_RELATIONSHIP,
					"relationship_guid" => $folder->getGUID()
				);
				
				if($bookmarks = elgg_get_entities_from_relationship($options)) {
					foreach($bookmarks as $bookmark) {
						$bookmark->access_id = $folder->access_id;
						$bookmark->save();
					}
				}
			}
		}	
	}
	
	
	function bookmark_tools_check_foldertitle_exists($title, $container_guid, $parent_guid = 0) {
		$result = false;
		
		$entities_options = array(
			'type' => 'object',
			'subtype' => BOOKMARK_TOOLS_SUBTYPE,
        	'container_guid' => $container_guid,
			'limit' => 1,
			'joins' => array(
				"JOIN " . elgg_get_config("dbprefix") . "objects_entity oe ON e.guid = oe.guid"
			),
			'wheres' => array(
				"oe.title = '" . sanitise_string($title) . "'"
			),
			"order_by_metadata" => array(
				"name" => "order",
				"direction" => "ASC",
				"as" => "integer"
			)
		);
		
		if(!empty($parent_guid)){
			$entities_options["metadata_name_value_pairs"] = array("parent_guid" => $parent_guid);
		}
					
		if($entities = elgg_get_entities_from_metadata($entities_options)) {
			$result = $entities[0];
		}
		
		return $result;
	}	

	
	/**
	 * Helper function to check if we use a folder structure
	 * Result is cached to increase performance
	 * 
	 * @return boolean
	 */
	function bookmark_tools_use_folder_structure(){
		static $result;
		
		if(!isset($result)){
			$result = false;
			
			// this plugin setting has a typo, should be use_folder_struture
			// @todo: update the plugin setting name
			if(elgg_get_plugin_setting("user_folder_structure", "bookmark_tools") == "yes"){
				$result = true;
			}
		}
		
		return $result;
	}
		