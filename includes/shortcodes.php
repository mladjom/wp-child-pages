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
        'page_thumbnail' => '',
        'thumbnail_size' => '',
        'thumbnail_align' => '',
        'page_title' => '',
        'page_excerpt' => '',
        'excerpt_lenght' => '',
        'orderby' => '',
        'order' => 'DESC',
        ), $atts
    ));

    global $post;

    if ($title)
        echo '<h1>' . $title . '</h1>';
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
           // var_dump($page);
            $content = $page->post_content;
           // var_dump($page_title);
             // var_dump($page_excerpt);
          ?>
            <li>
                <?php if ($page_title == 'true') { ?>
                    <h2>
                        <a href="<?php echo get_page_link($page->ID); ?>"><?php echo $page->post_title; ?></a>
                    </h2>
                <?php } ?>	 						
                    <?php if ($page_thumbnail == 'true') 
                        echo get_the_post_thumbnail($page->ID, $thumbnail_size, array('class' => $thumbnail_align)); 
                    ?>
                    <?php if ($page_excerpt == 'true') { ?>
                        <?php echo $page->post_excerpt; ?>
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
