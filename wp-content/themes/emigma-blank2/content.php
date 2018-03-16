<?php
/**
 * @package emigma
 */

?>
<?php $width = 12; ?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="row">
		<?php if(has_post_thumbnail()): ?>
			<?php $width = 8; ?>
			<div class="hidden-xs col-sm-4 pull-right">
				<div class="gallery-icon">
					<a href="<?php the_permalink(); ?>"><?php get_template_part('elements/element', 'thumbnail'); ?></a>
				</div>
			</div>
		<?php endif; ?>

		<div class="col-xs-12 col-sm-<?php echo $width; ?>">
			<div class="entry-meta">
				<?php get_template_part('elements/element', 'entrymeta'); ?>
			</div><!-- .entry-meta -->
			<header class="entry-header">
				<h3 class="entry-title">
					<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
				</h3>
			</header><!-- .entry-header -->

			<?php if($post->post_excerpt): ?>
				<div class="entry-summary"><?php echo $post->post_excerpt; ?></div>
			<?php endif; ?>

			<div class="clearfix"></div>
		</div><!-- .col-xs-12 -->
		
		<div class="col-xs-12">
			<footer class="entry-footer pull-right-sm">
				<?php get_template_part('elements/element', 'readMore'); ?>
			</footer><!-- .entry-footer -->
		</div>
	</div><!-- .row -->
</article><!-- #post-## -->