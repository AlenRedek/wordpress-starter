<?php
/**
 * Template name: News
**/

global $qury, $cpt, $year;
$cpt = 'post';
$year = isset($_GET['years']) ? $_GET['years'] : date('Y');
$news = $qury->get_posts_by_year($cpt, $year);

do_action('ar_loadmore_init');
get_header();

?>

<div id="primary" class="col-xs-12">
        <main id="main" class="site-main" role="main">

        	<div class="row">
        		<div class="col-xs-12 col-md-8">
        			<?php the_content(); ?>
        		</div>
        	</div>

        	<div class="row">
        		<div class="col-xs-12">
			        <?php if ( $news->have_posts() ) : ?>

		        		<?php get_template_part('elements/element', 'selectYear'); ?>

		                <?php /* Start the Loop */ ?>
		                <div class="loadmore-container row">

		                <?php while ( $news->have_posts() ) : $news->the_post(); ?>

							<div class="col-xs-12 col-md-8 loadmore-item">

							    <?php get_template_part('content'); ?>

							</div>

						<?php endwhile; ?>

						</div>

						<?php wp_reset_postdata(); ?>

			        <?php else : ?>
			                <?php get_template_part( 'content', 'none' ); ?>
			        <?php endif; ?>
		        </div>

		    </div>
        </main><!-- #main -->
</div><!-- #primary -->
<?php get_footer(); ?>