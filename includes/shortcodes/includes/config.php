<?php

/**
 * Define the shortcode parameters
 *
 * @since 1.0
 */
$childpages_shortcodes['childpages'] = array(
    'title' => __('Child Pages', 'childpages'),
    'id' => 'cps-projects-roundabout',
    'template' => '[childpages {{attributes}}] {{content}} [/childpages]',
    'params' => array(
        'title' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Title', 'childpages'),
            'desc' => __('(optional)', 'childpages')
        ),
        'page_thumbnail' => array(
            'type' => 'checkbox',
            'label' => __('Show Thumbnail', 'childpages'),
            'desc' => __('', 'childpages'),
            'default' => false
        ),
        'thumbnail_size' => array(
            'std' => '',
            'type' => 'select',
            'label' => __('Thumbnail Size', 'childpages'),
            'desc' => __('Chose thumbnail', 'childpages'),
                 'options' => 
                CP()->get_thumbnail_sizes()
            ),
        'thumbnail_align' => array(
            'type' => 'select',
            'label' => __('Thumbnail Align', 'childpages'),
            'desc' => __('', 'childpages'),
            'options' => array(
                'alignleft' => 'Align Left',
                'alignright' => 'Align Right',
                'alignnone' => 'Align None',
            )
        ),
        'page_title' => array(
            'type' => 'checkbox',
            'label' => __('Show page title', 'childpages'),
            'desc' => __('', 'childpages'),
            'default' => true
        ),
        'page_excerpt' => array(
            'type' => 'checkbox',
            'label' => __('Show excerpt', 'childpages'),
            'desc' => __('', 'childpages'),
            'default' => true
        ),
        'excerpt_lenght' => array(
            'std' => '',
            'type' => 'text',
            'label' => __('Excerpt length', 'childpages'),
            'desc' => __('(characters)', 'childpages')
        ),
        'order' => array(
            'type' => 'select',
            'label' => __('Order direction', 'childpages'),
            'desc' => __('How to order the items.', 'childpages'),
            'options' => array(
                'menu_order' => 'Menu Order',
                'none' => 'None',
                'ID' => 'ID',
                'title' => 'Title',
                'date' => 'Date',
                'rand' => 'Rand',
            )
        ),
        'orderby' => array(
            'type' => 'select',
            'label' => __('Order By', 'childpages'),
            'desc' => __('The order direction.', 'childpages'),
            'options' => array(
                'DESC' => 'DESC',
                'ASC' => 'ASC',
            )
        ),
    )
);

