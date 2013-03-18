<?php

	function bookmark_tools_can_edit_metadata_hook($hook, $type, $returnvalue, $params)
	{
		$result = $returnvalue;
	
		if(!empty($params) && is_array($params) && $result !== true)
		{
			if(array_key_exists("user", $params) && array_key_exists("entity", $params))
			{
				$entity = $params["entity"];
				$user = $params["user"];
	
				if($entity->getSubtype() == BOOKMARK_TOOLS_SUBTYPE)
				{
					$container_entity = $entity->getContainerEntity();
						
					if(($container_entity instanceof ElggGroup) && $container_entity->isMember($user) && ($container_entity->bookmark_tools_structure_management_enable != "no"))
					{
						$result = true;
					}
				}
			}
		}
	
		return $result;
	}
	
	function bookmark_tools_folder_icon_hook($hook, $type, $returnvalue, $params) {
		$result = $returnvalue;
	
		if(!empty($params) && is_array($params)){
			$entity = elgg_extract("entity", $params);
			$size = elgg_extract("size", $params, "small");
			
			if(!empty($entity) && elgg_instanceof($entity, "object", BOOKMARK_TOOLS_SUBTYPE)){
				switch($size){
					case "topbar":
					case "tiny":
					case "small":
						$result = "mod/bookmark_tools/_graphics/folder/" . $size . ".png";
						break;
					default:
						$result = "mod/bookmark_tools/_graphics/folder/medium.png";
						break;
				}
			}
		}
	
		return $result;
	}
	
	function bookmark_tools_write_acl_plugin_hook($hook, $type, $returnvalue, $params)
	{
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params))
		{
			
			if((elgg_get_context() == "bookmark_tools") && ($page_owner = elgg_get_page_owner_entity()) && ($page_owner instanceof ElggGroup))
			{
				$result = array(
					$page_owner->group_acl => elgg_echo("groups:group") . ": " . $page_owner->name,
					ACCESS_LOGGED_IN => elgg_echo("LOGGED_IN"),
					ACCESS_PUBLIC => elgg_echo("PUBLIC")
				);
			}
		}
		
		return $result;
	}
	
	function bookmark_tools_bookmark_route_hook($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($returnvalue) && is_array($returnvalue)){
			$page = elgg_extract("segments", $returnvalue);
			
			switch($page[0]){
				case "view":
					if(!elgg_is_logged_in() && isset($page[1])){
						if(!get_entity($page[1])){
							gatekeeper();
						}
					}
					break;
				case "owner":
					if(bookmark_tools_use_folder_structure()){
						$result = false;
							
						include(dirname(dirname(__FILE__)) . "/pages/list.php");
					}
					break;
				case "group":
					if(bookmark_tools_use_folder_structure()){
						$result = false;
						
						include(dirname(dirname(__FILE__)) . "/pages/list.php");
					}
					break;
  				case "add":
					$user = get_user_by_username($page[1]);
            
          if ($user) {
            $search = elgg_get_site_url() . 'bookmarks/add/' . $user->username;
            $replace = elgg_get_site_url() . 'bookmarks/add/' . $user->guid;
            $url = str_replace($search, $replace, current_page_url());
            forward($url);
          }
					break;
			}
		}
		
		return $result;
	}
	
	function bookmark_tools_folder_breadcrumb_hook($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params)){
			$folder = elgg_extract("entity", $params);
			
			$main_folder_options = array(
				"name" => "main_folder",
				"text" => elgg_echo("bookmark_tools:list:folder:main"),
				"priority" => 0
			);
			
			if(!empty($folder) && elgg_instanceof($folder, "object", BOOKMARK_TOOLS_SUBTYPE)){
				$container = $folder->getContainerEntity();
				
				$priority = 9999999;
				$folder_options = array(
					"name" => "bmfolder_" . $folder->getGUID(),
					"text" => $folder->title,
					"href" => false,
					"priority" => $priority
				);
				
				$result[] = ElggMenuItem::factory($folder_options);
				
				$parent_guid = (int) $folder->parent_guid;
				while(!empty($parent_guid) && ($parent = get_entity($parent_guid))){
					$priority--;
					$folder_options = array(
						"name" => "bmfolder_" . $parent->getGUID(),
						"text" => $parent->title,
						"href" => $parent->getURL(),
						"priority" => $priority
					);
					
					$result[] = ElggMenuItem::factory($folder_options);
					$parent_guid = (int) $parent->parent_guid;
				}
			} else {
				$container = elgg_get_page_owner_entity();
			}
			
			// make main folder item
			if(elgg_instanceof($container, "group")){
				$main_folder_options["href"] = "bookmarks/group/" . $container->getGUID() . "/all#";
			} else {
				$main_folder_options["href"] = "bookmarks/owner/" . $container->username . "/all#";
			}
			
			$result[] = ElggMenuItem::factory($main_folder_options);
		}

		return $result;
	}
	
	function bookmark_tools_folder_sidebar_tree_hook($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($params) && is_array($params)){
			$container = elgg_extract("container", $params);
			
			if(!empty($container) && (elgg_instanceof($container, "user") || elgg_instanceof($container, "group"))){
				$main_menu_item =ElggMenuItem::factory(array(
					"name" => "root",
					"text" => elgg_echo("bookmark_tools:list:folder:main"),
					"href" => "#",
					"id" => "0",
					"rel" => "root",
					"priority" => 0
				));
				
				if($folders = bookmark_tools_get_folders($container->getGUID())){
					$main_menu_item->setChildren(bookmark_tools_make_menu_items($folders));
				}
				
				$result[] = $main_menu_item;
			}
		}
		
		return $result;
	}
	
	function bookmark_tools_entity_menu_hook($hook, $type, $returnvalue, $params){
		$result = $returnvalue;
		
		if(!empty($result) && is_array($result) && !empty($params) && is_array($params)){
			$entity = elgg_extract("entity", $params);
			
			if(!empty($entity)){
				if(elgg_instanceof($entity, "object", BOOKMARK_TOOLS_SUBTYPE)){
					foreach($result as $index => $menu_item){
						if($menu_item->getName() == "likes"){
							unset($result[$index]);
						}
					}
				}
			}
		}
		
		return $result;
	}

  /*
   * forwards old links to correct content
   */
  function bookmark_tools_forward_old_link($hook, $type, $return, $params) {
    
    $result = $return;
    $parts = $return['segments'];
    
    switch ($parts[0]) {
      case 'list':
        default:
          $owner = get_entity($parts[1]);
          if (!$owner) {
            return $result; // 404
          }
          $url = elgg_get_site_url() . 'bookmarks/owner/' . $owner->username;
          if (elgg_instanceof($owner, 'group', '', 'ElggGroup')) {
            $url = elgg_get_site_url() . 'bookmarks/group/' . $owner->guid . '/all';
          }
          
          forward($url);
          return FALSE;
          break;
    }
  }
  
  
  
  
  