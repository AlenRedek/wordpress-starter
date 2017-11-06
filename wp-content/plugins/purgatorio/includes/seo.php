<?php
	
/**
 * Required hatom microdata
 *
 * @param boolean
 * @return HTML
 *
**/
if ( ! function_exists('pg_required_hentry_schema') ) {
    function pg_required_hentry_schema($show_modified=false){
        ?>
          <span class="vcard author"><span class="fn"><?php the_author(); ?></span></span>
          <span>@</span>
          <span class="entry-date">
              <time class="published" datetime="<?php the_date('c'); ?>">
                <?php echo get_the_date(); ?>
              </time>
              <br />
              <span class="<?php echo $show_modified ? '' : 'hidden'; ?>">
				<?php _e('Last modified: ', 'purgatorio'); ?>
				<time class="updated" datetime="<?php the_modified_date('c'); ?>">
					<?php printf(__('%s at %s', 'purgatorio'), get_the_modified_date(), get_the_modified_time()); ?>
				</time>
              </span>
              
          </span>
        <?php
    }
}

/**
 * Outputs the JSON LD code in a valid JSON+LD wrapper
 *
 * @param array
 * @return HTML
 *
**/
if ( ! function_exists('pg_json_ld_output') ) {
	function pg_json_ld_output($json_ld_array) {
	    if ( is_array( $json_ld_array ) && ! empty( $json_ld_array ) ) {
	        $json_ld_data = wp_json_encode( $json_ld_array );
	        echo "<script type='application/ld+json'>", $json_ld_data, '</script>', "\n";
	    }
	}
}

/**
 * Prepare JSON+LD schema for software application
 *
 * @param object
 * @return
 *
**/
if ( ! function_exists('pg_json_ld_software_application') ) {
	function pg_json_ld_software_application($post, $author_id) {
	    $json_ld_array = array(
	        '@context' => 'http://schema.org',
	        '@type' => $post->item_type,
	        'name' => $post->post_title,
	        'image' => get_the_post_thumbnail_url($post->ID),
	        'description' => strip_tags($post->post_content),
	        'url' => $post->project_url,
	        'datePublished' => get_the_date(),
	        'dateModified' => get_the_modified_date(),
	        'applicationCategory' => $post->application_category,
	        'operatingSystem' => $post->operating_systems,
	        //'browserRequirements' => $post->browser_requirements,
	        /* Plugin used: Post Ratings (https://wordpress.org/plugins/post-ratings/) */
	        'AggregateRating' => array(
	            'ratingValue' => $post->rating,
	            'ratingCount' => $post->votes,
	            'bestRating' => get_option('post-ratings')["max_rating"],
	        ),
	        'offers' => array(
	            'price' => $post->price,
	            'priceCurrency' => $post->price_currency
	        ),
	        'author' => array(
	            '@id' => $author_id
	        )
	    );
		
	    if($post->publisher_id){
	        $json_ld_array['publisher'] = $post->publisher_id;
	    }else{
	      $json_ld_array['publisher'] = array(
	          '@type' => 'Organization',
	          'name' => $post->publisher_name,
	          'url' => $post->publisher_url
	      );
	    }
	    
	    if($post->browser_requirements){
		    $json_ld_array['browserRequirements'] = $post->browser_requirements;
	    }
	
	    $keywords = array();
	    if ($post_tags = get_the_tags()) {
	      foreach($post_tags as $tag) {
	        $keywords[] = $tag->name;
	      }
	      $json_ld_array['keywords'] = implode(', ', $keywords);
	    }
	
	    if($file = get_field('download_file')){
	        $json_ld_array['downloadURL'] = $file['url'];
	        $json_ld_array['fileSize'] = ar_get_filesize( $file['ID'] );
	    }
	
	    pg_json_ld_output($json_ld_array);
	}
}	
	
?>