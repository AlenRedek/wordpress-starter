<?php
/**
 * Declaring widgets
 *
 *
 * @package emigma
 */

/*
******************************************************************************************************
	Register widgetized area and update sidebar with default widgets.
******************************************************************************************************
*/
if ( ! function_exists( 'emigma_widgets_init' ) ) {
    add_action( 'widgets_init', 'emigma_widgets_init' );
    function emigma_widgets_init() {
        register_sidebar( array(
            'name'          => __( 'Sidebar', 'emigma' ),
            'id'            => 'sidebar-1',
            'before_widget' => '<aside id="%1$s" class="widget %2$s">',
            'after_widget'  => '</aside>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));

        register_sidebar( array(
            'name'          => __( 'Hero Static', 'emigma' ),
            'id'            => 'statichero',
            'description'   => 'Static Hero widget. no slider functionallity',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ) );

        register_sidebar(array(
            'id'            => 'home-widget-1',
            'name'          => __( 'Homepage Widget 1', 'emigma' ),
            'description'   => __( 'Displays on the Home Page', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ));

        register_sidebar(array(
            'id'            => 'home-widget-2',
            'name'          =>  __( 'Homepage Widget 2', 'emigma' ),
            'description'   => __( 'Displays on the Home Page', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ));

        register_sidebar( array(
            'name'          => __( 'Header Right', 'emigma' ),
            'id'            => 'headerright',
            'description'   => 'Right widget area side by side with main navigation',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ) );

        register_sidebar(array(
            'id'            => 'footer-widget-1',
            'name'          =>  __( 'Footer Widget 1', 'emigma' ),
            'description'   =>  __( 'Used for footer widget area', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'id'            => 'footer-widget-2',
            'name'          =>  __( 'Footer Widget 2', 'emigma' ),
            'description'   =>  __( 'Used for footer widget area', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'id'            => 'footer-widget-3',
            'name'          =>  __( 'Footer Widget 3', 'emigma' ),
            'description'   =>  __( 'Used for footer widget area', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'id'            => 'footer-widget-4',
            'name'          =>  __( 'Footer Widget 4', 'emigma' ),
            'description'   =>  __( 'Used for footer widget area', 'emigma' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-business-card.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-business-card.php');
        	register_widget( 'emigma_business_card_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-banner.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-banner.php');
        	register_widget( 'emigma_banner_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-featured-banner.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-featured-banner.php');
        	register_widget( 'emigma_featured_banner_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-language.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-language.php');
        	register_widget( 'emigma_language_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-popular-posts.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-popular-posts.php');
        	register_widget( 'emigma_popular_posts_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-sidenav.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-sidenav.php');
        	register_widget( 'emigma_sidenav_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-social.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-social.php');
        	register_widget( 'emigma_social_widget' );
        }
        if(file_exists(get_stylesheet_directory().'/inc/widgets/widget-login.php')){
        	require_once(get_stylesheet_directory() . '/inc/widgets/widget-login.php');
        	register_widget( 'emigma_login_widget' );
        }
    }
}

?>