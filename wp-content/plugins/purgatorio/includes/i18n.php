<?php

/**
 * Get current language using Polylang
 *
 * @param
 * @return string
 *
**/
if ( ! function_exists('pg_get_current_language') ) {
    function pg_get_current_language() {
	    $current_lang = 'en';
        if(function_exists('pll_current_language')){
            $current_lang = pll_current_language();
        }
        
        return $current_lang;
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
	
?>