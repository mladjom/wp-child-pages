<?php
/**
 * Creates the admin interface to add shortcodes to the editor
 *
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Class
 */
class CPS_Admin_Insert {

	/**
	 * __construct function
	 *
	 * @access public
	 * @return  void
	 */
	public function __construct() {
		add_action( 'media_buttons', array( $this, 'media_buttons' ), 20 );
		add_action( 'admin_footer', array( $this, 'cps_popup_html' ) );
	}

	/**
	 * media_buttons function
	 *
	 * @access public
	 * @return void
	 */
	public function media_buttons( $editor_id = 'content' ) {
		global $pagenow;

		// Only run on add/edit screens
		if ( in_array( $pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php') ) ) {
			$output = '<a href="#TB_inline?width=4000&amp;inlineId=cps-choose-shortcode" class="thickbox button cps-thicbox" title="' . __( 'Child Pages Shortcode Generator', 'childpages' ) . '">' . __( 'Child Pages Shortcodes', 'childpages' ) . '</a>';
		}
		echo $output;
	}

	/**
	 * Build out the input fields for shortcode content
	 * @param  string $key
	 * @param  array $param the parameters of the input
	 * @return void
	 */
	public function cps_build_fields($key, $param) {
		$html = '<tr>';
		$html .= '<td class="label">' . $param['label'] . ':</td>';
		switch( $param['type'] )
		{
			case 'text' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<input type="text" class="shortcode-form-text shortcode-input" name="' . $key . '" id="' . $key . '" value="' . $param['std'] . '" />' . "\n";
				$output .= '<span class="shortcode-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'textarea' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<textarea rows="10" cols="30" name="' . $key . '" id="' . $key . '" class="shortcode-form-textarea shortcode-input">' . $param['std'] . '</textarea>' . "\n";
				$output .= '<span class="shortcode-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'select' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<select name="' . $key . '" id="' . $key . '" class="shortcode-form-select shortcode-input">' . "\n";

				foreach( $param['options'] as $value => $option )
				{
					$output .= '<option value="' . $value . '">' . $option . '</option>' . "\n";
				}

				$output .= '</select>' . "\n";
				$output .= '<span class="shortcode-form-desc">' . $param['desc'] . '</span></td>' . "\n";

				// append
				$html .= $output;

				break;

			case 'checkbox' :

				// prepare
				$output = '<td><label class="screen-reader-text" for="' . $key .'">' . $param['label'] . '</label>';
				$output .= '<input type="checkbox" name="' . $key . '" id="' . $key . '" class="shortcode-form-checkbox shortcode-input"' . ( $param['default'] ? 'checked' : '' ) . '>' . "\n";
				$output .= '<span class="shortcode-form-desc">' . $param['desc'] . '</span></td>';

				$html .= $output;

				break;

			default :
				break;
		}
		$html .= '</tr>';

		return $html;
	}

	/**
	 * Popup window
	 *
	 * Print the footer code needed for the Insert Shortcode Popup
	 *
	 * @since 2.0
	 * @global $pagenow
	 * @return void Prints HTML
	 */
	function cps_popup_html() {
		global $pagenow;
		include(plugin_dir_path( __FILE__ ). 'config.php');

		// Only run in add/edit screens
		if ( in_array( $pagenow, array('post.php', 'page.php', 'post-new.php', 'post-edit.php') ) ) { ?>

			<script type="text/javascript">
				function cpsInsertShortcode() {
					// Grab input content, build the shortcodes, and insert them
					// into the content editor
					var select = jQuery('#select-cps-shortcode').val(),
						type = select.replace('cps-', '').replace('-shortcode', ''),
						template = jQuery('#' + select).data('shortcode-template'),
						childTemplate = jQuery('#' + select).data('shortcode-child-template'),
						tables = jQuery('#' + select).find('table').not('.cps-clone-template'),
						attributes = '',
						content = '',
						contentToEditor = '';

					// go over each table, build the shortcode content
					for (var i = 0; i < tables.length; i++) {
						var elems = jQuery(tables[i]).find('input, select, textarea');

						// Build an attributes string by mapping over the input
						// fields in a given table.
						attributes = jQuery.map(elems, function(el, index) {
							var $el = jQuery(el);

							console.log(el);

							if( $el.attr('id') === 'content' ) {
								content = $el.val();
								return '';
							} else if( $el.attr('type') === 'checkbox' ) {
								if( $el.is(':checked') ) {
									return $el.attr('id') + '="true"';
								} else {
									return '';
								}
							} else {
								return $el.attr('id') + '="' + $el.val() + '"';
							}
						});
						attributes = attributes.join(' ').trim();

						// Place the attributes and content within the provided
						// shortcode template
						if( childTemplate ) {
							// Run the replace on attributes for columns because the
							// attributes are really the shortcodes
							contentToEditor += childTemplate.replace('{{attributes}}', attributes).replace('{{attributes}}', attributes).replace('{{content}}', content);
						} else {
							// Run the replace on attributes for columns because the
							// attributes are really the shortcodes
							contentToEditor += template.replace('{{attributes}}', attributes).replace('{{attributes}}', attributes).replace('{{content}}', content);
						}
					};

					// Insert built content into the parent template
					if( childTemplate ) {
						contentToEditor = template.replace('{{child_shortcode}}', contentToEditor);
					}

					// Send the shortcode to the content editor and reset the fields
					window.send_to_editor( contentToEditor );
					cpsResetFields();
				}

				// Set the inputs to empty state
				function cpsResetFields() {
					jQuery('#cps-shortcode-title').text('');
					jQuery('#cps-shortcode-wrap').find('input[type=text], select').val('');
					jQuery('#cps-shortcode-wrap').find('textarea').text('');
					jQuery('.cps-was-cloned').remove();
					jQuery('.cps-shortcode-type').hide();
				}

				// Function to redraw the thickbox for new content
				function cpsResizeTB() {
					var	ajaxCont = jQuery('#TB_ajaxContent'),
						tbWindow = jQuery('#TB_window'),
						cpPopup = jQuery('#cps-shortcode-wrap');

					ajaxCont.css({
						height: (tbWindow.outerHeight()-47),
						overflow: 'auto', // IMPORTANT
						width: (tbWindow.outerWidth() - 30)
					});
				}

				// Simple function to clone an included template
				function cpsCloneContent(el) {
					var clone = jQuery(el).find('.cps-clone-template').clone().removeClass('hidden cps-clone-template').removeAttr('id').addClass('cps-was-cloned');

					jQuery(el).append(clone);
				}

				jQuery(document).ready(function($) {
					var $shortcodes = $('.cps-shortcode-type').hide(),
						$title = $('#cps-shortcode-title');

					// Show the selected shortcode input fields
	                $('#select-cps-shortcode').change(function () {
	                	var text = $(this).find('option:selected').text();

	                	$shortcodes.hide();
	                	$title.text(text);
	                    $('#' + $(this).val()).show();
	                    cpsResizeTB();
	                });

	                // Clone a set of input fields
	                $('.clone-content').on('click', function() {
						var el = $(this).siblings('.cps-sortable');

						cpsCloneContent(el);
						cpsResizeTB();
						$('.cps-sortable').sortable('refresh');
					});

	                // Remove a set of input fields
					$('.cps-shortcode-type').on('click', '.cps-remove' ,function() {
						$(this).closest('table').remove();
					});

					// Make content sortable using the jQuery UI Sortable method
					$('.cps-sortable').sortable({
						items: 'table:not(".hidden")',
						placeholder: 'cps-sortable-placeholder'
					});
	            });
			</script>

			<div id="cps-choose-shortcode" style="display: none;">
				<div id="cps-shortcode-wrap" class="wrap cps-shortcode-wrap">
					<div class="cps-shortcode-select">
						<label for="cps-shortcode"><?php _e('Select the shortcode type', 'childpages'); ?></label>
						<select name="cps-shortcode" id="select-cps-shortcode">
							<option><?php _e('Select Shortcode', 'childpages'); ?></option>
							<?php foreach( $childpages_shortcodes as $shortcode ) {
								echo '<option data-title="' . $shortcode['title'] . '" value="' . $shortcode['id'] . '">' . $shortcode['title'] . '</option>';
							} ?>
						</select>
					</div>

					<h3 id="cps-shortcode-title"></h3>

				<?php

				$html = '';
				$clone_button = array( 'show' => false );

				// Loop through each shortcode building content
				foreach( $childpages_shortcodes as $key => $shortcode ) {

					// Add shortcode templates to be used when building with JS
					$shortcode_template = ' data-shortcode-template="' . $shortcode['template'] . '"';
					if( array_key_exists('child_shortcode', $shortcode ) ) {
						$shortcode_template .= ' data-shortcode-child-template="' . $shortcode['child_shortcode']['template'] . '"';
					}

					// Individual shortcode 'block'
					$html .= '<div id="' . $shortcode['id'] . '" class="cps-shortcode-type" ' . $shortcode_template . '>';

					// If shortcode has children, it can be cloned and is sortable.
					// Add a hidden clone template, and set clone button to be displayed.
					if( array_key_exists('child_shortcode', $shortcode ) ) {
						$html .= (isset($shortcode['child_shortcode']['shortcode']) ? $shortcode['child_shortcode']['shortcode'] : null);
						$shortcode['params'] = $shortcode['child_shortcode']['params'];
						$clone_button['show'] = true;
						$clone_button['text'] = $shortcode['child_shortcode']['clone_button'];
						$html .= '<div class="cps-sortable">';
						$html .= '<table id="clone-' . $shortcode['id'] . '" class="hidden cps-clone-template"><tbody>';
						foreach( $shortcode['params'] as $key => $param ) {
							$html .= $this->cps_build_fields($key, $param);
						}
						if( $clone_button['show'] ) {
							$html .= '<tr><td colspan="2"><a href="#" class="cps-remove">' . __('Remove', 'childpages') . '</a></td></tr>';
						}
						$html .= '</tbody></table>';
					}

					// Build the actual shortcode input fields
					$html .= '<table><tbody>';
					foreach( $shortcode['params'] as $key => $param ) {
						$html .= $this->cps_build_fields($key, $param);
					}

					// Add a link to remove a content block
					if( $clone_button['show'] ) {
						$html .= '<tr><td colspan="2"><a href="#" class="cps-remove">' . __('Remove', 'childpages') . '</a></td></tr>';
					}
					$html .= '</tbody></table>';

					// Close out the sortable div and display the clone button as needed
					if( $clone_button['show'] ) {
						$html .= '</div>';
						$html .= '<a id="add-' . $shortcode['id'] . '" href="#" class="button-secondary clone-content">' . $clone_button['text'] . '</a>';
						$clone_button['show'] = false;
					}

					// Display notes if provided
					if( array_key_exists('notes', $shortcode) ) {
						$html .= '<p class="cps-notes">' . $shortcode['notes'] . '</p>';
					}
					$html .= '</div>';
				}

				echo $html;
				?>

				<p class="submit">
					<input type="button" id="cps-insert-shortcode" class="button-primary" value="<?php _e('Insert Shortcode', 'childpages'); ?>" onclick="cpsInsertShortcode();" />
					<a href="#" id="cps-cancel-shortcode-insert" class="button-secondary cps-cancel-shortcode-insert" onclick="tb_remove();"><?php _e('Cancel', 'childpages'); ?></a>
				</p>
				</div>
			</div>

		<?php
		}
	}
}

new CPS_Admin_Insert();