<?php
/* Our variables from the widget settings. */
$title = apply_filters('widget_title', $instance['title']);
$parents = $parents;
$orderby = $instance['orderby'];
$order = $instance['order'];
$tag = $instance['tag'];
$thumb_size = $instance['thumb_size'];
$show_title = $instance['show_title'];
$show_excerpt = $instance['show_excerpt'];
$excerpt_lenght = $instance['excerpt_lenght'];
/* Display the widget title if one was input (before and after defined by themes). */
if ($title)
    echo $before_title . $title . $after_title;
?>
<?php
global $post;
//Here we geting current page id
$args = array(
    'sort_column' => $orderby,
    'sort_order' => $order,
    'child_of' => $post->ID
);
?>
<ul>
    <?php
    $pages = get_pages($args);
    foreach ($pages as $page) {
        $content = $page->post_content;
        ?>
        <li>
            <?php if ($show_title == 'on') { ?>
            <<?php echo $tag ?> class="media-heading">
                <a href="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></a>
            </<?php echo $tag ?>>
                <?php } ?>

             <?php
              $image = get_the_post_thumbnail($page->ID, array($thumb_size, $thumb_size), array('class' => 'alignleft'));
              if($image || $show_excerpt == 'on') {
             ?>
                <p>
                  <?php echo $image; ?>
                  <?php if ($show_excerpt == 'on') { ?>
                     <?php echo string_limit_characters($content, $excerpt_lenght); ?>
                  <?php } ?>
                </p>
              <?php } ?>

        </li>
<?php } ?>
</ul>
