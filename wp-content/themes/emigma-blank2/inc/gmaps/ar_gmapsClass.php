<?php

class AR_GMaps_Class {

    private $gmaps_dir;
	private $cpt;
	private $gmaps_api_key;
	private $gmaps_gmaps_marker;
	private $gmaps_gmaps_theme;

    public function __construct($gmaps_dir, $cpt, $gmaps_api_key=''){
		$this->gmaps_dir            = $gmaps_dir;
		$this->gmaps_api_key        = $gmaps_api_key;
		$this->cpt                  = $cpt;
		if(function_exists('get_field')){
			$this->gmaps_api_key    = get_field('gmaps_api_key', 'options') ? get_field('gmaps_api_key', 'options') : $this->gmaps_api_key;
		    $this->gmaps_marker     = get_field('gmaps_marker', 'options');
            $this->gmaps_theme      = strip_tags(get_field('gmaps_theme', 'options'));
		}
	}

	private function enqueue_gmaps_scripts(){
	    $theme_info = wp_get_theme();
        wp_enqueue_style('gmaps-style', get_stylesheet_directory_uri().$this->gmaps_dir . '/css/gmaps.css', array(), $theme_info->get( 'Version' ));

        wp_enqueue_script('gmaps-api', 'https://maps.googleapis.com/maps/api/js?key='.$this->gmaps_api_key, array());
        wp_enqueue_script('gmaps-markerclusterer', get_stylesheet_directory_uri().$this->gmaps_dir.'/js/gmaps-markerclusterer.js', array('jquery', 'gmaps-api'), $theme_info->get( 'Version' ));
        wp_enqueue_script('gmaps-theme', get_stylesheet_directory_uri().$this->gmaps_dir.'/js/gmaps-clustered.js', array('jquery', 'gmaps-api', 'gmaps-markerclusterer'), $theme_info->get( 'Version' ));
	}

	private function prepare_locations(){
	    global $post;
	    $args = array(
            'post_type' => $this->cpt,
            'posts_per_page' => -1
        );
        if(is_single()){
            $args['post__in'] = array($post->ID);
        }
        $posts = get_posts($args);
        $locations = array();
        foreach($posts as $k=>$post){
            $post_meta = '';
                ob_start();
        		echo get_template_part('metas/meta');
        	$post_meta .= ob_get_clean();
            $address = $post->address;
            $locations[$k] = array(
                'id'                => $post->ID,
                'post_type'         => $post->post_type,
                'title'             => $post->post_title,
                'attachment_image'  => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium')[0],
                'lat'               => round($address['lat'], 6),
                'lng'               => round($address['lng'], 6),
                'post_meta'         => $post_meta
            );
        }
        wp_reset_postdata();
        return $locations;
	}

    public function include_gmaps($gmaps_container){
        $this->enqueue_gmaps_scripts();
        if( ! $this->gmaps_theme ){
            $this->gmaps_theme = file_get_contents(get_stylesheet_directory_uri().$this->gmaps_dir.'/js/gmaps-style.json');
        }
        wp_localize_script('gmaps-theme', 'mapStyle', array(
            'marker'    => $this->gmaps_marker,
            'theme'     => json_decode($this->gmaps_theme, true)
        ));
        wp_localize_script('gmaps-theme', 'scriptData', array(
            'plugin_url'        => get_stylesheet_directory_uri().$this->gmaps_dir,
            'gmaps_container'   => $gmaps_container
        ));
        wp_localize_script('gmaps-theme', 'strings', array(
            'show_direction'    => __('Show directions', 'emigma'),
            'direction_link'    => __('Show directions in new window', 'emigma')
        ));
        wp_localize_script('gmaps-theme', 'mapData', $this->prepare_locations());
    }
}

?>