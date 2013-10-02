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

var scnDialogHelper = {
	needsPreview: false,
	setUpButtons: function () {
		var a = this;
		jQuery("#scn-btn-cancel").click(function () {
			a.closeDialog()
		});
		jQuery("#scn-btn-insert").click(function () {
			a.insertAction()
		});
		jQuery("#scn-btn-preview").click(function () {
			a.previewAction()
		})
	},
	loadShortcodeDetails: function () {
		if (scnSelectedShortcodeType) {
			var a = this;
			jQuery.getScript("<?php echo $dir; ?>sc/" + scnSelectedShortcodeType + ".js", function () {

				a.initializeDialog()
			})
		}
	},
	initializeDialog: function () {
		if (typeof scnShortcodeMeta == "undefined") jQuery("#scn-options").append("<p>Error loading details for shortcode: " + scnSelectedShortcodeType + "</p>");
		else {
			if (scnShortcodeMeta.disablePreview) {
				jQuery("#scn-preview").remove();
				jQuery("#scn-btn-preview").remove()
			}
			var a = scnShortcodeMeta.attributes,
			b = jQuery("#scn-options-table");
			for (var c in a) {
				var f = "scn-value-" + a[c].id,
				d = a[c].isRequired ? "scn-required": "",
				g = jQuery('<th valign="top" scope="row"></th>');
				jQuery("<label/>").attr("for", f).attr("class", d).html(a[c].label).appendTo(g);
				f = jQuery("<td/>");
				d = (d = a[c].controlType) ? d: "text-control";
				switch (d) {
				case "column-control":
					this.createColumnControl(a[c], f, c == 0);
					break;
				case "icon-control":
				case "color-control":
				case "link-control":
				case "text-control":
					this.createTextControl(a[c], f, c == 0);
					break

				case "textarea-control":

					this.createTextAreaControl(a[c], f, c == 0);

				break;
							
				case "select-control":

					this.createSelectControl(a[c], f, c == 0);

				break;


				}
				jQuery("<tr/>").append(g).append(f).appendTo(b)
			}
			jQuery(".scn-focus-here:first").focus()
		}
	},
	createColumnControl: function (a, b, c) {
		new ScnColumnMaker(b, 6, c ? "scn-focus-here": null);
		b.addClass("scn-marker-column-control")
	},
	createTextControl: function (a, b, c) {
		var f = a.validateLink ? "scn-validation-marker": "",
		d = a.isRequired ? "scn-required": "",
		g = "scn-value-" + a.id;
		jQuery('<input type="text">').attr("id", g).attr("name", g).addClass(f).addClass(d).addClass(c ? "scn-focus-here": "").appendTo(b);
		if (a = a.help) {
			jQuery("<br/>").appendTo(b);
			jQuery("<span/>").addClass("scn-input-help").html(a).appendTo(b)
		}
		var h = this;
		b.find("#" + g).bind("keydown focusout", function (e) {
			if (e.type == "keydown" && e.which != 13 && e.which != 9 && !e.shiftKey) h.needsPreview = true;
			else if (h.needsPreview && (e.type == "focusout" || e.which == 13)) {
				h.previewAction(e.target);
				h.needsPreview = false
			}
		})
	},

    /* Generic TextArea Element */

    createTextAreaControl: function (a, b, c) {

        var f = a.validateLink ? "scn-validation-marker" : "",
            d = a.isRequired ? "scn-required" : "",
            g = "scn-value-" + a.id;

        jQuery( '<textarea>').attr( "id", g).attr( "name", g).attr( "rows", 10).attr( "cols", 30).addClass(f).addClass(d).addClass( 'txt input-textarea').addClass(c ? "scn-focus-here" : "").appendTo(b);
        b.addClass( "scn-marker-textarea-control" );

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "scn-input-help").html(a).appendTo(b)
        }

        var h = this;
        b.find( "#" + g).bind( "keydown focusout", function (e) {
        })

    },

    /* Select Box Element */

    createSelectControl: function (a, b, c) {

        var f = a.validateLink ? "scn-validation-marker" : "",
            d = a.isRequired ? "scn-required" : "",
            g = "scn-value-" + a.id;

        var selectNode = jQuery( '<select>').attr( "id", g).attr( "name", g).addClass(f).addClass(d).addClass( 'select input-select').addClass(c ? "scn-focus-here" : "" );

        b.addClass( 'scn-marker-select-control' );

        var selectBoxValues = a.selectValues;
        
        var labelValues = a.selectValues;

        for (v in selectBoxValues) {

            var value = selectBoxValues[v];
            var label = labelValues[v];
            var selected = '';

            if (value == '') {

                if (a.defaultValue == value) {

                    label = a.defaultText;

                } // End IF Statement
            } else {

                if (value == a.defaultValue) {
                    label = a.defaultText;
                } // End IF Statement
            } // End IF Statement
            if (value == a.defaultValue) {
                selected = ' selected="selected"';
            } // End IF Statement
            
            selectNode.append( '<option value="' + value + '"' + selected + '>' + label + '</option>' );

        } // End FOREACH Loop
        
        selectNode.appendTo(b);

        if (a = a.help) {
            jQuery( "<br/>").appendTo(b);
            jQuery( "<span/>").addClass( "scn-input-help").html(a).appendTo(b)
        }

        var h = this;

        b.find( "#" + g).bind( "change", function (e) {
            // Update the text in the appropriate span tag.
            var newText = jQuery(this).children( 'option:selected').text();
            
            jQuery(this).parents( '.select_wrapper').find( 'span').text( newText );
        })

    },




	getTextKeyValue: function (a) {
		var b = a.find("input");
		if (!b.length) return null;
		a = b.attr("id").substring(10);
		b = b.val();
		return {
			key: a,
			value: b
		}
	},

	getTextAreaKeyValue: function (a) {
        var b = a.find( "textarea" );
        if (!b.length) return null;
        a = b.attr( "id").substring(10);
        b = b.val();
		b = b.replace(/\n\r?/g, '<br />');
        return {
            key: a,
            value: b
        }
    },

   getSelectKeyValue: function (a) {
        var b = a.find( "select" );
        if (!b.length) return null;
        a = b.attr( "id").substring(10);
        b = b.val();
        return {
            key: a,
            value: b
        }
    },

	getColumnKeyValue: function (a) {
		var b = a.find("#scn-column-text").text();
		if (a = Number(a.find("select option:selected").val())) return {
			key: "data",
			value: {
				content: b,
				numColumns: a
			}
		}
	},
	makeShortcode: function () {
		var a = {},
		b = this;
		jQuery("#scn-options-table td").each(function () {
			var h = jQuery(this),
			e = null;
			if (e = h.hasClass("scn-marker-column-control") ? b.getColumnKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
    	if (e = h.hasClass( "scn-marker-select-control") ? b.getSelectKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
  		if (e = h.hasClass( "scn-marker-textarea-control") ? b.getTextAreaKeyValue(h) : b.getTextKeyValue(h)) a[e.key] = e.value
		});
		if (scnShortcodeMeta.customMakeShortcode) return scnShortcodeMeta.customMakeShortcode(a);
		var c = a.content ? a.content: scnShortcodeMeta.defaultContent,
		f = "";
		for (var d in a) {
			var g = a[d];
			if (g && d != "content") f += " " + d + '="' + g + '"'
		}
		return "[" + scnShortcodeMeta.shortcode + f + "]" + (c ? c + "[/" + scnShortcodeMeta.shortcode + "] ": " ")
	},



	insertAction: function () {
		if (typeof scnShortcodeMeta != "undefined") {
			var a = this.makeShortcode();
			tinyMCE.activeEditor.execCommand("mceInsertContent", false, a);
			this.closeDialog()
		}
	},
	closeDialog: function () {
		this.needsPreview = false;
		tb_remove();
		jQuery("#scn-dialog").remove()
	},
	previewAction: function (a) {
		jQuery(a).hasClass("scn-validation-marker") && this.validateLinkFor(a);
		jQuery("#scn-preview h3:first").addClass("scn-loading");
		jQuery("#scn-preview-iframe").attr("src", "<?php echo $dir; ?>/preview.php?shortcode=" + encodeURIComponent(this.makeShortcode()))
	},
	validateLinkFor: function (a) {
		var b = jQuery(a);
		b.removeClass("scn-validation-error");
		b.removeClass("scn-validated");
		if (a = b.val()) {
			b.addClass("scn-validating");
			jQuery.ajax({
				url: ajaxurl,
				dataType: "json",
				data: {
					action: "scn_check_url_action",
					url: a
				},
				error: function () {
					b.removeClass("scn-validating")
				},
				success: function (c) {
					b.removeClass("scn-validating");
					c.error || b.addClass(c.exists ? "scn-validated": "scn-validation-error")
				}
			})
		}
	}
};
scnDialogHelper.setUpButtons();
scnDialogHelper.loadShortcodeDetails();