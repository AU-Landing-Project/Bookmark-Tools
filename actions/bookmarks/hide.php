<?php 

	$action = get_input('hide');
	$bookmark_guid = get_input('guid');
	
	if(($bookmark = get_entity($bookmark_guid)) && ($bookmark->getSubtype() == 'bookmarks')) {
		if($bookmark->canEdit()) {
			if($action == 'show') {
				$bookmark->show_in_widget = time();
			} elseif($action == 'hide') {
				unset($bookmark->show_in_widget);
			}
			
			$bookmark->save();
		}
	}
	
	$options = array(
		"type" => "object",
		"subtype" => BOOKMARK_TOOLS_SUBTYPE,
		"container_guid" => $bookmark->getOwnerGUID(),
		"limit" => false,
		"relationship" => BOOKMARK_TOOLS_RELATIONSHIP,
		"relationship_guid" => $bookmark->getGUID(),
		"inverse_relationship" => true
	);
	
	if(stristr($_SERVER["HTTP_REFERER"], "bookmark")){
	
		if($folders = elgg_get_entities_from_relationship($options)){
			$folder = $folders[0];
			
			forward($folder->getURL());
		}
	
	}
	
	forward(REFERER);