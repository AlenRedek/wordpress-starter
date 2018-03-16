<?php
/**
 * The template for displaying 404 pages (not found).
 * @package understrap
 */

global $current_page;

get_header(); ?>


<div id="primary" class="content-area col-sm-12 col-md-8">
	<main id="main" class="site-main" role="main">

        <section class="error-404 not-found">

            <header class="page-header">
                <h3 class="page-title"><?php _e( 'Oops! That page can&rsquo;t be found.', 'emigma' ); ?></h3>
            </header><!-- .page-header -->

            <div class="page-content">
        		<p><?php printf( __('It looks like nothing was found at this location. Maybe try with a search or return to a %1$shomepage%2$s.', 'emigma' ), '<a href="'.esc_url( get_site_url() ).'">', '</a>' ); ?></p>
        		<!--search form-->
        		<?php get_search_form(); ?>
        	</div><!-- .page-content -->

        </section><!-- .error-404 -->
        
    </main>
</div>


<?php get_footer(); ?>
