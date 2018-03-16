<?php
/**
 * @package emigma
 */
global $hide_readmore;
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-meta uppercase">
		<?php get_template_part('metas/meta'); ?>
	</div><!-- .entry-meta -->
	<header class="entry-header">
		<div class="row">
			<div class="col-xs-12 col-sm-8">
				<h3 class="entry-title uppercase">
					<?php if($post->post_content): ?>
						<a href="<?php if($post->post_content) the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
					<?php else: ?>
						<?php the_title(); ?>
					<?php endif; ?>
				</h3>
			</div>
			<div class="col-xs-12 col-sm-4 text-right-sm">
				<h3 class="xs-m-0 text-base"><?php echo get_date_format($post->event_date, 'd. M'); ?></h3>
				<h3 class="xs-m-0 text-base"><?php echo get_date_format($post->event_date, 'H:i'); ?></h3>
			</div>
		</div>
	</header><!-- .entry-header -->

	<footer class="entry-footer pull-right-sm">
		<?php if( ! $post->post_content ) $hide_readmore = true; ?>
		<?php get_template_part('elements/element', 'readMore'); ?>
	</footer><!-- .entry-meta -->
	<div class="clearfix"></div>
</article><!-- #post-## -->