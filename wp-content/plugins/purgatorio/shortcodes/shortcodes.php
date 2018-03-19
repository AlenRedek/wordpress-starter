<?php
/**
 * Declaring shortcodes
 *
 *
 * @package purgatorio
 */
 
if(file_exists(PURGATORIO__PLUGIN_DIR.'/shortcodes/shortcode-buisness-card.php')){
	require_once(PURGATORIO__PLUGIN_DIR.'/shortcodes/shortcode-buisness-card.php');
	if( function_exists('pg_buisness_card_func') ){
		add_shortcode( 'pg_buisness_card', 'pg_buisness_card_func' );
	}
}
	
?>