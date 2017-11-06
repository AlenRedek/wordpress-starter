<?php

class PG_GMaps_Class {

    protected $gmaps_dir;
	private $gmaps_api_key;
	private $gmaps_gmaps_marker;
	private $gmaps_gmaps_theme;
	private $pg_options;

    public function __construct(){
	    $this->pg_options = get_option( PURGATORIO__SETTINGS );
	    if( ! $api_key = $this->pg_options['gmaps_api_key'] ){
		    return false;
	    }
	    
		$this->gmaps_api_key    = $api_key;
		$this->gmaps_dir        = 'gmaps';
		$this->gmaps_marker     = $this->pg_options['gmaps_marker'];
		if( ! pg_file_upload_exists($this->gmaps_marker) ){
			$this->gmaps_marker = '';
		}
        $this->gmaps_theme      = strip_tags($this->pg_options['gmaps_style']);
        if( ! $this->gmaps_theme ){
            $this->gmaps_theme = file_get_contents(PURGATORIO__PLUGIN_URL.$this->gmaps_dir.'/js/gmaps-style.json');
        }
        
        add_shortcode('pg_gmaps', array($this, 'gmaps_shortcode'));
	}
	
	protected function enqueue_gmaps_scripts(){
        wp_enqueue_style('purgatorio-gmaps-style', PURGATORIO__PLUGIN_URL.$this->gmaps_dir . '/css/gmaps.css', array(), PURGATORIO_VERSION);

        wp_enqueue_script('purgatorio-gmaps-api', 'https://maps.googleapis.com/maps/api/js?key='.$this->gmaps_api_key, array());
        wp_enqueue_script('purgatorio-gmaps-markerclusterer', PURGATORIO__PLUGIN_URL.$this->gmaps_dir.'/js/gmaps-markerclusterer.js', array('jquery', 'purgatorio-gmaps-api'), PURGATORIO_VERSION);
        wp_enqueue_script('purgatorio-gmaps-theme', PURGATORIO__PLUGIN_URL.$this->gmaps_dir.'/js/gmaps-clustered.js', array('jquery', 'purgatorio-gmaps-api', 'purgatorio-gmaps-markerclusterer'), PURGATORIO_VERSION);
	}
	
	protected function get_post_geolocation($post_meta){
		$geolocation = false;
		
		if( isset($post_meta[$this->pg_options['gmaps_lat_metakey']]) && isset($post_meta[$this->pg_options['gmaps_lng_metakey']])){
			$geolocation = array(
	            'lat' => $post_meta[$this->pg_options['gmaps_lat_metakey']][0],
	            'lng' => $post_meta[$this->pg_options['gmaps_lng_metakey']][0],
	        );
		}elseif( isset($post_meta[$this->pg_options['gmaps_address_metakey']]) ){
			$address = $post_meta[$this->pg_options['gmaps_address_metakey']][0];
			$geolocation = $this->get_coordinates($address);
		}
            
        return $geolocation;
	}

	protected function get_coordinates($address) {
		if( ! $address ){
			return false;
		}
		
        $address = urlencode($address);
        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$this->gmaps_api_key";
        
        $request = wp_remote_get($url);
        $response = wp_remote_retrieve_body($request);
        $result = json_decode($response, true);
        
        if(pg_is_dev() && $result['status'] !== 'OK'){
	        echo '<pre>'.$response.'</pre>';
            return false;
        }             
        
        $geolocation = array(
            'lat' => $result['results'][0]['geometry']['location']['lat'],
            'lng' => $result['results'][0]['geometry']['location']['lng'],
        );

        return $geolocation;
    }

	protected function prepare_locations($cpt, $page_template){
	    global $post;
	    $args = array(
            'post_type' => $cpt,
            'posts_per_page' => -1
        );
        if(is_singular($cpt)){
            $args['post__in'] = array($post->ID);
        }elseif($page_template){
            $args['meta_query'] = array(
                array(
                    'key'   => '_wp_page_template',
                    'value' => $page_template
                )
            );
        }
        $posts = get_posts($args);
        $locations = array();
        $i=0;
        foreach($posts as $post){
	        $post_meta = get_post_meta($post->ID);
	        $geolocation = $this->get_post_geolocation($post_meta);
            if($geolocation['lat'] && $geolocation['lng']){
	            $post_meta_html = '';
	                ob_start();
	        		echo get_template_part('metas/meta');
	        	$post_meta_html .= ob_get_clean();
                $locations[$i] = array(
                    'id'                => $post->ID,
                    'post_type'         => $post->post_type,
                    'title'             => $post->post_title,
                    'attachment_image'  => wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'medium')[0],
                    'lat'               => round($geolocation['lat'], 6),
                    'lng'               => round($geolocation['lng'], 6),
                    'post_meta'         => $post_meta_html
                );
            }
            $i++;
        }

        wp_reset_postdata();
        //var_dump($locations);die();
        return $locations;
	}

    public function include_gmaps($id, $cpt='page', $page_template=''){
	    $locations = $this->prepare_locations($cpt, $page_template);
	    if( ! $locations ){
		    return false;
	    }
	    
        $this->enqueue_gmaps_scripts();
        
        wp_localize_script('purgatorio-gmaps-theme', 'mapStyle', array(
            'marker'    => $this->gmaps_marker,
            'theme'     => json_decode($this->gmaps_theme, true)
        ));
        wp_localize_script('purgatorio-gmaps-theme', 'scriptData', array(
            'plugin_url'        => PURGATORIO__PLUGIN_URL.$this->gmaps_dir,
            'gmaps_container'   => $id
        ));
        wp_localize_script('purgatorio-gmaps-theme', 'strings', array(
            'distance'          => __('Distance:', 'emigma'),
            'show_direction'    => __('Show directions', 'emigma'),
            'direction_link'    => __('View on Google Maps', 'emigma')
        ));
        wp_localize_script('purgatorio-gmaps-theme', 'mapData', $locations);
    }
    
    public function gmaps_shortcode($atts, $content = null){
		
		extract(
			shortcode_atts(
				array(
					'id' 				=> 'gmaps-container',
					'cpt' 				=> 'page',
					'page_template' 	=> '',
					'height'			=> '400px'
				), $atts, 'purgatorio'
			)
		);
		
		$shortcode_string = "<div id='{$id}' style='height:{$height};'></div>";
	    $this->include_gmaps($id, $cpt, $page_template);
	    
	    /**
		 * Filters the shortcode.
		 *
		 * @since Unknown
		 *
		 * @param string $shortcode_string The full shortcode string.
		 * @param array  $attributes       The attributes within the shortcode.
		 * @param string $content          The content of the shortcode, if available.
		 */
		$shortcode_string = apply_filters( "pg_gmaps_shortcode", $shortcode_string, $atts, $content );

		return $shortcode_string;
    }
}

?>