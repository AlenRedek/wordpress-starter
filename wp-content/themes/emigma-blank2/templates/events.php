<?php
/**
 * Template name: Events
**/

global $qury;
$events = $qury->get_eventdate_posts('event', -1, array( 'meta_value' => 'ASC'), 'event_date', date('Y-m-d H:i:s'), 'CHAR', '>=');
get_header();

?>

<div id="primary" class="col-xs-12">
        <main id="main" class="site-main" role="main">
        	<div class="row">
        		<div class="col-xs-8">
        			<?php the_content(); ?>
        		</div>
        	</div>
        	<div class="row">

		        <?php if ( $events->have_posts() ) : ?>
	                <?php /* Start the Loop */ ?>

	                <?php while ( $events->have_posts() ) : $events->the_post(); ?>

	                	<?php $month = ar_group_posts_by_month($post->event_date, 'M Y'); ?>
                    		<?php if($month): ?>
                                <div class="col-xs-12">
                                    <h4 class=""><span><?php echo $month[0]; ?></span>&nbsp;<span><?php echo $month[1]; ?></span></h4>
                                </div>
                        <?php endif; ?>

	                	<div class="col-xs-12 col-md-6">
	                		<div class="panel panel-default panel-no-spacing">
	                			<div class="panel-body">
									<?php get_template_part( 'content', 'event' ); ?>
								</div>
	                		</div>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
		        <?php endif; ?>

		    </div>
        </main><!-- #main -->
</div><!-- #primary -->
<?php get_footer(); ?>