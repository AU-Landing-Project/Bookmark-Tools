<?php 

	$folders = elgg_extract("bmfolders", $vars);
	$folder = elgg_extract("bmfolder", $vars);
	
	$selected_id = "bookmark_tools_list_tree_main";
	if($folder instanceof ElggObject) {
		$selected_id = $folder->getGUID();
	}
	
	$page_owner = elgg_get_page_owner_entity();
	
	// load JS
	elgg_load_js("jquery.tree");
	elgg_load_css("jquery.tree");
	
	elgg_load_js("jquery.hashchange");
?>
<script type="text/javascript">
	function bookmark_tools_get_selected_tree_folder_id(){
		var result = 0;

		tree = jQuery.tree.reference($("#bookmark_tools_list_tree"));
		result = bookmark_tools_tree_folder_id(tree.selected);
		return result;
	}

	function bookmark_tools_remove_folder_bookmarks(link) {
		if(confirm("<?php echo elgg_echo("bookmark_tools:folder:delete:confirm_bookmarks");?>")) {
			var cur_href = $(link).attr("href"); 
			$(link).attr("href", cur_href + "&bookmarks=yes");
		}
		return true;
	}
	
	function bookmark_tools_tree_folder_id(node, parent) {
		if(parent == true) {
			var find = "a:first";
		} else {
			var find = "a";
		}
		
		var element_id = node.find(find).attr("id");
		return element_id.substring(24, element_id.length);
	}
	
	function bookmark_tools_select_node(folder_guid, tree) {
		tree = jQuery.tree.reference($("#bookmark_tools_list_tree"));
		
		tree.select_branch($("#bookmark_tools_tree_element_" + folder_guid));
		tree.open_branch($("#bookmark_tools_tree_element_" + folder_guid));
	}
	
	$(function() {
		<?php if(bookmark_tools_use_folder_structure()){?>
		if(window.location.hash.substring(1) == '') {
			elgg.bookmark_tools.load_folder(0);
		}

		$(window).hashchange(function(){
			elgg.bookmark_tools.load_folder(window.location.hash.substring(1));
		});
		
		$("a[href*='bookmark_tools/bookmark/new']").live("click",function(e) {
			var link = $(this).attr('href');
		
			window.location = link + '?bmfolder_guid=' + bookmark_tools_get_selected_tree_folder_id();
			e.preventDefault();
	        
		});
		<?php }?>
	
		$('.bookmark_tools_load_folder').live('click', function() {
			folder_guid = $(this).attr('rel');
			bookmark_tools_select_node(folder_guid);
		});
	
		$('select[name="bookmark_sort"], select[name="bookmark_sort_direction"]').change(function() {
			bookmark_tools_show_loader($("#bookmark_tools_list_folder"));
			var folder_url = "<?php echo $vars["url"];?>bookmark_tools/list/<?php echo elgg_get_page_owner_guid();?>?bmfolder_guid=" + bookmark_tools_get_selected_tree_folder_id() + "&search_viewtype=<?php echo get_input("search_viewtype", "list"); ?>&sort_by=" + $('select[name="bookmark_sort"]').val() + "&direction=" + $('select[name="bookmark_sort_direction"]').val();
			$("#bookmark_tools_list_bookmarks_container").load(folder_url);
		});
	
		
	});

	function bookmark_tools_show_loader(elem){
		var overlay_width = elem.outerWidth();
		var margin_left = elem.css("margin-left");
			
		$("#bookmark_tools_list_bookmarks_overlay").css("width", overlay_width).css("left", margin_left).show();
	}
	
	$(function () {
		<?php if($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember())){ ?>
		$("#bookmark_tools_list_tree a").droppable({
			"accept": ".bookmark_tools_bookmark",
			"hoverClass": "ui-state-hover",
			"tolerance": "pointer",
			"drop": function(event, ui) {
	
				var bookmark_move_url = "<?php echo $vars["url"];?>bookmark_tools/proc/bookmark/move";
				var bookmark_guid = $(ui.draggable).prev("input").val();
				if(bookmark_guid == undefined)
				{
					bookmark_guid = $(ui.draggable).attr('id').replace('bookmark_','');
				}
				var folder_guid = $(this).attr("id");
				var selected_folder_guid = bookmark_tools_get_selected_tree_folder_id();

				bookmark_tools_show_loader($(ui.draggable));
				
				$(ui.draggable).hide();
				
				$.post(bookmark_move_url, {"bookmark_guid": bookmark_guid, "bmfolder_guid": folder_guid}, function(data)
				{
					elgg.bookmark_tools.load_folder(selected_folder_guid);
				});
			},
			"greedy": true
		});
		<?php } ?>
	});

</script>

<?php 

	$body = "<div id='bookmark-tools-folder-tree' class='clearfix hidden'>";
	$body .= elgg_view_menu("bookmark_tools_folder_sidebar_tree", array(
		"container" => $page_owner,
		"sort_by" => "priority"
	));
	$body .= "</div>";
	
	if($page_owner->canEdit() || ($page_owner instanceof ElggGroup && $page_owner->isMember() && $page_owner->bookmark_tools_structure_management_enable != "no")) { 
		elgg_load_js("lightbox");
		elgg_load_css("lightbox");
		
		$body .= "<div class='mtm'>";
		$body .= elgg_view("input/button", array("value" => elgg_echo("bookmark_tools:new:title"), "id" => "bookmark_tools_list_new_folder_toggle", "class" => "elgg-button-action"));
		$body .= "</div>";
	}
	
	// output bookmark tree
	echo elgg_view_module("aside", "", $body, array("id" => "bookmark_tools_list_tree_container"));
