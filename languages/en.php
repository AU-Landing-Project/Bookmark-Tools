<?php 

	$english = array(
		'bookmark_tools' => "Bookmark Tools",
	
		'bookmark_tools:bookmark:actions' => 'Actions',
	
		'bookmark_tools:list:sort:type' => 'Type',
		'bookmark_tools:list:sort:time_created' => 'Time created',
		'bookmark_tools:list:sort:asc' => 'Ascending',
		'bookmark_tools:list:sort:desc' => 'Descending',
	
		// object name
		'item:object:folder' => "Bookmark Folder",
	
		// menu items
		'bookmark_tools:menu:mine' => "Your folders",
		'bookmark_tools:menu:user' => "%s's folders",
		'bookmark_tools:menu:group' => "Group bookmark folders",
		
		// group tool option
		'bookmark_tools:group_tool_option:structure_management' => "Allow management of bookmarks folders by members",
		
		// views
	
		// object
		'bookmark_tools:object:bookmarks' => "%s bookmark(s) in this folder",
		'bookmark_tools:object:no_bookmarks' => "No bookmarks in this folder",
	
		// input - folder select
		'bookmark_tools:input:folder_select:main' => "Main folder",
	
		// list
		'bookmark_tools:list:title' => "List bookmark folders",
		
		'bookmark_tools:list:folder:main' => "Main folder",
		'bookmark_tools:list:bookmarks:none' => "No bookmarks found in this folder",
		'bookmark_tools:list:select_all' => 'Select all',
		'bookmark_tools:list:deselect_all' => 'Deselect all',
		'bookmark_tools:list:download_selected' => 'Download selected',
		'bookmark_tools:list:delete_selected' => 'Delete selected',
		'bookmark_tools:list:alert:not_all_deleted' => 'Not all bookmarks could be deleted',
		'bookmark_tools:list:alert:none_selected' => 'No items selected',
		
	
		'bookmark_tools:list:tree:info' => "Did you know?",
		'bookmark_tools:list:tree:info:1' => "You can drag and drop bookmarks on to the folders to organize them!",
		'bookmark_tools:list:tree:info:2' => "You can double click on any folder to expand all of its subfolders!",
		'bookmark_tools:list:tree:info:3' => "You can reorder folders by dragging them to their new place in the tree!",
		'bookmark_tools:list:tree:info:4' => "You can move complete folder structures!",
		'bookmark_tools:list:tree:info:5' => "If you delete a folder, you can optionally choose to delete all bookmarks!",
		'bookmark_tools:list:tree:info:6' => "When you delete a folder, all subfolders will also be deleted!",
		'bookmark_tools:list:tree:info:7' => "This message is random!",
		'bookmark_tools:list:tree:info:8' => "When you remove a folder, but not it's bookmarks, the bookmarks will appear at the top level folder!",
		'bookmark_tools:list:tree:info:9' => "A newly added folder can be placed directly in the correct subfolder!",
		'bookmark_tools:list:tree:info:10' => "When uploading or editing a bookmark you can choose in which folder it should appear!",
		'bookmark_tools:list:tree:info:11' => "You can drag and drop bookmarks into your folders!",
		'bookmark_tools:list:tree:info:12' => "You can update the access level on all subfolders and even (optional) on all bookmarks when editing a folder!",
	
		'bookmark_tools:list:bookmarks:options:sort_title' => 'Sorting',
		'bookmark_tools:list:bookmarks:options:view_title' => 'View',
	
		'bookmark_tools:usersettings:time' => 'Time display',
		'bookmark_tools:usersettings:time:description' => 'Change the way the bookmark/folder time is displayed ',
		'bookmark_tools:usersettings:time:default' => 'Default time display',
		'bookmark_tools:usersettings:time:date' => 'Date',
		'bookmark_tools:usersettings:time:days' => 'Days ago',
		
		// new/edit
		'bookmark_tools:new:title' => "New bookmark folder",
		'bookmark_tools:edit:title' => "Edit bookmark folder",
		'bookmark_tools:forms:edit:title' => "Title",
		'bookmark_tools:forms:edit:description' => "Description",
		'bookmark_tools:forms:edit:parent' => "Select a parent folder",
		'bookmark_tools:forms:edit:change_children_access' => "Update access on all subfolders",
		'bookmark_tools:forms:edit:change_bookmarks_access' => "Update access on all bookmarks in this folder (and all subfolders if selected)",
		'bookmark_tools:forms:browse' => 'Browse..',
		'bookmark_tools:forms:empty_queue' => 'Empty queue',
	
		'bookmark_tools:folder:delete:confirm_bookmarks' => "Do you also wish to delete all bookmarks in the removed (sub)folders",
	
		// actions
		// edit
		'bookmark_tools:action:edit:error:input' => "Incorrect input to create/edit a bookmark folder",
		'bookmark_tools:action:edit:error:owner' => "Could not find the owner of the bookmark folder",
		'bookmark_tools:action:edit:error:folder' => "No folder to create/edit",
		'bookmark_tools:action:edit:error:save' => "Unknown error occured while saving the bookmark folder",
		'bookmark_tools:action:edit:success' => "bookmark folder successfully created/edited",
	
		'bookmark_tools:action:move:parent_error' => "Can\'t drop the folder in itself.",
		
		// delete
		'bookmark_tools:actions:delete:error:input' => "Invalid input to delete a bookmark folder",
		'bookmark_tools:actions:delete:error:entity' => "The given GUID could not be found",
		'bookmark_tools:actions:delete:error:subtype' => "The given GUID is not a bookmark folder",
		'bookmark_tools:actions:delete:error:delete' => "An unknown error occured while deleting the bookmark folder",
		'bookmark_tools:actions:delete:success' => "The bookmark folder was deleted successfully",
	
		//errors
		'bookmark_tools:error:pageowner' => 'Error retrieving page owner.',
		'bookmark_tools:error:nobookmarkfound' => 'Choose a bookmark to upload.',
	
		//messages
		
		// move
		'bookmark_tools:action:move:success:bookmark' => "The bookmark was moved successfully",
		'bookmark_tools:action:move:success:folder' => "The folder was moved successfully",
		
		// buld delete
		'bookmark_tools:action:bulk_delete:success:bookmarks' => "Successfully removed %s bookmarks",
		'bookmark_tools:action:bulk_delete:error:bookmarks' => "There was an error while removing some bookmarks",
		'bookmark_tools:action:bulk_delete:success:folders' => "Successfully removed %s folders",
		'bookmark_tools:action:bulk_delete:error:folders' => "There was an error while removing some folders",
		
		// reorder
		'bookmark_tools:action:folder:reorder:success' => "Successfully reordered the folder(s)",
		
		//settings
		'bookmark_tools:settings:user_folder_structure' => 'Use folder structure',
		'bookmark_tools:settings:sort:default' => 'Default bookmark folder sorting options',
	
		'bookmark:type:application' => 'Application',
		'bookmark:type:text' => 'Text',

		// widgets
		// bookmark tree
		'widgets:bookmark_tree:title' => "Folders",
		'widgets:bookmark_tree:description' => "Showcase your bookmark folders",
		
		'widgets:bookmark_tree:edit:select' => "Select which folder(s) to display",
		'widgets:bookmark_tree:edit:show_content' => "Show the content of the folder(s)",
		'widgets:bookmark_tree:no_folders' => "No folders configured",
		'widgets:bookmark_tree:no_bookmarks' => "No bookmarks configured",
		'widgets:bookmark_tree:more' => "More bookmark folders",
	
		'widget:bookmark:edit:show_only_featured' => 'Show only featured bookmarks',
		
		'widget:bookmark_tools:show_bookmark' => 'Feature bookmark (widget)',
		'widget:bookmark_tools:hide_bookmark' => 'Unfeature bookmark',
	
		'widgets:bookmark_tools:more_bookmarks' => 'More bookmarks',
		
		// Group bookmarks
		'widgets:group_bookmarks:description' => "Show the latest group bookmarks",
		
		// index_bookmark
		'widgets:index_bookmark:description' => "Show the latest bookmarks on your community",
	
	);
	
	add_translation("en", $english);
	