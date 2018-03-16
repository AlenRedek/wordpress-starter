<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package emigma
 */

/* Globals variables */
global $options_categories;
$options_categories = array();
$options_categories_obj = get_categories();
foreach ($options_categories_obj as $category) {
        $options_categories[$category->cat_ID] = $category->cat_name;
}

global $site_layout;
$site_layout = array('side-pull-left' => esc_html__('Right Sidebar', 'emigma'),'side-pull-right' => esc_html__('Left Sidebar', 'emigma'),'no-sidebar' => esc_html__('No Sidebar', 'emigma'),'full-width' => esc_html__('Full Width', 'emigma'));

// Typography Options
global $typography_options;
$typography_options = array(
        'sizes' => array( '6px' => '6px','10px' => '10px','12px' => '12px','14px' => '14px','15px' => '15px','16px' => '16px','18px'=> '18px','20px' => '20px','24px' => '24px','28px' => '28px','32px' => '32px','36px' => '36px','42px' => '42px','48px' => '48px' ),
        'faces' => roman_google_fonts(),
        'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
        'color'  => true
);
// Typography Defaults
global $typography_defaults;
$typography_defaults = array(
        'size'  => '14px',
        'face'  => 'Open Sans',
        'style' => 'normal',
        'color' => '#6B6B6B'
);
global $typography_types;
$typography_types = array(
		'heading' => __('Heading','emigma'),
		'nav' => __('Menu','emigma')
);

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * @param array $args Configuration arguments.
 * @return array
 */
function emigma_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'emigma_page_menu_args' );

/*
******************************************************************************************************
	Adds custom classes to the array of body classes.
******************************************************************************************************
*/
if ( ! function_exists('emigma_body_classes') ) {
	add_filter( 'body_class', 'emigma_body_classes' );
	function emigma_body_classes( $classes ) {
		// Adds a class of group-blog to blogs with more than 1 published author.
		if( is_multi_author() ){
			$classes[] = 'group-blog';
		}
		// Adds a class of hfeed to non-singular pages.
		if( ! is_singular() ){
			$classes[] = 'hfeed';
		}

		if( is_development() ){
			$classes[] = 'development';
		}else{
			$classes[] = 'production';
		}

		if(function_exists('pll_current_language')){
			$classes[] = 'lang-'.pll_current_language();
		}
		return $classes;
	}
}


/**
 * Mark Posts/Pages as Untiled when no title is used
 */
add_filter( 'the_title', 'emigma_title' );

function emigma_title( $title ) {
  if ( $title == '' ) {
    return 'Untitled';
  } else {
    return $title;
  }
}
/**
 * Add Filters
 */
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar


/**
 * Password protected post form
 */
add_filter( 'the_password_form', 'custom_password_form' );

function custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
  <div class="row">
    <div class="col-lg-10">
        <p>' . __( "This post is password protected. To view it please enter your password below:" ,'emigma') . '</p>
        <label for="' . $label . '">' . __( "Password:" ,'emigma') . ' </label>
      <div class="input-group">
        <input class="form-control" value="' . get_search_query() . '" name="post_password" id="' . $label . '" type="password">
        <span class="input-group-btn"><button type="submit" class="btn btn-default" name="submit" id="searchsubmit" vvalue="' . esc_attr__( "Submit",'emigma' ) . '">' . __( "Submit" ,'emigma') . '</button>
        </span>
      </div>
    </div>
  </div>
</form>';
	return $o;
}
/**
 * Add Bootstrap classes for table
 */
add_filter( 'the_content', 'emigma_add_custom_table_class' );
function emigma_add_custom_table_class( $content ) {
  return str_replace( '<table>', '<table class="table table-hover">', $content );
}

if ( ! function_exists( 'emigma_social_icons' ) ) :
/**
 * Display social links in footer and widgets
 */
function emigma_social_icons(){
  if ( has_nav_menu( 'social-menu' ) ) {
  	wp_nav_menu(
      array(
        'theme_location'  => 'social-menu',
        'container'       => 'nav',
        'container_id'    => 'social',
        'container_class' => 'social-icon',
        'menu_id'         => 'menu-social-items',
        'menu_class'      => 'social-menu',
        'depth'           => 1,
        'fallback_cb'     => '',
        'link_before'     => '<i class="social_icon fa"><span>',
        'link_after'      => '</span></i>'
      )
    );
  }
}
endif;

if ( ! function_exists( 'emigma_bottom_bar_menu' ) ) :
/**
 * Display social links in footer and widgets
 */
function emigma_bottom_bar_menu(){

  	wp_nav_menu(
      array(
        'theme_location'  => 'bottom-bar-menu',
        'container'       => '',
        'container_id'    => '',
        'container_class' => '',
        'menu_id'         => 'bottom-bar-menu',
        'menu_class'      => 'nav',
        'depth'           => 1,
        'fallback_cb'     => ''
      )
    );
}
endif;


if( !function_exists( 'emigma_social' ) ) :
	/**
	 * Fallback function for the deprecated function emigma_social
	*/
function emigma_social(){
  if( of_get_option('footer_social') ) {
    emigma_social_icons();
  }
}
endif;

/**
 * header menu (should you choose to use one)
 */
function emigma_header_menu() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'menu'              => 'primary',
    'theme_location'    => 'primary',
    'depth'             => 2,
    'menu_id'           => 'main-menu',
    'menu_class'        => 'nav navbar-nav',
    'container'         => false,
    'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
    'walker'            => new wp_bootstrap_navwalker()
  ));
} /* end header menu */


/**
 * footer menu (should you choose to use one)
 */
function emigma_footer_links() {
  // display the WordPress Custom Menu if available
  wp_nav_menu(array(
    'container'       => '',                              // remove nav container
    'container_class' => 'footer-links clearfix',   // class of container (should you choose to use it)
    'menu'            => __( 'Footer Links', 'emigma' ),   // nav name
    'menu_class'      => 'nav footer-nav clearfix',      // adding custom nav class
    'theme_location'  => 'footer-links',             // where it's located in the theme
    'before'          => '',                                 // before the menu
    'after'           => '',                                  // after the menu
    'link_before'     => '',                            // before each link
    'link_after'      => '',                             // after each link
    'depth'           => 0,                                   // limit the depth of the nav
    'fallback_cb'     => 'emigma_footer_links_fallback'  // fallback function
  ));
} /* end emigma footer link */


/**
 * Get Post Views - for Popular Posts widget
 */
function emigma_getPostViews($postID){
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return "0 View";
    }
    return $count.' Views';
}
function emigma_setPostViews($postID) {
    $count_key = 'post_views_count';
    $count = (int)get_post_meta($postID, $count_key, true);
    if($count == 0){
        $count = 1;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, $count);
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}


if ( ! function_exists( 'emigma_call_for_action' ) ) :
/**
 * Call for action button & text area
 */
function emigma_call_for_action() {
  if ( is_front_page() && of_get_option('w2f_cfa_text')!=''){
    echo '<div class="cfa">';
      echo '<div class="container">';
        echo '<div class="col-md-8">';
          echo '<span class="cfa-text">'. of_get_option('w2f_cfa_text').'</span>';
          echo '</div>';
          echo '<div class="col-md-4">';
          echo '<a class="btn btn-lg cfa-button" href="'. of_get_option('w2f_cfa_link'). '">'. of_get_option('w2f_cfa_button'). '</a>';
          echo '</div>';
      echo '</div>';
    echo '</div>';
  } else {
  //Do nothing
  }
}
endif;


if ( ! function_exists( 'emigma_featured_slider' ) ) :
/**
 * Featured image slider
 */
function emigma_featured_slider() {
    if ( is_front_page() && of_get_option('emigma_slider_checkbox') == 1 ) {

      echo '<div class="flexslider">';
        echo '<ul class="slides">';
          $count = of_get_option('emigma_slide_number');
          $slidecat = of_get_option('emigma_slide_categories');

            if ( $count && $slidecat ) {
            $query = new WP_Query( array( 'cat' => $slidecat, 'posts_per_page' => $count ) );
//            print_r($query);
            if ($query->have_posts()) :
              while ($query->have_posts()) : $query->the_post();

              echo '<li>';
                if ( has_post_thumbnail() ) { // Check if the post has a featured image assigned to it.
                  the_post_thumbnail('full');
                }
                echo '<div class="flex-caption">';
                  echo '<a href="'. get_permalink() .'">';
                    if ( get_the_title() != '' ) echo '<h1 class="flex-title">'. get_the_title().'</h1>';
                    if ( get_the_excerpt() != '' ) echo '<div class="excerpt">' . get_the_excerpt() .'</div>';
                  echo '</a>';
                echo '</div>';
                endwhile;
              endif;

            } else {
                echo "Slider is not properly configured";
            }
            echo '</li>';
        echo '</ul>';
      echo ' </div>';
    }
}
endif;


if(!function_exists('exclude_slider_category')):
/**
 * Exclude FlexSlider selected Category
 */
add_filter('pre_get_posts' , 'exclude_slider_category');

function exclude_slider_category( $query ) {
	if($query->is_main_query() && $query->is_admin === false){
		if(of_get_option('emigma_slider_checkbox') == 1){
			 //&& $cat = of_get_option('emigma_slide_categories')
			//$query->set( 'cat' , '-'.$cat );
		}
		return $query;
	}
}

endif;

/* -------------------------------------------------------------------- */
/* Google FONTs
 *
 * @since 1.1
 *
 * @modified in 1.1
 *
/* -------------------------------------------------------------------- */
if (!function_exists( 'roman_custom_fonts' ) ) :
function roman_custom_fonts() {
    global $typography_types, $typography_defaults;
    $previous_fonts = array();

    $fonts_url = '';
    $fonts_data = roman_google_fonts_data();
    $subsets = array();

    $faces = array();
    $fonts = array();

    foreach ($typography_types as $type=>$label) {
        // get current font
        $current_font = of_get_option('main_'.$type.'_font');
        if ( ! $current_font ) $current_font = $typography_defaults['face'];
        // not a Google font
        if ( ! isset( $fonts_data[ $current_font ] ) )
            continue;
        $faces[] = $current_font;
    }

    // font data
    foreach ( $faces as $face ) {

        $fontData = $fonts_data[ $face ];
        $face = str_replace( ' ', '+', $face );
        $styles = $fontData[ 'styles' ];
        $styles = join( ',', $styles );
        $fonts[] = "{$face}:{$styles}";
        $subsets += $fontData[ 'subsets' ];
    }
    // remove duplicated elements
    $fonts = array_unique( $fonts );
    $subsets = array_unique( $subsets );

	$subsets_mod = array('latin','latin-ext');

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => implode( '|', $fonts ),
			'subset' => join( ',', $subsets_mod ),
		), 'https://fonts.googleapis.com/css' );
	}
    return $fonts_url;
}
endif;

if (!function_exists('emigma_footer_copyright')):
/**
 * function to show the footer copyright information
 */
function emigma_footer_copyright() {
  global $emigma_footer_copyright;
  $footerpage_id = of_get_option( 'custom_footer_page', 'emigma' );
  $footerpage_id = get_translated_post($footerpage_id);
  echo '&copy; ' . date('Y') . ' ' . of_get_option( 'custom_footer_text', 'emigma' );
  if($footerpage_id) echo ' | ' . '<a href="'.get_permalink($footerpage_id).'">'.get_the_title($footerpage_id).'</a>';
}
endif;

if (!function_exists('emigma_footer_authors')):
/**
 * function to show the footer authors info
 */
function emigma_footer_authors() {
  global $emigma_footer_authors;
  printf( __( 'Authors: %s', 'emigma' ), '<a href="https://emigma.com/" target="_blank">Emigma</a>' );
}
endif;

if (!function_exists('emigma_footer_notice')):
/**
 * function to show the footer notice
 */
function emigma_footer_notice() {
  global $emigma_footer_notice;
  _e('For the best user experience we suggest you use one of the modern web browsers: Chrome, Firefox, Safari or Internet Explorer (10 or newer).','emigma');
}
endif;

/**
 * Get custom CSS from Theme Options panel and output in header
 */
if (!function_exists('get_emigma_theme_options'))  {
  function get_emigma_theme_options(){

    echo '<style type="text/css">';
    if ( of_get_option('link_color')) {
      echo 'a, #infinite-handle span {color:' . of_get_option('link_color') . '}';
    }
    if ( of_get_option('link_hover_color')) {
      echo 'a:hover {color: '.of_get_option('link_hover_color', '#000').';}';
    }
    if ( of_get_option('link_active_color')) {
      echo 'a:active {color: '.of_get_option('link_active_color', '#000').';}';
    }
    if ( of_get_option('element_color')) {
      echo '.btn-default, .label-default, .flex-caption h2, .navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:hover, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > li > a:hover, .navbar-default .navbar-nav > li > a:focus, .navbar-default .navbar-nav > .open > a, .navbar-default .navbar-nav > .open > a:hover, .navbar-default .navbar-nav > .open > a:focus, .dropdown-menu > li > a:hover, .dropdown-menu > li > a:focus, .navbar-default .navbar-nav .open .dropdown-menu > li > a:hover, .navbar-default .navbar-nav .open .dropdown-menu > li > a:focus, .dropdown-menu > .active > a, .navbar-default .navbar-nav .open .dropdown-menu > .active > a {background-color: '.of_get_option('element_color', '#000').'; border-color: '.of_get_option('element_color', '#000').';} .btn.btn-default.read-more, .entry-meta .fa, .site-main [class*="navigation"] a, .more-link { color: '.of_get_option('element_color', '#000').'}';
    }
    if ( of_get_option('element_color_hover')) {
      echo '.btn-default:hover, .label-default[href]:hover, .label-default[href]:focus, #infinite-handle span:hover, .btn.btn-default.read-more:hover, .btn-default:hover, .scroll-to-top:hover, .btn-default:focus, .btn-default:active, .btn-default.active, .site-main [class*="navigation"] a:hover, .more-link:hover, #image-navigation .nav-previous a:hover, #image-navigation .nav-next a:hover  { background-color: '.of_get_option('element_color_hover', '#000').'; border-color: '.of_get_option('element_color_hover', '#000').'; }';
    }
    if (of_get_option('cfa_bg_color')) {
      echo '.cfa { background-color: '.of_get_option('cfa_bg_color', '#000').'; } .cfa-button:hover {color: '.of_get_option('cfa_bg_color', '#000').';}';
    }
    if ( of_get_option('cfa_color')) {
      echo '.cfa-text { color: '.of_get_option('cfa_color', '#000').';}';
    }
    if ( of_get_option('cfa_btn_color')) {
      echo '.cfa-button {border-color: '.of_get_option('cfa_btn_color', '#000').';}';
    }
    if ( of_get_option('cfa_btn_txt_color')) {
      echo '.cfa-button {color: '.of_get_option('cfa_btn_txt_color', '#000').';}';
    }
    if ( of_get_option('heading_color')) {
      echo 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6, .entry-title {color: '.of_get_option('heading_color', '#000').';}';
    }
	if ( of_get_option('main_heading_font')) {
      //echo 'h1, h2, h3, h4, h5, h6, .h1, .h2, .h3, .h4, .h5, .h6 { font-family:"'.of_get_option('main_heading_font', $typography_defaults['face']).'", sans-serif; }';
    }
	if ( of_get_option('main_nav_font')) {
      //echo '.navbar-nav.nav { font-family:"'.of_get_option('main_nav_font', $typography_defaults['face']).'", sans-serif; }';
    }
    if ( of_get_option('top_nav_bg_color')) {
      echo '.navbar.navbar-default {background-color: '.of_get_option('top_nav_bg_color', '#000').';}';
    }
    if ( of_get_option('top_nav_link_color')) {
      echo '.navbar-default .navbar-nav > li > a { color: '.of_get_option('top_nav_link_color', '#000').';}';
    }
    if ( of_get_option('top_nav_dropdown_bg')) {
      echo '.dropdown-menu, .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {background-color: '.of_get_option('top_nav_dropdown_bg', '#000').';}';
    }
    if ( of_get_option('top_nav_dropdown_item')) {
      echo '.navbar-default .navbar-nav .open .dropdown-menu > li > a { color: '.of_get_option('top_nav_dropdown_item', '#000').';}';
    }
    if ( of_get_option('footer_bg_color')) {
      echo '#colophon {background-color: '.of_get_option('footer_bg_color', '#000').';}';
    }
    if ( of_get_option('footer_text_color')) {
      echo '#footer-area, .site-info {color: '.of_get_option('footer_text_color', '#000').';}';
    }
    if ( of_get_option('footer_widget_bg_color')) {
      echo '#footer-area {background-color: '.of_get_option('footer_widget_bg_color', '#000').';}';
    }
    if ( of_get_option('footer_link_color')) {
      echo '.site-info a, #footer-area a {color: '.of_get_option('footer_link_color', '#000').';}';
    }
    if ( of_get_option('social_color')) {
      echo '#social a {color: '.of_get_option('social_color', '#000').' !important ;}';
    }
    if ( of_get_option('social_hover_color')) {
      echo '#social a:hover {color: '.of_get_option('social_hover_color', '#000').'!important ;}';
    }
    global $typography_options, $typography_defaults;

    $typography = of_get_option('main_body_typography', $typography_defaults);
    $typography = false;
    if ( $typography ) {
      $font_family = isset( $typography_options['faces'][$typography['face']] ) ? $typography_options['faces'][$typography['face']] : $typography_options['faces'][$typography_defaults['face']];
      $font_size = isset( $typography['size'] ) ? $typography['size'] : $typography_defaults['size'];
      $font_style = isset( $typography['style'] ) ? $typography['style'] : $typography_defaults['style'];
      $font_color = isset( $typography['color'] ) ? $typography['color'] : $typography_defaults['color'];
      echo '.entry-content { font-family:"' . $font_family . '", sans-serif; font-size:' . $font_size . '; font-weight: ' . $font_style . '; color:'.$font_color . ';}';
    }
    if ( of_get_option('custom_css')) {
      echo html_entity_decode(of_get_option( 'custom_css', 'no entry'));
    }
      echo '</style>';
  }
}
add_action('wp_head','get_emigma_theme_options',10);

/*
******************************************************************************************************
	CF7 custom properties for bootstrap plugin
******************************************************************************************************
*/
add_filter('cf7bs_default_form_properties', 'cf7bs_custom_form_properties');
/*
	$properties = array(
			'layout'		=> 'default', // 'default', 'inline', 'horizontal'
			'size'			=> 'default', // 'default', 'small', 'large'
			'group_layout'	=> 'default', // 'default', 'inline', 'buttons'
			'group_type'	=> 'default', // 'default', 'primary', 'success', 'info', 'warning', 'danger' (only if group_layout=buttons)
			'submit_size'	=> '', // 'default', 'small', 'large' or leave empty to use value of 'size'
			'submit_type'	=> 'primary', // 'default', 'primary', 'success', 'info', 'warning', 'danger'
			'required_html'	=> '<span class="required">*</span>',
			'grid_columns'	=> 12,
			'label_width'	=> 3, // integer between 1 and 'grid_columns' minus 1
			'breakpoint'	=> 'sm', // xs, sm, md, lg
	);
*/
function cf7bs_custom_form_properties($prop){
	$prop['layout']		 = 'default';
	$prop['submit_type'] = 'default';
	return $prop;
}

?>