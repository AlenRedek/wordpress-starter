<?php
/**
 * Sample implementation of the Custom Header feature
 * http://codex.wordpress.org/Custom_Headers
 *
 * You can add an optional custom header image to header.php like so ...

	<?php if ( get_header_image() ) : ?>
	<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
		<img src="<?php header_image(); ?>" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="">
	</a>
	<?php endif; // End header image check. ?>

 *
 * @package emigma
 */

/**
 * Setup the WordPress core custom header feature.
 *
 * @uses emigma_header_style()
 * @uses emigma_admin_header_style()
 * @uses emigma_admin_header_image()
 *
 * @package emigma
 */
function emigma_custom_header_setup() {
	//header_image
	add_theme_support( 'custom-header', apply_filters( 'emigma_custom_header_args', emigma_header_args()));
}
add_action( 'after_setup_theme', 'emigma_custom_header_setup' );


if ( ! function_exists( 'emigma_header_args' ) ) :

function emigma_header_args($key=false){
	$defaults = array(
		'default-image'          => '',
		'default-text-color'     => 'ffffff',
		'width'                  => get_option('large_size_w'),
		'height'                 => 330,
		'flex-height'            => true,
		'flex-width'			 => false,
		//'header-text' 			 => array( 'site-title', 'site-description' ),
		'wp-head-callback'       => 'emigma_header_style',
		'admin-head-callback'    => 'emigma_admin_header_style',
		'admin-preview-callback' => 'emigma_featured_header_image',
	);
	if($key) return $defaults[$key];
	return $defaults;
}
endif;


if ( ! function_exists( 'emigma_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see emigma_custom_header_setup().
 */
function emigma_header_style() {
	$header_text_color = get_header_textcolor();
	$header_image 	   = get_header_image();

	if(defined('HEADER_IMAGE_HEIGHT')){
		$header_height	   = HEADER_IMAGE_HEIGHT;
	}
	if(defined( 'HEADER_IMAGE_WIDTH')){
		$header_width	   = HEADER_IMAGE_WIDTH;
	}
	//'header-text' => array( 'site-title', 'site-description' ),
	//rvar_dump(HEADER_IMAGE);

	if(!$header_image){
		return;
	}

	//var_dump(emigma_custom_header_args());
	// If no custom options for text are set, let's bail
	// get_header_textcolor() options: HEADER_TEXTCOLOR is default, hide text (returns 'blank') or any hex value
	// If we get this far, we have custom styles. Let's do this.
	?>
	<style type="text/css">
	<?php
		// Has the text been hidden?
		if ( 'blank' == $header_text_color ) :
	?>
		.site-title,
		.site-description {
			position: absolute;
			clip: rect(1px, 1px, 1px, 1px);
		}
        #headimg { }
	<?php
		// If the user has set a custom color for the text use that
		else :
	?>
        #headimg {
         	background-image: url(<?php echo $header_image; ?>);
         	min-height: <?php echo $header_height; ?>px;
        }
		#header-tools,
        #header-tools .header-title,
        #breadcrumbs a,
        #breadcrumbs a:hover, #breadcrumbs a:active, #breadcrumbs a:focus {
        	color: #<?php echo $header_text_color; ?>;
        }
	<?php endif; ?>
	</style>
	<?php
}
endif; // emigma_header_style

if ( ! function_exists('emigma_admin_header_style')) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * @see emigma_custom_header_setup().
 */
function emigma_admin_header_style() {
	$header_image = get_header_image();

?>
	<style type="text/css">
        #headimg {
         	background-image: url(<?php echo $header_image; ?>);
        }

	</style>
<?php
}
endif; // emigma_admin_header_style

if (!function_exists('emigma_featured_header_image')) :
/**
 * Custom header image markup displayed on the Appearance > Header admin panel.
 *
 * @see emigma_custom_header_setup().
 */
function emigma_featured_header_image() {
	emigma_featured_image();
}
endif; // emigma_admin_header_image

if(!function_exists('featured_title')):
/**
 * Featured image title
 */
function featured_title() {
	global $post;
	
	if(is_category() || is_paged()){
		echo single_cat_title('',false);
	}elseif(is_tax()){
		echo single_term_title();
	}elseif(is_tag()){
		echo single_tag_title();
	}elseif(is_archive()){
		_e('Archives','emigma');
	}elseif(is_search()){
		//_e('Search results','emigma');
		printf( __( 'Search Results for: %s', 'emigma' ), '<span>' . get_search_query() . '</span>' );
	}elseif(is_404()){
		_e('Page not found','emigma');
	}elseif(is_single()){
		$archive_page = get_page_id($post->post_type);
		if($archive_page){
			echo get_the_title($archive_page);
		}
	}else echo get_the_title();
}
endif;


if (!function_exists('emigma_featured_image')):
/**
 * Featured image header
 */
function emigma_featured_image() {
	global $post, $ar_gmaps;

	if(is_home() || is_front_page()){
		return;
	}elseif( $post && get_page_id('pharmacy') === $post->ID || is_singular('pharmacy') ){
		$gmaps_container = 'gmaps-container';
		$ar_gmaps->include_gmaps($gmaps_container,'pharmacy');
		?>
		<div id="<?php echo $gmaps_container; ?>"></div>
		<?php
	}else{
		$headstyle = '';
		// If category then display default header image
		if(is_category() || is_tax() || is_tag()){
			if(function_exists('z_taxonomy_image_url')){
				$header_image = z_taxonomy_image_url();
				$headstyle = sprintf(' style="background-image:url(%s);"', $header_image);
			}

		}else
		if(of_get_option('header_image')){
			// If the option is checked in Appearance > Header admin panel
			if(has_post_thumbnail() && !is_attachment()){
				$tmp = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'large', false, '' );
				$header_image = $tmp[0];
				$headstyle = sprintf(' style="background-image:url(%s);"', $header_image);
			}
		}
		?>
		<div id="headimg" class="jumbotron background-image" <?php echo $headstyle; ?>></div>
		<?php
	}
    ?>

	<div id="header-tools">
        <div class="container">
        	<div class="row">
        		<div class="col-xs-12">
        			<div class="panel panel-primary panel-top-offset">
        				<div class="panel-body">
				            <div class="row">
				                <div class="col-xs-12 col-md-6">
				                	<h3 class="header-title xs-mt-0"><?php featured_title(); ?></h3>
				                </div>
				                <div class="hidden-xs hidden-sm col-md-6">
					            	<?php get_template_part('elements/element','print'); ?>
				            	</div>
				            	<div class="col-xs-12">
				            		<div id="breadcrumbs">
					        		    <?php if (function_exists('yoast_breadcrumb')) yoast_breadcrumb(); ?>
					        		</div><!--.breadcrumbs-->
				            	</div>
				            </div>
				    	</div>
			    	</div>
				</div>
        	</div>
        </div>
    </div>

	<?php
}
endif;