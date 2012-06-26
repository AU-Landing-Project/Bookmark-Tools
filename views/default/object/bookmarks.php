<?php
	/**
	 * Elgg file browser.
	 * File renderer.
	 * 
	 * @package ElggFile
	 */

	$bookmark = elgg_extract("entity", $vars, false);
	$full_view = elgg_extract("full_view", $vars, false);

	if(!$bookmark){
		return true;
	}

	$bookmark_guid = $bookmark->getGUID();
	$owner = $bookmark->getOwnerEntity();
	
	$tags = elgg_view("output/tags", array("value" => $bookmark->tags));
	$categories = elgg_view('output/categories', $vars);
	
	$title = $bookmark->title;
	
	$owner_link = elgg_view("output/url", array("text" => $owner->name, "href" => $owner->getURL(), "is_trusted" => true));
	$author_text = elgg_echo("byline", array($owner_link));
	
	// which time format to show
	$time_preference = "date";
	
	if($user_time_preference = elgg_get_plugin_user_setting("bookmark_tools_time_display", null, "bookmark_tools")){
		$time_preference = $user_time_preference;
	} elseif($site_time_preference = elgg_get_plugin_setting("bookmark_tools_default_time_display", "bookmark_tools")) {
		$time_preference = $site_time_preference;
	}
	
	if($time_preference == "date")	{
		$date = date(elgg_echo("friendlytime:date_format"), $bookmark->time_created);
	} else {
		$date = elgg_view_friendly_time($bookmark->time_created);
	}
	
	// count comments
	$comments_link = "";
	$comment_count = (int) $bookmark->countComments();
	if($comment_count > 0){
		$comments_link = elgg_view("output/url", array(
			"href" => $bookmark->getURL() . "#bookmark-comments",
			"text" => elgg_echo("comments") . " ($comment_count)",
			"is_trusted" => true,
		));
	}
	
	$subtitle = "$author_text $date $comments_link $categories";

	// title
	if (empty($title)) {
		$title = elgg_echo("untitled");
	}

	// entity actions
	$entity_menu = "";
	if(!elgg_in_context("widgets")){
		$entity_menu = elgg_view_menu("entity", array(
			"entity" => $bookmark,
			"handler" => "bookmarks",
			"sort_by" => "priority",
			"class" => "elgg-menu-hz"
		));
	}

	if($full_view) {
		// normal full view
		$extra = "";
		
		$params = array(
			"entity" => $bookmark,
			"title" => $title,
			"metadata" => $entity_menu,
			"subtitle" => $subtitle,
			"tags" => $tags
		);
		$params = $params + $vars;
		$summary = elgg_view("object/elements/summary", $params);
		
		$text = elgg_view("output/longtext", array("value" => $bookmark->description));
		$body = "$text $extra";
		
		echo elgg_view("object/elements/full", array(
				"entity" => $bookmark,
				"title" => false,
				"icon" => elgg_view_entity_icon(get_entity($bookmark->owner_guid), "small"),
				"summary" => $summary,
				"body" => $body
		));
	} else {
		// listing view of the bookmark
		$bookmark_icon_alt = "";
		if(bookmark_tools_use_folder_structure()){
			$bookmark_icon = elgg_view_entity_icon(get_entity($bookmark->owner_guid), "tiny", array("img_class" => "bookmark-tools-icon-tiny"));
			
			if(elgg_in_context("bookmark_tools_selector")){
				$bookmark_icon_alt = elgg_view("input/checkbox", array("name" => "bookmark_guids[]", "value" => $bookmark->getGUID(), "default" => false));
			}
			
			$excerpt = "";
			$subtitle = "";
			$tags = "";
		} else {
			$bookmark_icon = elgg_view_entity_icon(get_entity($bookmark->owner_guid), "small");
			$excerpt = elgg_get_excerpt($bookmark->description);
		}
		
		$params = array(
			"entity" => $bookmark,
			"metadata" => $entity_menu,
			"subtitle" => $subtitle,
			"tags" => $tags,
			"content" => $excerpt
		);
		$params = $params + $vars;
		$list_body = elgg_view("object/elements/summary", $params);
		
		echo elgg_view_image_block($bookmark_icon, $list_body, array("class" => "bookmark-tools-bookmark", "image_alt" => $bookmark_icon_alt));
	}
	