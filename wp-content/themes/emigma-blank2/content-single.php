<?php
/**
 * @package emigma
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<header class="entry-header page-header">
		<div class="entry-meta">
			<?php get_template_part('elements/element', 'entrymeta'); ?>
		</div><!-- .entry-meta -->
		<h3 class="entry-title"><?php the_title(); ?></h3>
		<div class="entry-meta">
			<?php get_template_part('metas/meta'); ?>
		</div><!-- .entry-meta -->
	</header><!-- .entry-header -->
	<div class="entry-content">
		<?php the_content(); ?>
	</div><!-- .entry-content -->
	<footer class="entry-meta">
		<?php emigma_setPostViews(get_the_ID()); ?>
	</footer><!-- .entry-meta -->
</article><!-- #post-## -->