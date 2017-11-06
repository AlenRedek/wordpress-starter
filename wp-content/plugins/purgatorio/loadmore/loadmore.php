<?php

/**
 * ---------------------------------------------------------------
 * Include javascript for load-more
 * ---------------------------------------------------------------
 */
add_action('pg_loadmore', 'pg_loadmore_init');
function pg_loadmore_init(){
    
    wp_enqueue_style('purgatorio-loadmore', PURGATORIO__PLUGIN_URL . 'loadmore/assets/css/loadmore.css', array());

    wp_enqueue_script('purgatorio-loadmore', PURGATORIO__PLUGIN_URL . 'loadmore/assets/js/loadmore.js', array('jquery'), false, true);
    wp_localize_script('purgatorio-loadmore', 'loadmoreData', array(
        'container'         => '.loadmore-container',
        'element'           => '.loadmore-item',
        'posts_per_page' 	=> get_option( 'posts_per_page' ),
        'show_more_posts' 	=> __('Show more','emigma'),
        'no_more_posts' 	=> __('Nothing more to show','emigma'),
    ));
}

?>