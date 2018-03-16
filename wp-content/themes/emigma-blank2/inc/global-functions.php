<?php

/*
******************************************************************************************************
	Determine environment
******************************************************************************************************
*/
if ( ! function_exists('is_development') ) {
	function is_development(){
		if($_SERVER['REMOTE_ADDR'] == '84.255.206.218' || stristr($_SERVER['REMOTE_ADDR'],'192.168.1') || $_SERVER['REMOTE_ADDR'] == '193.200.207.30'){
			return true;
		}else{
			return false;
		}
	}
}

/*
******************************************************************************************************
	Local var_dump
******************************************************************************************************
*/
if ( ! function_exists('avar_dump') ) {
	function avar_dump(){
		$args = func_get_args();
		if(is_development()){
			echo '<pre>';
				print_r($args);
			echo '</pre>';
		}
	}
}

/*
******************************************************************************************************
    Helper function to return the theme option value.
******************************************************************************************************
*/
if ( ! function_exists('of_get_option') ) {
    function of_get_option( $name, $default = false ) {
        $option_name = '';
        // Get option settings from database
        $options = get_option( 'emigma' );
        // Return specific option
        if ( isset( $options[$name] ) ) {
            return $options[$name];
        }
        return $default;
    }
}

/*
******************************************************************************************************
    Get translated post
******************************************************************************************************
*/
if ( ! function_exists('get_translated_post') ) {
    function get_translated_post($post_id) {
        if(function_exists('pll_current_language') && function_exists('pll_get_post')){
            $post_id = pll_get_post($post_id, pll_current_language());
        }
        return $post_id;
    }
}

/*
******************************************************************************************************
    Get translated term
******************************************************************************************************
*/
if ( ! function_exists('get_translated_term') ) {
    function get_translated_term($term_id) {
        if(function_exists('pll_current_language') && function_exists('pll_get_term')){
            $term_id = pll_get_term($term_id, pll_current_language());
        }
        return $term_id;
    }
}

/*
******************************************************************************************************
    Main pages
******************************************************************************************************
*/
if ( ! function_exists('get_page_id') ) {
    function get_page_id($key){
        if(function_exists('get_field')) {
            return get_translated_post(get_field($key,'options'));
        }
    }
}

/*
******************************************************************************************************
    Is date format
******************************************************************************************************
*/
if ( ! function_exists('is_date_format') ) {
    function is_date_format($input){
        $date = DateTime::createFromFormat('d.m.Y', $input);
        return $date;
    }
}

/*
******************************************************************************************************
    Get date format
******************************************************************************************************
*/
if ( ! function_exists('get_date_format') ) {
    function get_date_format($date, $format=false){
        if(!$format) $format = get_option('date_format');
        $date = date_i18n($format, strtotime($date));
        return $date;
    }
}

/*
******************************************************************************************************
    Pretty URL
******************************************************************************************************
*/
if ( ! function_exists('pretty_url') ) {
    function pretty_url($url){
        $pretty_url = preg_replace('/^https?:\/\//', '', $url);
        return $pretty_url;
    }
}

/*
******************************************************************************************************
    Exctract URL from string
******************************************************************************************************
*/
if ( ! function_exists('ar_extract_url') ) {
    function ar_extract_url($text){
        preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $text, $match);
        return $match;
    }
}

/*
******************************************************************************************************
    Generate URL with GET parameters
******************************************************************************************************
*/
if ( ! function_exists('update_url_params') ) {
    function update_url_params($params, $cpt) {
        $url = get_permalink(get_page_id($cpt));
        $get_params = $_GET;

        foreach($params as $name=>$value){
            unset($get_params[$name]);
            $get_params[$name] = $value;
        }

        return $url.'?'.http_build_query($get_params);
    }
}

/**
 * ---------------------------------------------------------------
 * Get news archives
 * ---------------------------------------------------------------
 */
function ar_get_archives($cpt) {
	$args = array(
	    'post_type'			=> $cpt,
	    'posts_per_page'   	=> -1,
	);
	$posts = get_posts( $args );
	$years = array();
	foreach($posts as $p){
		$post_date = date('Y', strtotime($p->post_date));
		if(!in_array($post_date, $years)){
			$years[] = $post_date;
		}
	}
	return $years;
}

/**
 * ---------------------------------------------------------------
 * Group posts by month
 * ---------------------------------------------------------------
 */
function ar_group_posts_by_month($date, $format) {
	static $month_title = '';
	$current_month_title = get_date_format($date, $format);

	if( $month_title != $current_month_title ){
		$month_title = $current_month_title;

		return explode(' ', $month_title);
	}
}

?>