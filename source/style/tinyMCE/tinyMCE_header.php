<!-- TinyMCE -->
<script type="text/javascript" src="style/tinyMCE/jscripts/tiny_mce/tiny_mce.js"></script>
<!-- <script type="text/javascript" src="style/tinyMCE/jscripts/tiny_mce/tiny_mce_gzip.js"></script> -->
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		// General options
		mode : "textareas",
		elements : "elm1",
		theme : "advanced",
		skin : "o2k7",
		language: "ru",
		plugins : "safari,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",
		extended_valid_elements : "hr[class|width|size|noshade],a[name|href|target|title|onclick|rel]",

		// Theme options
		theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,styleselect,formatselect,fontselect,fontsizeselect",
		theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
		theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,fullscreen",
		theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,
		fullscreen_new_window : true,
		fullscreen_settings : {
			theme_advanced_path_location : "top"
		},
		plugin_preview_width : "600",
		plugin_preview_height : "300",
		content_css : "style/tinyMCE/style.css",
		plugin_insertdate_dateFormat : "%d-%m-%Y",
		plugin_insertdate_timeFormat : "%H:%i:%s",
		paste_use_dialog : false,
		theme_advanced_resize_horizontal : false,
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false,
		force_br_newlines: false,
		force_p_newlines: false,
		convert_newlines_to_brs:false,
		remove_linebreaks: false,        	
	});
</script>
<!-- /TinyMCE -->
<style>
textarea { 
  background: black url("http://<?=IMG_DOMAIN;?>/nav/story-content-bg2.gif");
  background-repeat: repeat;
  color: white;
}
</style>
