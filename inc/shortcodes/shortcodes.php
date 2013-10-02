<?php
/**
* Return html.
* @param string/array $args Arguments.
* @since 1.0.0
* @return string
*/
function subpages ( $atts ) {
 	ob_start();           
	extract( shortcode_atts(
		array( 
			'title' => '',
			'orderby' => '',
			'order' => 'DESC',
			'thumb_size' => '',
			'post_title'=>'',
			'excerpt'=>'',
			'excerpt_lenght'=>'',
		), 
			$atts 
		) 
	); 
 
		global $post; 	

if ( $title )echo '<h1>' . $title . '</h1>'; 
?>
<?php
	global $post;
	//Here we geting current page id
	$args = array(
		'sort_column' => $orderby,
		'sort_order' => $order,
		'child_of'=>$post->ID
	);	

	?>
<script type="text/javascript">
jQuery(document).ready( function(e) {

	jQuery("body").on("click", ".media-body h2 a", function (e){
   	post_id = jQuery(this).attr("rel")

		jQuery.ajax({
			type: 'POST',
			url: '/wp-admin/admin-ajax.php',
			data: {
				action: 'get_this_post', post_id : post_id, // the PHP function to run
			},
			success: function(data, textStatus, XMLHttpRequest) {
				jQuery('#'+post_id+' p').remove(); // empty an element
				jQuery('#'+post_id).append(data); // put our content
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				if(typeof console === "undefined") {
					console = {
						log: function() { },
						debug: function() { },
					};
				}
				if (XMLHttpRequest.status == 404) {
					console.log('Element not found.');
				} else {
					console.log('Error: ' + errorThrown);
				}
			}
		});
		
	e.preventDefault();
		
	})	 
	 
	 
});
</script>

<ul class="list-group">
<?php 				
	$pages = get_pages($args); 
  foreach ( $pages as $page ) {
?>
 	<li class="list-group-item">
		<div class="media">
			<?php	echo	get_the_post_thumbnail($page->ID, array($thumb_size,$thumb_size), array('class' => 'media-object pull-left'));?>
			<div class="media-body" id="<?php echo $page->ID; ?>">
				<?php if ( $post_title == 'yes' ) { ?><h2 class="media-heading"><a rel="<?php echo $page->ID; ?>" href="<?php echo get_page_link( $page->ID ); ?>"><?php echo $page->post_title; ?></a></h2><?php } ?>	 						
				<?php if ( $excerpt == 'yes' ) { ?><p><?php $content = $page->post_content; echo string_limit_characters($content,$excerpt_lenght);?></p><?php } ?>					
			</div>
		</div>
	</li>
	<?php	} ?>
</ul>

<?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}

add_shortcode( 'subpages', 'subpages' );
