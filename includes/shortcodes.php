<?php

/**
 * Return html.
 * @param string/array $args Arguments.
 * @since 1.0.0
 * @return string
 */
function child_pages($atts) {
    ob_start();
    extract(shortcode_atts(array(
        'title' => '',
        'orderby' => '',
        'order' => 'DESC',
        'thumb_size' => '',
        'page_title' => '',
        'page_excerpt' => '',
        'excerpt_lenght' => '',
                    ), $atts
    ));

    global $post;

    if ($title)
        echo '<h2>' . $title . '</h2>';
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
    <ul class="childpages">
        <?php
        $pages = get_pages($args);
        foreach ($pages as $page) {
            $content = $page->post_content;
           // var_dump($page_title);
             // var_dump($page_excerpt);
          ?>
            <li>
                <?php if ($page_title == 'true') { ?>
                    <h3>
                        <a href="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></a>
                    </h3>
                <?php } ?>	 						
                <p>
                    <?php echo get_the_post_thumbnail($page->ID, array($thumb_size, $thumb_size), array('class' => 'alignleft')); ?>
                    <?php if ($page_excerpt == 'true') { ?>
                        <?php echo string_limit_characters($content, $excerpt_lenght); ?>
                    </p>
                <?php } ?>					
            </li>
        <?php } ?>
    </ul>
    <style>
        .childpages {
            list-style: outside none none;
            margin-left: 0;
            padding-left: 0;
        }
    </style>
    <?php
    $output_string = ob_get_contents();
    ob_end_clean();
    return $output_string;
}

add_shortcode('childpages', 'child_pages');
