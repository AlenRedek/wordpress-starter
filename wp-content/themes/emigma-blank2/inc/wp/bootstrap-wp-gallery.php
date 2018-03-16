<?php

/*
******************************************************************************************************
	Add ACF gallery after post content
******************************************************************************************************
*/
if ( ! function_exists('ar_display_acf_gallery_after_content') && class_exists('acf') ) {
    //add_filter('the_content', 'ar_display_acf_gallery_after_content');
    function ar_display_acf_gallery_after_content($content){
    	global $post;
    	$shortcode = '';
    	if((is_single() || is_page())){
            $image_ids = get_field('gallery', $post->ID, false);
            if($image_ids){
            	$shortcode = '[gallery ids="' . implode(',', $image_ids) . '"]';
            }
    	}
    	return $content.$shortcode;
    }
}

/*
******************************************************************************************************
	Custom filter function to modify default gallery shortcode output
******************************************************************************************************
*/
remove_shortcode('gallery', 'gallery_shortcode');
add_shortcode('gallery', 'ar_gallery_shortcode');
function ar_gallery_shortcode( $attr ) {
	global $attachment_id, $attachment_size, $selector;

	$post = get_post();
	static $instance = 0;
	$instance++;

	if ( ! empty( $attr['ids'] ) ) {
		// 'ids' is explicitly ordered, unless you specify otherwise.
		if ( empty( $attr['orderby'] ) ) {
			$attr['orderby'] = 'post__in';
		}
		$attr['include'] = $attr['ids'];
	}

	/*$output = apply_filters( 'post_gallery', '', $attr, $instance );
	if ( $output != '' ) {
		return $output;
	}*/

	$atts = shortcode_atts( array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'div',
		'icontag'    => 'div',
		'captiontag' => 'div',
		'columns'    => 4,
		'size'       => 'large',
		'include'    => '',
		'exclude'    => '',
		'link'       => ''
	), $attr, 'gallery' );

	$id = intval( $atts['id'] );

	if ( ! empty( $atts['include'] ) ) {
		$_attachments = get_posts( array( 'include' => $atts['include'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( ! empty( $atts['exclude'] ) ) {
		$attachments = get_children( array( 'post_parent' => $id, 'exclude' => $atts['exclude'], 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	} else {
		$attachments = get_children( array( 'post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $atts['order'], 'orderby' => $atts['orderby'] ) );
	}

	if ( empty( $attachments ) ) {
		return '';
	}

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment ) {
			$output .= wp_get_attachment_link( $att_id, $atts['size'], true ) . "\n";
		}
		return $output;
	}

	// Filter tags and attributes
	$itemtag = tag_escape( $atts['itemtag'] );
	$captiontag = tag_escape( $atts['captiontag'] );
	$icontag = tag_escape( $atts['icontag'] );
	$columns = intval( $atts['columns'] );
	$itemwidth = $columns > 0 ? floor( 12 / $columns ) : 100;
	$float = is_rtl() ? 'right' : 'left';
	$selector = "gallery-{$instance}";

	// Filter gallery CSS
	$output = apply_filters( 'gallery_style', "
		<div id='$selector' class='gallery galleryid-{$id} row'>"
	);

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
		global $attachment_id, $attachment_size;
		$attachment_id = $id;
		$attachment_size = 'gallery-thumbnail';

		$output .= "<{$itemtag} class='col-xs-12 col-sm-6 col-md-{$columns}'>";
			ob_start();
			echo get_template_part('elements/element', 'galleryIcon');
			$output .= ob_get_clean();

		// End itemtag
		$output .= "</{$itemtag}>";

		// Line breaks by columns set
		$i++;
		if($columns > 0 && $i % 2 == 0) $output .= '<div class="clearfix visible-sm"></div>';
		if($columns > 0 && $i % 3 == 0) $output .= '<div class="clearfix visible-md visible-lg"></div>';
	}

	// End gallery output
	$output .= "
		<br style='clear: both;'>
	</div>\n";

	return $output;
}