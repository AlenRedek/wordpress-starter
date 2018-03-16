<?php
/**
 * Template Name: Full-width(no sidebar)
 *
 * This is the template that displays full width page without sidebar
 *
 * @package emigma
 */
$news = $qury->get_posts_after('post', 1, '2 years ago');
$events = $qury->get_eventdate_posts('event', 2, array( 'meta_value' => 'ASC'), 'event_date', date('Y-m-d H:i:s'), 'CHAR', '>=');
get_header(); ?>
	</div><!-- close .row -->
</div><!-- close .container -->
	<div id="front-page" class="content-area">
		<main id="main" class="site-main" role="main">

			<div id="home-banner">
				<?php get_sidebar('statichero'); ?>
			</div>

			<div id="home-news">
			<?php if ( $news->have_posts() ) : ?>
				<div class="container">
	                <?php while ( $news->have_posts() ) : $news->the_post(); ?>
	                	<div class="col-xs-12 panel panel-primary panel-no-spacing panel-top-offset">
	                		<div class="panel-body">
								<?php get_template_part('content', get_post_format() ); ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
		    <?php endif; ?>
			</div>

			<div id="home-events">
			<?php if ( $events->have_posts() ) : ?>
				<div class="container">
	                <?php while ( $events->have_posts() ) : $events->the_post(); ?>
	                	<div class="col-xs-12 col-md-6 panel panel-default panel-no-spacing">
	                		<div class="panel-body">
								<?php get_template_part('content', 'event' ); ?>
							</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				</div>
		    <?php endif; ?>
			</div>

			<div id="home-widgets">
				<div class="container-fluid">
					<div class="row">
	        			<?php get_sidebar('home'); ?>
	        		</div>
	        	</div>
			</div>

		</main><!-- #main -->
	</div><!-- #primary -->
<div class="container main-content-area">
	<div class="row">
<?php get_footer(); ?>
