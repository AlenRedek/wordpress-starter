<?php

/**
 * ---------------------------------------------------------------
 * Include javascript for load-more
 * ---------------------------------------------------------------
 */
add_action('ar_loadmore_init', 'ar_loadmore_init');
function ar_loadmore_init(){

    $loadmore_dir = str_replace(get_stylesheet_directory(), '', dirname(__FILE__));
    $posts_per_page = get_option( 'posts_per_page' );

    wp_enqueue_style('loadmore', get_stylesheet_directory_uri() . $loadmore_dir . '/assets/css/loadmore.css', array());

    wp_enqueue_script('loadmore', get_stylesheet_directory_uri() . $loadmore_dir . '/assets/js/loadmore.js', array('jquery'), false, true);
    wp_localize_script('loadmore', 'loadmoreData', array(
        'container'         => '.loadmore-container',
        'element'           => '.loadmore-item',
        'posts_per_page' 	=> $posts_per_page,
        'show_more_posts' 	=> __('Show more','emigma'),
        'no_more_posts' 	=> __('Nothing more to show','emigma'),
    ));
}

?>