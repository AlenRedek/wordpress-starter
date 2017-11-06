<?php

/*
******************************************************************************************************
	Register widgetized area and update sidebar with default widgets.
******************************************************************************************************
*/
if ( ! function_exists( 'pg_widgets_init' ) ) {
    add_action( 'widgets_init', 'pg_widgets_init' );
    function pg_widgets_init() {

        register_sidebar( array(
            'name'          => __( 'Hero Static', 'purgatorio' ),
            'description'   => __( 'Home Page main banner', 'purgatorio' ),
            'id'            => 'statichero',
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ) );

        register_sidebar(array(
            'id'            => 'home-widget-1',
            'name'          => __( 'Homepage Widget 1', 'purgatorio' ),
            'description'   => __( 'Displays on the Home Page', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ));

        register_sidebar(array(
            'id'            => 'home-widget-2',
            'name'          => __( 'Homepage Widget 2', 'purgatorio' ),
            'description'   => __( 'Displays on the Home Page', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widgettitle">',
            'after_title'   => '</h3>',
        ));

        register_sidebar( array(
            'name'          => __( 'Header Right', 'purgatorio' ),
            'id'            => 'headerright',
            'description'   =>  __( 'Right widget area side by side with main navigation', 'purgatorio' ),
            'before_widget' => '',
            'after_widget'  => '',
            'before_title'  => '',
            'after_title'   => '',
        ) );
        
        register_sidebar(array(
            'id'            => 'footer-top-widget',
            'name'          =>  __( 'Footer Top Widget', 'purgatorio' ),
            'description'   =>  __( 'Used for footer top area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'id'            => 'footer-widget-1',
            'name'          =>  __( 'Footer Widget 1', 'purgatorio' ),
            'description'   =>  __( 'Used for footer widget area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));

        register_sidebar(array(
            'id'            => 'footer-widget-2',
            'name'          =>  __( 'Footer Widget 2', 'purgatorio' ),
            'description'   =>  __( 'Used for footer widget area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'id'            => 'footer-widget-3',
            'name'          =>  __( 'Footer Widget 3', 'purgatorio' ),
            'description'   =>  __( 'Used for footer widget area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'id'            => 'footer-widget-4',
            'name'          =>  __( 'Footer Widget 4', 'purgatorio' ),
            'description'   =>  __( 'Used for footer widget area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));
        
        register_sidebar(array(
            'id'            => 'footer-bottom-widget',
            'name'          =>  __( 'Footer Bottom Widget', 'purgatorio' ),
            'description'   =>  __( 'Used for footer bottom area', 'purgatorio' ),
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widgettitle">',
            'after_title'   => '</h2>',
        ));
        
        if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-banner.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-banner.php');
			register_widget( 'pg_banner_widget' );
		}
        if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-business-card.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-business-card.php');
			register_widget( 'pg_business_card_widget' );
		}
		if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-dynamic-banner.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-dynamic-banner.php');
			register_widget( 'pg_dynamic_banner_widget' );
		}
		if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-featured-banner.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-featured-banner.php');
			register_widget( 'pg_featured_banner_widget' );
		}
		if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-language-switcher.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-language-switcher.php');
			register_widget( 'pg_language_switcher_widget' );
		}
		if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-login.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-login.php');
			register_widget( 'pg_login_widget' );
		}
		if(file_exists(PURGATORIO__PLUGIN_DIR.'widgets/widget-sidenav.php')){
			require_once(PURGATORIO__PLUGIN_DIR.'widgets/widget-sidenav.php');
			register_widget( 'pg_sidenav_widget' );
		}
    }
}

?>