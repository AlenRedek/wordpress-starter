<?php

global $youtube;

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('gallery-article gallery-icon enable-title-box'); ?>>
	<a href="<?php echo get_permalink(); ?>">
		<?php get_template_part('elements/element', 'thumbnail'); ?>
		<footer class="entry-meta title-box bottom">
			<h5 class="text-white xs-m-0"><?php echo get_date_format(get_the_date(), 'd. F'); ?></h5>
			<h5 class="entry-title xs-m-0"><?php the_title(); ?></h5>
			<?php echo $youtube->get_min_duration($post->post_content); ?>
		</footer><!-- .entry-meta -->
	</a>
</article><!-- #post-## -->