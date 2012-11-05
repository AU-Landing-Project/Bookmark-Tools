<?php

?>
//<script>
elgg.provide("elgg.bookmark_tools");
elgg.provide("elgg.bookmark_tools.tree");

// extend jQuery with a function to serialize to JSON
(function( $ ){
	$.fn.serializeJSON = function() {
		var json = {};
		jQuery.map($(this).serializeArray(), function(n, i){
			if (json[n['name']]){
				if (!json[n['name']].push) {
					json[n['name']] = [json[n['name']]];
				}
				json[n['name']].push(n['value'] || '');
			} else {
				json[n['name']] = n['value'] || '';
			}
		});
		return json;
	};
})( jQuery );


elgg.bookmark_tools.tree.init = function(){
	$tree = $('#bookmark-tools-folder-tree');

	if($tree.length){
		$tree.tree({
			rules: {
				multiple: false,
				drag_copy: false,
				valid_children : [ "root" ]
			},
			ui: {
				theme_name: "classic"
			},
			callback: {
				onload: function(tree){
					var hash = window.location.hash;

					if(hash){
						tree.select_branch($tree.find('a[href="' + hash + '"]'));
						tree.open_branch($tree.find('a[href="' + hash + '"]'));

						var folder_guid = hash.substr(1);
					} else {
						tree.select_branch($tree.find('a[href="#"]'));
						tree.open_branch($tree.find('a[href="#"]'));

						var folder_guid = 0;
					}

					elgg.bookmark_tools.load_folder(folder_guid);
					
					$tree.show();
				},
				onselect: function(node, tree){
					var hash = $(node).find('a:first').attr("href").substr(1);

					window.location.hash = hash;
				},
				onmove: function(node, ref_node, type, tree_obj, rb){
					var parent_node = tree_obj.parent(node);

					var folder_guid = $(node).find('a:first').attr('href').substr(1);
					var parent_guid = $(parent_node).find('a:first').attr('href').substr(1);
										
					var order = [];
					$(parent_node).find('>ul > li > a').each(function(k, v){
						var guid = $(v).attr('href').substr(1);
						order.push(guid);
					});

					if(parent_guid == window.location.hash.substr(1)){
						$("#bookmark_tools_list_bookmarks_container .elgg-ajax-loader").show();
					}
					
					elgg.action("bookmark_tools/folder/reorder", {
						data: {
							bmfolder_guid: folder_guid,
							parent_guid: parent_guid,
							order: order
						},
						success: function(){
							if(parent_guid == window.location.hash.substr(1)){
								elgg.bookmark_tools.load_folder(parent_guid);
							}
						}
					});
				}
			}
		}).find("a").droppable({
			accept: "#bookmark_tools_list_bookmarks .bookmark-tools-bookmark",
			hoverClass: "bookmark-tools-tree-droppable-hover",
			tolerance: "pointer",
			drop: function(event, ui){
				droppable = $(this);
				draggable = ui.draggable;

				drop_id = droppable.attr("href").substring(1);
				drag_id = draggable.parent().attr("id").split("-").pop();

				elgg.bookmark_tools.move_bookmark(drag_id, drop_id, draggable);
			}
		});
	}
}

elgg.bookmark_tools.breadcrumb_click = function(event) {
	var href = $(this).attr("href");
	var hash = elgg.parse_url(href, "fragment");

	if(hash){
		window.location.hash = hash;
	} else {
		window.location.hash = "#";
	}

	event.preventDefault();
}

elgg.bookmark_tools.load_folder = function(folder_guid){
	var query_parts = elgg.parse_url(window.location.href, "query", true);
	var search_type = 'list';
	
	if(query_parts && query_parts.search_viewtype){
		search_type = query_parts.search_viewtype;
	}
	
	var url = elgg.get_site_url() + "bookmark_tools/list/" + elgg.get_page_owner_guid() + "?bmfolder_guid=" + folder_guid + "&search_viewtype=" + search_type;

	$("#bookmark_tools_list_bookmarks_container .elgg-ajax-loader").show();
	$("#bookmark_tools_list_bookmarks_container").load(url, function(){
		var add_link = $('ul.elgg-menu-title li.elgg-menu-item-add a').attr("href");

    if(add_link){
      var path = elgg.parse_url(add_link, "path");
      var new_add_link = elgg.get_site_url() + 'bookmarks/add/' + elgg.get_page_owner_guid() + "?bmfolder_guid=" + folder_guid;

      $('ul.elgg-menu-title li.elgg-menu-item-add a').attr("href", new_add_link);
    }
    
    //update bookmark link and widget_manager multidashboard link and rss link
    var new_bookmark_link = elgg.get_site_url() + 'bookmarks/add/' + elgg.get_page_owner_guid() + '?address=' + encodeURIComponent(window.location.href);
    var new_multidashboard_link = elgg.get_site_url() + 'multi_dashboard/edit/?internal_url=' + encodeURIComponent(window.location.href);
	var new_rss_link = elgg.get_site_url() + 'bookmarks/owner/' + elgg.page_owner.username + '?view=rss&bmfolder_guid=' + folder_guid;
    $(".elgg-menu-item-bookmark a").attr("href", new_bookmark_link);
    $("#widget-manager-multi_dashboard-extras").attr("href", new_multidashboard_link);
	$(".elgg-menu-item-rss a").attr("href", new_rss_link);
	});
}

elgg.bookmark_tools.move_bookmark = function(bookmark_guid, to_folder_guid, draggable){
	elgg.action("bookmark/move", {
		data: {
			bookmark_guid: bookmark_guid, 
			bmfolder_guid: to_folder_guid
		},
		error: function(result){
			var hash = elgg.parse_url(window.location.href, "fragment");

			if(hash){
				elgg.bookmark_tools.load_folder(hash);
			} else {
				elgg.bookmark_tools.load_folder(0);
			}
		},
		success: function(result){
			draggable.parent().remove();
		}
	});
}

elgg.bookmark_tools.select_all = function(e){
	e.preventDefault();

	if($(this).find("span:first").is(":visible")){
		// select all
		$('#bookmark_tools_list_bookmarks input[type="checkbox"]').attr("checked", "checked");
	} else {
		// deselect all
		$('#bookmark_tools_list_bookmarks input[type="checkbox"]').removeAttr("checked");
	}

	$(this).find("span").toggle();
}

elgg.bookmark_tools.bulk_delete = function(e){
	e.preventDefault();

	$checkboxes = $('#bookmark_tools_list_bookmarks input[type="checkbox"]:checked');

	if($checkboxes.length){
		if(confirm(elgg.echo("deleteconfirm"))) {
			var postData = $checkboxes.serializeJSON();

			if($('#bookmark_tools_list_bookmarks input[type="checkbox"][name="bmfolder_guids[]"]:checked').length && confirm(elgg.echo("bookmark_tools:folder:delete:confirm_bookmarks"))){
				postData.bookmarks = "yes";
			}

			$("#bookmark_tools_list_bookmarks_container .elgg-ajax-loader").show();
			
			elgg.action("bookmark/bulk_delete", {
				data: postData,
				success: function(res){
					$.each($checkboxes, function(key, value){
						$('#elgg-object-' + $(value).val()).remove();
					});

					$("#bookmark_tools_list_bookmarks_container .elgg-ajax-loader").hide();
				}
			});
		}
	}
}

elgg.bookmark_tools.new_folder = function(event){
	event.preventDefault();

	var hash = window.location.hash.substr(1);
	var link = elgg.get_site_url() + "bookmark_tools/bmfolder/new/" + elgg.get_page_owner_guid() + "?bmfolder_guid=" + hash;
	window.location = link;
  /*
	$.fancybox({
		href: link,
		titleShow: false
	});
  */
}

elgg.bookmark_tools.init = function(){
	// tree functions
	elgg.bookmark_tools.tree.init();
	
	$('#bookmark_tools_breadcrumbs a').live("click", elgg.bookmark_tools.breadcrumb_click);
	$('#bookmark_tools_select_all').live("click", elgg.bookmark_tools.select_all);
	$('#bookmark_tools_action_bulk_delete').live("click", elgg.bookmark_tools.bulk_delete);

	$('#bookmark_tools_list_new_folder_toggle').live('click', elgg.bookmark_tools.new_folder);
}

// register init hook
elgg.register_hook_handler("init", "system", elgg.bookmark_tools.init);