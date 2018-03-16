<?php

/**
 * ---------------------------------------------------------------
 * Contact form: Dynamic select
 * ---------------------------------------------------------------
 */
if( ! function_exists('cf7_get_dynamic_select_data') ){
    function cf7_get_dynamic_select_data($query, $placeholder) {
        if( count($query->posts) > 0 ){
            $choices[$placeholder] = __('Nothing selected', 'emigma');
            foreach($query->posts as $d){
                $choices[$d->post_title] = $d->post_title;
            }
        }else{
            $placeholder = __('Nothing is available', 'emigma');
            $choices[$placeholder] = $placeholder;
        }
        wp_reset_postdata();
        ar_include_cf7_hooks();

        return $choices;
    }
}

if( ! function_exists('cf7_posts_dynamic_select') ){
    add_filter('wpcf7_posts_dynamic_select', 'cf7_posts_dynamic_select', 10, 2);
    function cf7_posts_dynamic_select($choices, $args=array()) {
        global $qury;
        $query = $qury->get_latest_posts('post', -1);
        $placeholder = __('Choose post', 'emigma');
        $choices = cf7_get_dynamic_select_data($query, $placeholder);

        return $choices;
    }
}

/**
 * ---------------------------------------------------------------
 * Include javascript for load-more
 * ---------------------------------------------------------------
 */
function ar_include_cf7_hooks(){
    wp_enqueue_script('cf7-hooks', get_stylesheet_directory_uri() . '/assets/js/cf7-hooks.js', array('jquery'), false, true);
}

?>