<?php

?>
.bookmark-tools-icon-tiny {
	width: 20px;
	height: 20px;
}

.elgg-menu-bookmark-tools-folder-breadcrumb > li:after {
	padding: 0 4px;
	content: ">";
}

#bookmark_tools_list_bookmarks_container {
	position: relative;
}

#bookmark_tools_list_bookmarks_container .elgg-ajax-loader {
	background-color: white;
	opacity: 0.85;
	width: 100%;
	height: 100%;
	position: absolute;
	top: 0;
}

#bookmark_tools_list_bookmarks .ui-draggable,
.bookmark-tools-bookmark.ui-draggable {
	cursor: move;
	background: white;
}

#bookmark-tools-folder-tree .bookmark-tools-tree-droppable-hover {
	border: 1px solid red;
}
