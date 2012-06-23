<?php
	$plugin = $vars["entity"];
	
	$options = array(
		"date" => elgg_echo("bookmark_tools:usersettings:time:date"),
		"days" => elgg_echo("bookmark_tools:usersettings:time:days")
	);
	
	$bookmark_tools_time_display_value = $plugin->getUserSetting("bookmark_tools_time_display", elgg_get_page_owner_guid());
	if(empty($bookmark_tools_time_display_value)) {
		$bookmark_tools_time_display_value = elgg_get_plugin_setting("bookmark_tools_default_time_display", "bookmark_tools");
	}

	echo "<div>";
	echo elgg_echo("bookmark_tools:usersettings:time:description");
	echo "</div>";
	
	echo "<div>";
	echo elgg_echo("bookmark_tools:usersettings:time");
	echo "&nbsp;" . elgg_view("input/dropdown", array("name" => "params[bookmark_tools_time_display]", "options_values" => $options, "value" => $bookmark_tools_time_display_value));
	echo "</div>";
	