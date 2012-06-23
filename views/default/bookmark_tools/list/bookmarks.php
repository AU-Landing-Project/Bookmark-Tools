<?php

	$bookmarks = elgg_extract("bookmarks", $vars, array());
	$folder = elgg_extract("bmfolder", $vars);
	
	$folder_content = elgg_view("bookmark_tools/breadcrumb", array("entity" => $folder));	
	
	if(!($sub_folders = bookmark_tools_get_sub_folders($folder))){
		$sub_folders = array();
	}
	
	$entities = array_merge($sub_folders, $bookmarks) ;
	
	if(!empty($entities)) {
		$params = array(
			"full_view" => false,
			"pagination" => false
		);
		
		elgg_push_context("bookmark_tools_selector");
		
		$bookmarks_content = elgg_view_entity_list($entities, $params);
		
		elgg_pop_context();
	}
	
	if(empty($bookmarks_content)){
		$bookmarks_content = elgg_echo("bookmark_tools:list:bookmarks:none");
	} else {
		$bookmarks_content .= "<div class='clearfix'>";
		
		if(elgg_get_page_owner_entity()->canEdit()) {
			$bookmarks_content .= '<a id="bookmark_tools_action_bulk_delete" href="javascript:void(0);">' . elgg_echo("bookmark_tools:list:delete_selected") . '</a>';
		}
		
		$bookmarks_content .= "<a id='bookmark_tools_select_all' class='float-alt' href='javascript:void(0);'>";
		$bookmarks_content .= "<span>" . elgg_echo("bookmark_tools:list:select_all") . "</span>";
		$bookmarks_content .= "<span class='hidden'>" . elgg_echo("bookmark_tools:list:deselect_all") . "</span>";
		$bookmarks_content .= "</a>";
		
		$bookmarks_content .= "</div>";
	}
	
	$bookmarks_content .= elgg_view("graphics/ajax_loader");
	
?>
<div id="bookmark_tools_list_bookmarks">
	<div id="bookmark_tools_list_bookmarks_overlay"></div>
	<?php 
		echo $folder_content;
		echo $bookmarks_content;
	?>
</div>

<?php 
$page_owner = elgg_get_page_owner_entity();

if($page_owner->canEdit() || (elgg_instanceof($page_owner, "group") && $page_owner->isMember())) { ?>
<script type="text/javascript">

	$(function(){
		$("#bookmark_tools_list_bookmarks .bookmark-tools-bookmark").draggable({
			revert: "invalid",
			opacity: 0.8,
			appendTo: "body",
			helper: "clone",
			start: function(event, ui) {
				$(this).css("visibility", "hidden");
				$(ui.helper).width($(this).width());
			},
			stop: function(event, ui) {
				$(this).css("visibility", "visible");
			}
		});

		$("#bookmark_tools_list_bookmarks .bookmark-tools-folder").droppable({
			accept: "#bookmark_tools_list_bookmarks .bookmark-tools-bookmark",
			drop: function(event, ui){
				droppable = $(this);
				draggable = ui.draggable;

				drop_id = droppable.parent().attr("id").split("-").pop();
				drag_id = draggable.parent().attr("id").split("-").pop();

				elgg.bookmark_tools.move_bookmark(drag_id, drop_id, draggable);
			}
		});
	});

</script>
<?php 
} 