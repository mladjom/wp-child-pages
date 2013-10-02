<?php 
//Setup URL to WordPres
$absolute_path = __FILE__;
$path_to_wp = explode( 'wp-content', $absolute_path );
$wp_url = $path_to_wp[0];

//Access WordPress
require_once( $wp_url.'/wp-load.php' ); 

// URL to TinyMCE plugin folder
$dir = plugins_url( '/' , __FILE__ )                                  
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div id="scn-dialog">
	<div id="scn-options">
		<h3>Customize the Shortcode</h3>
		<table id="scn-options-table">
		</table>
		<div style="float: left">
			<input type="button" id="scn-btn-cancel" class="button" name="cancel" value="Cancel" accesskey="C" />
		</div>
		<div style="float: right">
			<input type="button" id="scn-btn-preview" class="button" name="preview" value="Preview" accesskey="P" />
			<input type="button" id="scn-btn-insert" class="button-primary" name="insert" value="Insert" accesskey="I" />
		</div>
	</div>
	<div id="scn-preview" style="float:left;">
		<h3>Preview</h3>
		<iframe id="scn-preview-iframe" frameborder="0" style="width:100%;height:250px" ></iframe>
	</div>
	<script type="text/javascript"
	src="<?php echo $dir; ?>js/column-control.js"></script>
	<div style="float: right">
		<input type="button" id="scn-btn-cancel"
	class="button" name="cancel" value="Cancel" accesskey="C" />
	</div>
</div>
<script type="text/javascript"
	src="<?php echo $dir; ?>js/dialog-js.php"></script>
</div>
</body>
</html>
