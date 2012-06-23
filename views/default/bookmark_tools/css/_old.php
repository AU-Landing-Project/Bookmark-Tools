<?php

$graphics_folder = $vars['url'] . 'mod/bookmark_tools/_graphics/';

?>

#bookmark_tools_edit_form_access_extra label {
	font-size: 100%;
    font-weight: normal;
}

#bookmark_tools_list_folder p
{
	margin: 0px;
}

#bookmark_tools_list_folder_actions,
.bookmark_tools_folder_actions,
.bookmark_tools_bookmark_actions {
	float: right;
} 

.bookmark_tools_folder_actions
{
	margin-right: 10px;
}

.bookmark_tools_folder_title,
.bookmark_tools_folder_etc,
.bookmark_tools_bookmark_title,
.bookmark_tools_bookmark_etc
{
	float: left;
	width: 200px;
}

.bookmark_tools_bookmark_etc
{
	width: 225px;
}

.bookmark_tools_bookmark_etc span
{
	float: right;
	width: 60px;
}

.bookmark_tools_bookmark_icon,
.bookmark_tools_folder_icon
{
	float: left;
	width: 24px;
	height: 24px;
	margin-right: 10px;
}

#bookmark_tools_list_tree_container {	
	overflow: auto;
}

#bookmark_tools_list_tree_info {
	color: grey;
}

#bookmark_tools_list_tree_info > div {
	background: url(<?php echo $vars["url"]; ?>_graphics/icon_customise_info.gif) top left no-repeat;
	padding-left: 16px; 
	color: #333333;
	font-weight: bold;
}

/* loading overlay */

#bookmark_tools_list_bookmarks {
	position: relative;
	
}

#bookmark_tools_list_bookmarks_overlay {
	display: none;
	background: white;
	height: 100%;
	position: absolute;
	opacity: 0.6;
	filter: alpha(opacity=60);
	z-index: 100;
	background: url("<?php echo $vars["url"]; ?>_graphics/ajax_loader.gif") no-repeat scroll center center white;
	padding: auto;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}

/* breadcrumb */
#bookmark_tools_breadcrumbs_bookmark_title {
	float: right;
}

#bookmark_tools_breadcrumbs ul{
	border: 1px solid #DEDEDE;
    height: 2.3em;
}

#bookmark_tools_breadcrumbs ul,
#bookmark_tools_breadcrumbs li {
	list-style-type:none;
	padding:0;
	margin:0
}

#bookmark_tools_breadcrumbs li {
	float:left;
	line-height:2.3em;
	padding-left:.75em;
	color:#777;
	min-width: 50px;
}
#bookmark_tools_breadcrumbs li a {
	display:block;
	padding:0 15px 0 0;
	background:url(<?php echo $vars["url"]; ?>mod/bookmark_tools/_graphics/crumbs.gif) no-repeat right center;
}

#bookmark_tools_breadcrumbs li a:link, 
#bookmark_tools_breadcrumbs li a:visited {
	text-decoration:none;
   	color:#777;
}

#bookmark_tools_breadcrumbs li a:hover,
#bookmark_tools_breadcrumbs li a:focus {
	color:#333;
}


/* extending bookmark tree classic theme */

#bookmark_tools_list_tree.tree li {
	line-height: 20px;
}
 
#bookmark_tools_list_tree.tree li span {
	padding: 1px 0px;
}

#bookmark_tools_list_tree.tree-classic li a {
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
	border: 1px solid transparent;	
}

#bookmark_tools_list_tree.tree-classic li a:hover {
	border: 1px solid #CCCCCC;
}

#bookmark_tools_list_tree.tree-classic li a.clicked {
	background: #DEDEDE;
    border: 1px solid #CCCCCC;
    color: #999999;
}

#bookmark_tools_list_tree.tree-classic li a.clicked:hover {
	background: #CCCCCC;
    border: 1px solid #CCCCCC;
    color: white;
}

#bookmark_tools_list_tree.tree-classic li a.ui-state-hover{
	background: #0054A7;
	border: 1px solid #0054A7;	
	color: white;
}

/* **************************
bookmark tree widget
**************************** */
.bookmark_tools_widget_edit_folder_wrapper ul {
	list-style: none outside none;
	margin: 0;
	padding: 0;
}

.bookmark_tools_widget_edit_folder_wrapper ul ul {
	padding-left: 10px;
}

.bookmark_tools_widget_edit_folder_wrapper li {

}

.bookmark_tools_folder, .bookmark_tools_bookmark
{
	position: relative;
	height: 25px;
	line-height: 25px;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	background-color: #ffffff;
	margin: 0 10px 5px;
	padding-left: 5px;
    /*padding-right: 10px;*/
    padding-top: 2px;
}

.bookmark_tools_folder img, .bookmark_tools_bookmark img
{
	margin-right: 10px;
	width: 24px;
	height: 24px;
}

#bookmark_tools_list_bookmarks_sort_options span
{
	color: #333333;
    font-weight: bold;
}

#bookmark_tools_folder_preview
{
	margin-top: 20px;
}

.progressWrapper {
	width: 357px;
	overflow: hidden;
}

.progressContainer {
	margin: 5px;
	padding: 4px;
	border: solid 1px #E8E8E8;
	background-color: #F7F7F7;
	overflow: hidden;
}
/* Message */
.message {
	margin: 1em 0;
	padding: 10px 20px;
	border: solid 1px #FFDD99;
	background-color: #FFFFCC;
	overflow: hidden;
}
/* Error */
.red {
	border: solid 1px #B50000;
	background-color: #FFEBEB;
}

/* Current */
.green {
	border: solid 1px #DDF0DD;
	background-color: #EBFFEB;
}

/* Complete */
.blue {
	border: solid 1px #CEE2F2;
	background-color: #F0F5FF;
}

.progressName {
	font-size: 8pt;
	font-weight: 700;
	color: #555;
	width: 323px;
	height: 14px;
	text-align: left;
	white-space: nowrap;
	overflow: hidden;
}

.progressBarInProgress,
.progressBarComplete,
.progressBarError {
	font-size: 0;
	width: 0%;
	height: 2px;
	background-color: blue;
	margin-top: 2px;
}

.progressBarComplete {
	width: 100%;
	background-color: green;
	visibility: hidden;
}

.progressBarError {
	width: 100%;
	background-color: red;
	visibility: hidden;
}

.progressBarStatus {
	margin-top: 2px;
	width: 337px;
	font-size: 7pt;
	font-family: Arial;
	text-align: left;
	white-space: nowrap;
}

a.progressCancel {
	font-size: 0;
	display: block;
	height: 14px;
	width: 14px;
	background-image: url(<?php echo $vars["url"]; ?>mod/bookmark_tools/_graphics/swfupload/cancelbutton.gif);
	background-repeat: no-repeat;
	background-position: -14px 0px;
	float: right;
}

a.progressCancel:hover {
	background-position: 0px 0px;
}

#bookmark_tools_list_new_bookmark,
#bookmark_tools_list_new_folder,
#bookmark_tools_list_new_zip
{
	display: none;
}

.bookmark_tools_bookmark_actions, .bookmark_tools_folder_actions
{
	position: relative;
}

input[name="bookmark_tools_bookmark_action_check"]
{
	margin: 5px 10px 0 15px;	
}

.bookmark_tools_bookmark_actions span,
.bookmark_tools_folder_actions span
{
    cursor: pointer;
    display: block;
    position: absolute;
    right: 22px;
    width: 50px;
    z-index: 9;
	background: url(<?php echo $graphics_folder; ?>arrows_down.png) right center no-repeat;
	padding: 0px 17px 0px 5px;
}

.bookmark_tools_bookmark_actions ul,
.bookmark_tools_folder_actions ul
{
    display: none;
    background-color: #ffffff;
    border: 1px #CCCCCC solid;
    padding: 0px 15px 0px 10px;
	min-width: 46px;
	cursor: default;
}

.bookmark_tools_bookmark_actions ul li a,
.bookmark_tools_folder_actions ul li a
{
	white-space: nowrap;
}

.bookmark_tools_bookmark_actions:hover span,
.bookmark_tools_folder_actions:hover span
{
	background-color: #FFFFFF;
    border: 1px solid #CCCCCC;
    border-bottom: 0px;
    line-height: 23px;
    width: 49px;
}

.bookmark_tools_bookmark_actions:hover ul,
.bookmark_tools_folder_actions:hover ul {
    display: block;
    position: absolute;
    right: 22px;
    top: 18px;
    z-index: 8;
    list-style: none;
}

.bookmark_tools_bookmark_actions:hover,
.bookmark_tools_folder_actions:hover
{
	z-index: 10;
	
	zoom: 1; /* IE hack */
}

#bookmark_tools_bookmark_upload_form .flash_wrapper {
	background: #4690D6;
	display: inline;
    margin: 10px;
    padding: 0px 6px;
    float: left;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}
#bookmark_tools_bookmark_upload_form .flash_wrapper:hover {
	background: #0054A7;
}	

/* fixes layout in widget */
.collapsable_box  #bookmarkrepo_widget_layout {
	margin: 0px;
}
.bookmarkrepo_widget_singleitem_more{
	margin: 0 10px;
}
.bookmark_tools_bookmark.ui-draggable {
	border: 1px solid #CCC;
}