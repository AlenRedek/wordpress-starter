<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package emigma
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page" class="hfeed site">
		<!-- ******************* The Navbar Area ******************* -->
		<header id="header-area" class="site-header" role="banner">
			<div class="wrapper-fluid wrapper-navbar" id="wrapper-navbar">
				<div class="topbar">
		            <div class="container">
		                <div class="row flex-xs flex-align-center">
		                	<div class="col-xs-6 site-branding-wrapper">
		                    	<a class="skip-link screen-reader-text sr-only" href="#content"><?php _e( 'Skip to content', 'emigma' ); ?></a>
		                        <div class="site-branding">
		                            <!-- Your site title as branding in the menu -->
		                            <?php if(get_custom_logo()): ?>
		                                <?php the_custom_logo(); ?>
		                            <?php else: ?>
		                                <a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
		                            <?php endif; ?>
		                        </div>
		                    </div>
		                    <div class="col-xs-6 navbar-toggle-wrapper">

		                        <div class="nav-search hidden-xs hidden-sm pull-right">
								    <?php get_search_form(); ?>

								</div>

								<div class="navbar-header">
									<!-- .navbar-toggle is used as the toggle for collapsed navbar content -->
									<button class="navbar-toggle hidden-md hidden-lg" type="button" data-toggle="collapse" data-target=".navbar-primary-collapse">
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
										<span class="icon-bar"></span>
									</button>
								</div>
		                	</div>
		                </div><!-- .row -->
		            </div><!-- .container -->
		        </div>
                <nav class="navbar site-navigation" itemscope="itemscope" itemtype="http://schema.org/SiteNavigationElement">
					<div class="container">
						<div class="row">
							<div class="col-xs-12">
			                    <!-- The WordPress Menu goes here -->
			                    <div class="collapse navbar-collapse navbar-primary-collapse">
			                        <?php emigma_header_menu(); ?>
			                        <div class="nav-search hidden-md hidden-lg" data-dropdown="dropdown">
									    <?php get_search_form(); ?>
									    
									</div>
									<?php get_sidebar('headerright'); ?>
			                    </div>
	                    	</div>
	                    </div>
			    	</div>
                </nav><!-- .site-navigation -->
	        </div><!-- .wrapper-navbar end -->

	    	<div class="top-section">
				<?php //emigma_featured_slider(); ?>
	            <?php emigma_featured_image(); ?>
	            <?php //emigma_call_for_action(); ?>
	        </div>
	    </header>
	    <div id="content" class="site-content">
	        <div class="container main-content-area"><?php

	            global $post;
	            $layout_class = of_get_option( 'site_layout' );
	            if( $post && get_post_meta($post->ID, 'site_layout', true) ){
	                    $layout_class = get_post_meta($post->ID, 'site_layout', true);
	            }
	            ?>
	            <div class="row <?php echo $layout_class; ?>">