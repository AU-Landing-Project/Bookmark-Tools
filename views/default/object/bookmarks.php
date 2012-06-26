<?php
	/**
 * Elgg bookmark view
 *
 * @package ElggBookmarks
 */

	$bookmark = elgg_extract("entity", $vars, false);
	$full_view = elgg_extract("full_view", $vars, false);

	if(!$bookmark){
		return true;
	}

	$bookmark_guid = $bookmark->getGUID();
	$owner = $bookmark->getOwnerEntity();
  $owner_icon = elgg_view_entity_icon($owner, 'tiny');
  
  $link = elgg_view('output/url', array('href' => $bookmark->address));
  $description = elgg_view('output/longtext', array('value' => $bookmark->description, 'class' => 'pbl'));
	
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
			"href" => $bookmark->getURL() . "#comments",
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

	if($full_view && !elgg_in_context('gallery')) {
		// normal full view
    /*
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
     * 
     */
  $params = array(
		'entity' => $bookmark,
		'title' => false,
		'metadata' => $entity_menu,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	$bookmark_icon = elgg_view_icon('push-pin-alt');
	$body = <<<HTML
<div class="bookmark elgg-content mts">
	$bookmark_icon<span class="elgg-heading-basic mbs">$link</span>
	$description
</div>
HTML;

	echo elgg_view('object/elements/full', array(
		'entity' => $bookmark,
		'icon' => $owner_icon,
		'summary' => $summary,
		'body' => $body,
	));
  
	} elseif (elgg_in_context('gallery')) {
	echo <<<HTML
<div class="bookmarks-gallery-item">
	<h3>$bookmark->title</h3>
	<p class='subtitle'>$owner_link $date</p>
</div>
HTML;
} else {
		// listing view of the bookmark
	$url = $bookmark->address;
	$display_text = $url;
	$excerpt = elgg_get_excerpt($bookmark->description);
	if ($excerpt) {
		$excerpt = " - $excerpt";
	}

  $bookmark_icon_alt = "";
  if(elgg_in_context("bookmark_tools_selector")){
    $bookmark_icon_alt = elgg_view("input/checkbox", array("name" => "bookmark_guids[]", "value" => $bookmark->getGUID(), "default" => false));
	}
      
	if (strlen($url) > 25) {
		$bits = parse_url($url);
		if (isset($bits['host'])) {
			$display_text = $bits['host'];
		} else {
			$display_text = elgg_get_excerpt($url, 100);
		}
	}

	$link = elgg_view('output/url', array(
		'href' => $bookmark->address,
		'text' => $display_text,
	));

	$content = elgg_view_icon('push-pin-alt') . "$link{$excerpt}";

	$params = array(
		'entity' => $bookmark,
		'metadata' => $entity_menu,
		'subtitle' => $subtitle,
		'content' => $content,
	);
	$params = $params + $vars;
	$body = elgg_view('object/elements/summary', $params);
	
	echo elgg_view_image_block($owner_icon, $body, array("class" => "bookmark-tools-bookmark", "image_alt" => $bookmark_icon_alt));
	}
	