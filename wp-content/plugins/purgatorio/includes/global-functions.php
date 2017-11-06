<?php
	
/**
 * Get plugin option value
 *
 * @param string
 * @return string
 *
**/
if ( ! function_exists('pg_get_option') ) {
    function pg_get_option($key){
	    $pg_options = get_option( PURGATORIO__SETTINGS );
        if(isset($pg_options[$key])){
			return $pg_options[$key];
		}else{
			return false;
		}
    }
}

/**
 * Get theme option value
 *
 * @param string
 * @return string
 *
**/
if ( ! function_exists('pg_get_theme_option') ) {
    function pg_get_theme_option( $key ) {
	    $theme_key = pg_get_option('theme_option_id');
        $theme_options = get_option($theme_key);
        if ( isset($theme_options[$key]) ) {
            return $theme_options[$key];
        }else{
			return false;
		}
    }
}

/**
 * Determine environment
 *
 * @param
 * @return boolean
 *
**/
if ( ! function_exists('pg_is_dev') ) {
    function pg_is_dev(){
	    $dev_ip = pg_get_option('dev_ip');
        if( $_SERVER['REMOTE_ADDR'] == $dev_ip || current_user_can('administrator') ){
            return true;
        }else{
            return false;
        }
    }
}

/**
 * Local var_dump
 *
 * @param
 * @return HTML
 *
**/
if ( ! function_exists('avar_dump') ) {
    function avar_dump(){
        $args = func_get_args();
        if(is_development()){
            echo '<pre>';
                var_dump($args);
            echo '</pre>';
        }
    }
}

/**
 * Get translated post using Polylang
 *
 * @param integer
 * @return integer
 *
**/
if ( ! function_exists('pg_get_translated_post') ) {
    function pg_get_translated_post($post_id) {
        if(function_exists('pll_current_language') && function_exists('pll_get_post')){
            $post_id = pll_get_post($post_id, pll_current_language());
        }
        
        return $post_id;
    }
}

/**
 * Get translated term using Polylang
 *
 * @param integer
 * @return integer
 *
**/
if ( ! function_exists('pg_get_translated_term') ) {
    function pg_get_translated_term($term_id) {
        if(function_exists('pll_current_language') && function_exists('pll_get_term')){
            $term_id = pll_get_term($term_id, pll_current_language());
        }
        
        return $term_id;
    }
}

/**
 * Get post type years archive
 *
 * @param string
 * @return array
 *
**/
if ( ! function_exists('pg_get_years_archives') ) {
	function pg_get_years_archives($cpt) {
		$args = array(
		    'post_type'			=> $cpt,
		    'posts_per_page'   	=> -1,
		);
		$posts = get_posts( $args );
		$years = array();
		foreach($posts as $p){
			$post_date = date('Y', strtotime($p->post_date));
			if( ! in_array($post_date, $years)){
				$years[] = $post_date;
			}
		}
		
		return $years;
	}
}

/**
 * Group posts by month
 *
 * @param date, string
 * @return string
 *
**/
if ( ! function_exists('pg_group_posts_by_month') ) {
	function pg_group_posts_by_month($date, $format) {
		static $month_title = '';
		$current_month_title = pg_get_date($date, $format);
	
		if( $month_title != $current_month_title ){
			$month_title = $current_month_title;
	
			return explode(' ', $month_title);
		}
	}
}

/**
 * Get page template for pre-selected CPT
 *
 * @param string
 * @return integer
 *
**/
if ( ! function_exists('pg_get_cpt_page_id') ) {
    function pg_get_cpt_page_id($key){
        if(function_exists('get_field')) {
            //return pg_get_translated_post(get_field($key,'options'));
        }
        
        return false;
    }
}

/**
 * Get archive page template
 *
 * @param
 * @return object
 *
**/
if ( ! function_exists('pg_get_archive_page') ) {
    function pg_get_archive_page(){
        $args = array(
            'post_type'         => 'page',
            'posts_per_page'    => 1,
            'meta_query'        => array(
                array(
                    'key'       => 'page_post_type',
                    'value'     => get_post_type()
                )
            )
        );
        $archive_page = new WP_Query( $args );
        wp_reset_postdata();
        
        return $archive_page;
    }
}

/**
 * Get archive page template title
 *
 * @param
 * @return string
 *
**/
if ( ! function_exists('pg_get_archive_page_title') ) {
    function pg_get_archive_page_title(){
        $archive_page_title = '';
        $archive_page = pg_get_archive_page();
        while ($archive_page->have_posts()){
            $archive_page->the_post();
            $archive_page_title = get_the_title();
        }
        wp_reset_postdata();
        
        return $archive_page_title;
    }
}

/**
 * Get archive page template content
 *
 * @param
 * @return string
 *
**/
if ( ! function_exists('pg_get_archive_page_content') ) {
    function pg_get_archive_page_content(){
        $archive_page_content = '';
        $archive_page = pg_get_archive_page();
        while ($archive_page->have_posts()){
            $archive_page->the_post();
            if(get_the_content()){
                ob_start();
                echo get_template_part( 'content', get_post_type() );
                $archive_page_content .= ob_get_clean();
            }
        }
        wp_reset_postdata();
        
        return $archive_page_content;
    }
}

/**
 * Is date format
 *
 * @param string
 * @return boolean
 *
**/
if ( ! function_exists('pg_is_date') ) {
    function pg_is_date($input){
        $date = DateTime::createFromFormat('d.m.Y', $input);
        
        return $date;
    }
}

/**
 * Get i18 formatted date
 *
 * @param date, string
 * @return date
 *
**/
if ( ! function_exists('pg_get_date') ) {
    function pg_get_date($date, $format=false){
        if( ! $format ) $format = get_option('date_format');
        $date = date_i18n($format, strtotime($date));
        
        return $date;
    }
}

/**
 * Pretty URL
 *
 * @param string
 * @return string
 *
**/
if ( ! function_exists('pg_pretty_url') ) {
    function pg_pretty_url($url){
        $pretty_url = preg_replace('/^https?:\/\//', '', $url);
        
        return $pretty_url;
    }
}

/**
 * Extract URL from string
 *
 * @param string
 * @return string
 *
**/
if ( ! function_exists('pg_extract_url') ) {
    function pg_extract_url($text){
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $match);
        
        return $match;
    }
}

/**
 * Generate URL with GET parameters
 *
 * @param array, string, string
 * @return string
 *
**/
if ( ! function_exists('pg_update_url_param') ) {
    function pg_update_url_param($params, $url, $cpt) {
        if( ! $url ){
	        $url = get_permalink(pg_get_cpt_page_id($cpt));
        }
        
        $get_params = $_GET;
        foreach($params as $name=>$value){
            unset($get_params[$name]);
            $get_params[$name] = $value;
        }

        return $url.'?'.http_build_query($get_params);
    }
}

/**
 * Get title statically
 *
 * @param string
 * @return string
 *
**/
if ( ! function_exists('pg_get_static_title') ) {
    function pg_get_static_title($input){
        static $title;
        if($input != $title){
            $title = $input;
            
            return $title;
        }
    }
}

/**
 * Get taxonomy title
 *
 * @param object, object
 * @return string
 *
**/
if ( ! function_exists('pg_get_tax_title') ) {
    function pg_get_tax_title($post, $tax){
        static $tax_title = '';
        $terms = wp_get_post_terms( $post->ID, $tax );
        if($terms && $terms[0]->name != $tax_title){
            $tax_title = $terms[0]->name;
            
            return $tax_title;
        }
    }
}

/**
 * Get file size
 *
 * @param integer
 * @return string
 *
**/
if ( ! function_exists('pg_get_filesize') ) {
    function pg_get_filesize($file_id){
        $bytes = filesize(get_attached_file($file_id));
		$s = array('b', 'Kb', 'Mb', 'Gb');
		$e = floor(log($bytes)/log(1024));
		$size = $bytes/pow(1024, floor($e));
		$size = number_format_i18n($size, 2);
		
		return $size . ' ' . $s[$e];
    }
}

/**
 * Get file ID by URL
 *
 * @param string
 * @return integer
 *
**/
if ( ! function_exists('pg_get_file_id') ) {
    function pg_get_file_id($file_url){
	    global $wpdb;
	    $file_id = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $file_url ));
	    
	    if($file_id){
		    return $file_id[0];
	    }else{
		    return false;
	    }
    }
}

/**
 * Check if file exists in upload folder
 *
 * @param string
 * @return boolean
 *
**/
if ( ! function_exists('pg_file_upload_exists') ) {
    function pg_file_upload_exists($file_upload_uri){
        
        $wp_root_dir = ABSPATH;
        $site_url = get_site_url().'/';
        $file_relative_path = str_replace($site_url, '', $file_upload_uri);
        
        $file_upload_path = $wp_root_dir.$file_relative_path;
        
        if(file_exists($file_upload_path)){
	        return true;
        }else{
	        return false;
        }
    }
}

?>