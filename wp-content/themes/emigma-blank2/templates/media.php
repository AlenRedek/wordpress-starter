<?php
/**
 * Template name: Media
**/

global $qury, $cpt, $year;

$cpt = 'media';
$year = isset($_GET['years']) ? $_GET['years'] : date('Y');
$media = $qury->get_posts_by_year($cpt, $year);

do_action('ar_loadmore_init');
get_header();

?>

<div id="primary" class="col-xs-12">
        <main id="main" class="site-main" role="main">

			<?php if(is_user_logged_in()): ?>

	        	<div class="row">
	        		<div class="col-xs-12 col-md-8">
	        			<?php the_content(); ?>
	        		</div>
	        	</div>

	        	<div class="row">
	        		<div class="col-xs-12">
				        <?php if ( $media->have_posts() ) : ?>

			        		<?php get_template_part('elements/element', 'selectYear'); ?>

			                <?php /* Start the Loop */ ?>
			                <div class="row loadmore-container">

			                <?php while ( $media->have_posts() ) : $media->the_post(); ?>

								<div class="col-xs-12 col-md-4 loadmore-item">

								    <?php get_template_part('content', 'media'); ?>

								</div>

							<?php endwhile; ?>

							</div>

							<?php wp_reset_postdata(); ?>

				        <?php else : ?>
				                <?php get_template_part( 'content', 'none' ); ?>
				        <?php endif; ?>
			        </div>
				</div>

			<?php else: ?>

				<div class="row">
	        		<div class="col-xs-12 col-md-8">
	        			<?php printf( __('To see the content you need to %1$slogin%2$s first.', 'emigma' ), '<a href="'.esc_url( wp_login_url($_SERVER['REQUEST_URI']) ).'">', '</a>' ); ?>
	        		</div>
	        	</div>

			<?php endif; ?>

        </main><!-- #main -->
</div><!-- #primary -->
<?php get_footer(); ?>