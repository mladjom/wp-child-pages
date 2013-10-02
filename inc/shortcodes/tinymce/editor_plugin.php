<?php 
header("Content-Type:text/javascript");

// Setup URL to WordPress
$absolute_path = __FILE__;
$path_to_wp = explode( 'wp-content', $absolute_path );
$wp_url = $path_to_wp[0];

// Access WordPress
require_once( $wp_url.'/wp-load.php' );

// URL to curent file folder
$dir = plugins_url( '/' , __FILE__ );
?>
(function () {
	tinymce.create("tinymce.plugins.TinymceShortcodes", {
		init: function (d, e) {

			d.addCommand("scnOpenDialog", function (a, c) {
				scnSelectedShortcodeType = c.identifier;
				jQuery.get(e + "/dialog.php", function (b) {
					jQuery("#scn-dialog").remove();
					jQuery("body").append(b);
					jQuery("#scn-dialog").hide();
					var f = jQuery(window).width();
					b = jQuery(window).height();
					f = 720 < f ? 720 : f;
					f -= 80;
					b -= 84;
					tb_show("Insert Shortcode | WP Projects", "#TB_inline?width=" + f + "&height=" + b + "&inlineId=scn-dialog");
					jQuery("#scn-options h3:first").text("Customize the " + c.title + " Shortcode")
				})
			});
			d.onNodeChange.add(function (a, c) {
				c.setDisabled("scn_button", a.selection.getContent().length > 0)
			})
		},
		createControl: function (d, e) {
			if (d == "scn_button") {
				d = e.createMenuButton("scn_button", {
					title: "Insert WP Projects Shortcodes",
					image: "<?php echo $dir; ?>img/icon.png",
					icons: false
				});
				var a = this;
				d.onRenderMenu.add(function (c, b) {
					a.addWithDialog(b, "Subpages", "subpages");
				});
				return d
			}
			return null
		},
		addImmediate: function (d, e, a) {
			d.add({
				title: e,
				onclick: function () {
					tinyMCE.activeEditor.execCommand("mceInsertContent", false, a)
				}
			})
		},
		addWithDialog: function (d, e, a) {
			d.add({
				title: e,
				onclick: function () {
					tinyMCE.activeEditor.execCommand("scnOpenDialog", false, {
						title: e,
						identifier: a
					})
				}
			})
		},
		getInfo: function () {
			return {
				longname: "Shortcode Generator",
				author: "Mladjo",
				authorurl: "http://www.divinedeveloper.com/",
				infourl: "http://www.divinedeveloper.com/",
				version: "1.0"
			}
		}
	});
	tinymce.PluginManager.add("TinymceShortcodes", tinymce.plugins.TinymceShortcodes)
})();